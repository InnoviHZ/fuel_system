<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shift;
use App\Models\Pump;
use App\Models\ShiftPumpReading;
use App\Models\StockAlert;
use App\Models\Tank;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of sales
     */
    public function index()
    {
        $query = Sale::with(['shift', 'pump.tank.fuelProduct', 'fuelProduct', 'attendant']);

        // Attendants see only their own sales
        if (Auth::user()->hasRole('attendant')) {
            $query->where('attendant_id', Auth::id());
        }

        $sales = $query->orderBy('sale_time', 'desc')->paginate(20);

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale
     */
    public function create()
    {
        $activeShift = Shift::where('shift_date', today())
            ->where('status', 'open')
            ->first();

        if (!$activeShift) {
            return redirect()->route('sales.index')
                ->with('error', 'No active shift found. Please contact your supervisor to open a shift.');
        }

        // Get pumps assigned to this attendant in the active shift
        $assignedPumps = ShiftPumpReading::where('shift_id', $activeShift->id)
            ->where('attendant_id', Auth::id())
            ->with('pump.tank.fuelProduct')
            ->get()
            ->pluck('pump');

        if ($assignedPumps->isEmpty()) {
            return redirect()->route('sales.index')
                ->with('error', 'No pumps assigned to you in the current shift.');
        }

        // Get active credit customers
        $creditCustomers = \App\Models\CreditCustomer::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('activeShift', 'assignedPumps', 'creditCustomers'));
    }

    /**
     * Store a newly created sale
     */
    public function store(StoreSaleRequest $request)
    {
        try {
            $activeShift = Shift::where('shift_date', today())
                ->where('status', 'open')
                ->first();

            if (!$activeShift) {
                return redirect()->route('sales.index')
                    ->with('error', 'No active shift found');
            }

            DB::transaction(function () use ($request, $activeShift) {
                $pump = Pump::findOrFail($request->pump_id);

                // Check if pump is assigned to this attendant
                $assignment = ShiftPumpReading::where('shift_id', $activeShift->id)
                    ->where('pump_id', $pump->id)
                    ->where('attendant_id', Auth::id())
                    ->first();

                if (!$assignment) {
                    throw new \Exception('This pump is not assigned to you');
                }

                // Get fuel product and current price
                $fuelProduct = $pump->tank->fuelProduct;
                $unitPrice = $fuelProduct->current_price;
                $totalAmount = $request->quantity_litres * $unitPrice;

                // Check tank has enough stock
                if ($pump->tank->current_stock_litres < $request->quantity_litres) {
                    throw new \Exception('Insufficient stock. Available: ' . $pump->tank->current_stock_litres . 'L');
                }

                // If credit sale, check credit limit
                if ($request->payment_method === 'credit') {
                    $creditCustomer = \App\Models\CreditCustomer::findOrFail($request->credit_customer_id);

                    if (!$creditCustomer->is_active) {
                        throw new \Exception('Credit customer is inactive');
                    }

                    if (!$creditCustomer->canMakePurchase($totalAmount)) {
                        $availableCredit = $creditCustomer->credit_limit - $creditCustomer->current_balance;
                        throw new \Exception('Credit limit exceeded. Available credit: ' . number_format($availableCredit, 2));
                    }
                }

                // Create the sale
                $sale = Sale::create([
                    'shift_id' => $activeShift->id,
                    'pump_id' => $pump->id,
                    'fuel_product_id' => $fuelProduct->id,
                    'attendant_id' => Auth::id(),
                    'quantity_litres' => $request->quantity_litres,
                    'unit_price' => $unitPrice,
                    'total_amount' => $totalAmount,
                    'payment_method' => $request->payment_method,
                    'credit_customer_id' => $request->credit_customer_id,
                    'sale_time' => now(),
                ]);

                // Update credit customer balance if credit sale
                if ($request->payment_method === 'credit') {
                    $creditCustomer->update([
                        'current_balance' => $creditCustomer->current_balance + $totalAmount,
                    ]);
                }

                // Deduct from tank stock
                $tank = $pump->tank;
                $newStock = $tank->current_stock_litres - $request->quantity_litres;
                $tank->update(['current_stock_litres' => $newStock]);

                // Check for stock alerts
                if ($newStock <= $tank->critical_stock_threshold) {
                    // Create critical stock alert if not already exists
                    $existingAlert = StockAlert::where('tank_id', $tank->id)
                        ->where('alert_type', 'critical_stock')
                        ->where('is_resolved', false)
                        ->first();

                    if (!$existingAlert) {
                        StockAlert::create([
                            'tank_id' => $tank->id,
                            'alert_type' => 'critical_stock',
                            'stock_level_at_alert' => $newStock,
                        ]);
                    }
                } elseif ($newStock <= $tank->low_stock_threshold) {
                    // Create low stock alert if not already exists
                    $existingAlert = StockAlert::where('tank_id', $tank->id)
                        ->where('alert_type', 'low_stock')
                        ->where('is_resolved', false)
                        ->first();

                    if (!$existingAlert) {
                        StockAlert::create([
                            'tank_id' => $tank->id,
                            'alert_type' => 'low_stock',
                            'stock_level_at_alert' => $newStock,
                        ]);
                    }
                }
            });

            return redirect()->route('sales.index')
                ->with('success', 'Sale recorded successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified sale
     */
    public function show(Sale $sale)
    {
        // Attendants can only view their own sales
        if (Auth::user()->hasRole('attendant') && $sale->attendant_id !== Auth::id()) {
            abort(403);
        }

        $sale->load(['shift', 'pump.tank.fuelProduct', 'fuelProduct', 'attendant', 'creditCustomer']);

        return view('sales.show', compact('sale'));
    }

    /**
     * Get pump information (AJAX endpoint)
     */
    public function getPumpInfo(Pump $pump)
    {
        $activeShift = Shift::where('shift_date', today())
            ->where('status', 'open')
            ->first();

        if (!$activeShift) {
            return response()->json(['error' => 'No active shift'], 400);
        }

        // Check if pump is assigned to this attendant
        $assignment = ShiftPumpReading::where('shift_id', $activeShift->id)
            ->where('pump_id', $pump->id)
            ->where('attendant_id', Auth::id())
            ->first();

        if (!$assignment) {
            return response()->json(['error' => 'Pump not assigned to you'], 403);
        }

        $pump->load('tank.fuelProduct');

        return response()->json([
            'fuel_type' => $pump->tank->fuelProduct->name,
            'fuel_code' => $pump->tank->fuelProduct->code,
            'unit_price' => $pump->tank->fuelProduct->current_price,
            'tank_stock' => $pump->tank->current_stock_litres,
        ]);
    }
}
