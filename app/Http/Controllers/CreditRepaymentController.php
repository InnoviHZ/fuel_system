<?php

namespace App\Http\Controllers;

use App\Models\CreditRepayment;
use App\Models\CreditCustomer;
use App\Http\Requests\StoreCreditRepaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreditRepaymentController extends Controller
{
    /**
     * Display a listing of credit repayments
     */
    public function index()
    {
        // Authorize supervisor or admin
        if (!Auth::user()->hasRole('supervisor', 'admin')) {
            abort(403);
        }

        $repayments = CreditRepayment::with([
            'creditCustomer',
            'receivedBy'
        ])->orderBy('repayment_date', 'desc')->paginate(20);

        return view('credit-repayments.index', compact('repayments'));
    }

    /**
     * Show the form for creating a new credit repayment
     */
    public function create(CreditCustomer $creditCustomer)
    {
        // Authorize supervisor or admin
        if (!Auth::user()->hasRole('supervisor', 'admin')) {
            abort(403);
        }

        $creditCustomer->load('repayments');

        return view('credit-customers.repayment', compact('creditCustomer'));
    }

    /**
     * Store a newly created credit repayment
     */
    public function store(StoreCreditRepaymentRequest $request)
    {
        try {
            // Authorize supervisor or admin
            if (!Auth::user()->hasRole('supervisor', 'admin')) {
                abort(403);
            }

            $creditCustomer = CreditCustomer::findOrFail($request->credit_customer_id);

            // Validate repayment doesn't exceed balance
            $repaymentAmount = (float) $request->amount;
            if ($repaymentAmount > $creditCustomer->current_balance) {
                return back()
                    ->withInput()
                    ->with('error', 'Repayment amount cannot exceed customer balance of ' . number_format($creditCustomer->current_balance, 2));
            }

            DB::transaction(function () use ($creditCustomer, $repaymentAmount, $request) {
                // Create repayment record
                CreditRepayment::create([
                    'credit_customer_id' => $creditCustomer->id,
                    'amount' => $repaymentAmount,
                    'payment_method' => $request->payment_method,
                    'received_by' => Auth::id(),
                    'repayment_date' => $request->repayment_date,
                    'notes' => $request->notes,
                ]);

                // Decrease customer balance
                $creditCustomer->update([
                    'current_balance' => $creditCustomer->current_balance - $repaymentAmount,
                ]);
            });

            return redirect()->route('credit-customers.show', $creditCustomer)
                ->with('success', 'Repayment recorded successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
