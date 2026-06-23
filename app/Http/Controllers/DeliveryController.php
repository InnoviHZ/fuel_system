<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Tank;
use App\Models\FuelProduct;
use App\Models\StockAlert;
use App\Http\Requests\StoreDeliveryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries
     */
    public function index()
    {
        $deliveries = Delivery::with([
            'tank.fuelProduct',
            'fuelProduct',
            'deliveredBy'
        ])->orderBy('delivery_date', 'desc')->paginate(20);

        return view('deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new delivery
     */
    public function create()
    {
        // Authorize supervisor or admin
        if (!Auth::user()->hasRole('supervisor', 'admin')) {
            abort(403);
        }

        $tanks = Tank::with('fuelProduct')
            ->where('is_active', true)
            ->get();

        $fuelProducts = FuelProduct::where('is_active', true)->get();

        return view('deliveries.create', compact('tanks', 'fuelProducts'));
    }

    /**
     * Store a newly created delivery
     */
    public function store(StoreDeliveryRequest $request)
    {
        try {
            // Authorize supervisor or admin
            if (!Auth::user()->hasRole('supervisor', 'admin')) {
                abort(403);
            }

            DB::transaction(function () use ($request) {
                $tank = Tank::findOrFail($request->tank_id);
                $stockBefore = $tank->current_stock_litres;
                $quantity = (float) $request->quantity_litres;
                $unitCost = (float) $request->unit_cost;
                $totalCost = $quantity * $unitCost;
                $stockAfter = $stockBefore + $quantity;

                // Check stock won't exceed capacity
                if ($stockAfter > $tank->capacity_litres) {
                    throw new \Exception('Stock delivery would exceed tank capacity. Max: ' . $tank->capacity_litres . 'L');
                }

                // Create delivery record
                $delivery = Delivery::create([
                    'tank_id' => $tank->id,
                    'fuel_product_id' => $request->fuel_product_id,
                    'supplier_name' => $request->supplier_name,
                    'waybill_number' => $request->waybill_number,
                    'quantity_litres' => $quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $totalCost,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'delivered_by' => Auth::id(),
                    'delivery_date' => $request->delivery_date,
                    'notes' => $request->notes,
                ]);

                // Update tank stock
                $tank->update(['current_stock_litres' => $stockAfter]);

                // Resolve stock alerts if stock is now above threshold
                if ($stockBefore <= $tank->low_stock_threshold && $stockAfter > $tank->low_stock_threshold) {
                    StockAlert::where('tank_id', $tank->id)
                        ->where('alert_type', 'low_stock')
                        ->where('is_resolved', false)
                        ->update([
                            'is_resolved' => true,
                            'resolved_at' => now(),
                        ]);
                }

                if ($stockBefore <= $tank->critical_stock_threshold && $stockAfter > $tank->critical_stock_threshold) {
                    StockAlert::where('tank_id', $tank->id)
                        ->where('alert_type', 'critical_stock')
                        ->where('is_resolved', false)
                        ->update([
                            'is_resolved' => true,
                            'resolved_at' => now(),
                        ]);
                }
            });

            return redirect()->route('deliveries.index')
                ->with('success', 'Fuel delivery recorded successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified delivery
     */
    public function show(Delivery $delivery)
    {
        $delivery->load([
            'tank.fuelProduct',
            'fuelProduct',
            'deliveredBy'
        ]);

        return view('deliveries.show', compact('delivery'));
    }

    /**
     * Get tank information (AJAX endpoint)
     */
    public function getTankInfo(Tank $tank)
    {
        $tank->load('fuelProduct');

        return response()->json([
            'fuel_product_name' => $tank->fuelProduct->name,
            'fuel_code' => $tank->fuelProduct->code,
            'current_stock' => (float) $tank->current_stock_litres,
            'capacity' => (float) $tank->capacity_litres,
        ]);
    }
}
