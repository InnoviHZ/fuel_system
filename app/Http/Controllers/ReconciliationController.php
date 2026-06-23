<?php

namespace App\Http\Controllers;

use App\Models\Reconciliation;
use App\Models\Shift;
use App\Models\Sale;
use App\Http\Requests\StoreReconciliationRequest;
use App\Http\Requests\ReviewReconciliationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReconciliationController extends Controller
{
    /**
     * Display a listing of reconciliations
     */
    public function index()
    {
        $reconciliations = Reconciliation::with([
            'shift',
            'reconciledBy',
            'reviewedBy'
        ])->orderBy('created_at', 'desc')->paginate(20);

        return view('reconciliations.index', compact('reconciliations'));
    }

    /**
     * Show the form for creating a new reconciliation
     */
    public function create(Shift $shift)
    {
        // Authorize supervisor or admin
        if (!Auth::user()->hasRole('supervisor', 'admin')) {
            abort(403);
        }

        // Check shift is closed
        if ($shift->status !== 'closed') {
            return redirect()->back()->with('error', 'Only closed shifts can be reconciled.');
        }

        // Check if reconciliation already exists for this shift
        $existingReconciliation = Reconciliation::where('shift_id', $shift->id)->first();
        if ($existingReconciliation) {
            return redirect()->route('reconciliations.show', $existingReconciliation)->with('info', 'Reconciliation already exists for this shift.');
        }

        // Get all sales for this shift
        $sales = Sale::where('shift_id', $shift->id)
            ->with(['pump.tank.fuelProduct', 'fuelProduct', 'attendant'])
            ->orderBy('sale_time', 'asc')
            ->get();

        // Calculate totals
        $totalLitresSold = $sales->sum('quantity_litres');
        $expectedCash = $sales->sum('total_amount');

        // Group sales by pump
        $salesByPump = $sales->groupBy(function ($sale) {
            return $sale->pump->name;
        });

        // Group sales by payment method
        $salesByPaymentMethod = $sales->groupBy('payment_method');

        $shift->load(['openedBy', 'closedBy']);

        return view('reconciliations.create', compact(
            'shift',
            'sales',
            'salesByPump',
            'salesByPaymentMethod',
            'totalLitresSold',
            'expectedCash'
        ));
    }

    /**
     * Store a newly created reconciliation
     */
    public function store(StoreReconciliationRequest $request)
    {
        try {
            $shift = Shift::findOrFail($request->shift_id);

            if ($shift->status !== 'closed') {
                return redirect()->back()->with('error', 'Only closed shifts can be reconciled.');
            }

            // Check if reconciliation already exists
            if (Reconciliation::where('shift_id', $shift->id)->exists()) {
                return redirect()->back()->with('error', 'Reconciliation already exists for this shift.');
            }

            // Calculate totals
            $sales = Sale::where('shift_id', $shift->id)->get();
            $totalLitresSold = $sales->sum('quantity_litres');
            $expectedCash = $sales->sum('total_amount');

            $actualCash = (float) $request->actual_cash;
            $actualPos = (float) $request->actual_pos;
            $actualBankTransfer = (float) $request->actual_bank_transfer;
            $totalCollected = $actualCash + $actualPos + $actualBankTransfer;
            $cashDifference = $totalCollected - $expectedCash;

            // Determine status
            if ($cashDifference == 0) {
                $status = 'balanced';
            } elseif ($cashDifference > 0) {
                $status = 'surplus';
            } else {
                $status = 'shortage';
            }

            DB::transaction(function () use ($shift, $totalLitresSold, $expectedCash, $actualCash, $actualPos, $actualBankTransfer, $totalCollected, $cashDifference, $status, $request) {
                Reconciliation::create([
                    'shift_id' => $shift->id,
                    'reconciled_by' => Auth::id(),
                    'total_litres_sold' => $totalLitresSold,
                    'expected_cash' => $expectedCash,
                    'actual_cash' => $actualCash,
                    'actual_pos' => $actualPos,
                    'actual_bank_transfer' => $actualBankTransfer,
                    'total_collected' => $totalCollected,
                    'cash_difference' => $cashDifference,
                    'status' => $status,
                    'supervisor_note' => $request->supervisor_note,
                ]);
            });

            return redirect()->route('reconciliations.index')
                ->with('success', 'Reconciliation recorded successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified reconciliation
     */
    public function show(Reconciliation $reconciliation)
    {
        $reconciliation->load([
            'shift.openedBy',
            'shift.closedBy',
            'reconciledBy',
            'reviewedBy'
        ]);

        // Get related sales
        $sales = Sale::where('shift_id', $reconciliation->shift_id)
            ->with(['pump.tank.fuelProduct', 'fuelProduct', 'attendant'])
            ->orderBy('sale_time', 'asc')
            ->get();

        $salesByPump = $sales->groupBy(function ($sale) {
            return $sale->pump->name;
        });

        return view('reconciliations.show', compact('reconciliation', 'sales', 'salesByPump'));
    }

    /**
     * Show the form for reviewing a reconciliation
     */
    public function review(Reconciliation $reconciliation)
    {
        // Authorize manager or admin
        if (!Auth::user()->hasRole('manager', 'admin')) {
            abort(403);
        }

        $reconciliation->load(['shift', 'reconciledBy']);

        return view('reconciliations.review', compact('reconciliation'));
    }

    /**
     * Submit review of reconciliation
     */
    public function submitReview(ReviewReconciliationRequest $request, Reconciliation $reconciliation)
    {
        try {
            // Authorize manager or admin
            if (!Auth::user()->hasRole('manager', 'admin')) {
                abort(403);
            }

            DB::transaction(function () use ($reconciliation, $request) {
                $reconciliation->update([
                    'manager_note' => $request->manager_note,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);
            });

            return redirect()->route('reconciliations.show', $reconciliation)
                ->with('success', 'Reconciliation reviewed successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
