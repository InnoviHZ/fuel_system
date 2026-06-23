<?php

namespace App\Http\Controllers;

use App\Models\FuelProduct;
use App\Models\PriceHistory;
use App\Http\Requests\StoreFuelProductRequest;
use App\Http\Requests\UpdateFuelProductRequest;

class FuelProductController extends Controller
{
    /**
     * Display a listing of fuel products
     */
    public function index()
    {
        $this->authorizeRoles(['admin', 'manager']);
        
        $fuelProducts = FuelProduct::paginate(15);
        return view('fuel-products.index', compact('fuelProducts'));
    }

    /**
     * Show the form for creating a new fuel product
     */
    public function create()
    {
        $this->authorizeRoles(['admin', 'manager']);
        
        return view('fuel-products.create');
    }

    /**
     * Store a newly created fuel product in storage
     */
    public function store(StoreFuelProductRequest $request)
    {
        $this->authorizeRoles(['admin', 'manager']);
        
        FuelProduct::create($request->validated());

        return redirect()
            ->route('fuel-products.index')
            ->with('success', 'Fuel product created successfully');
    }

    /**
     * Display the specified fuel product
     */
    public function show(FuelProduct $fuelProduct)
    {
        $this->authorizeRoles(['admin', 'manager']);
        
        $priceHistories = $fuelProduct->priceHistories()->with('changedBy')->latest()->paginate(10);
        return view('fuel-products.show', compact('fuelProduct', 'priceHistories'));
    }

    /**
     * Show the form for editing the specified fuel product
     */
    public function edit(FuelProduct $fuelProduct)
    {
        $this->authorizeRoles(['admin', 'manager']);
        
        return view('fuel-products.edit', compact('fuelProduct'));
    }

    /**
     * Update the specified fuel product in storage
     */
    public function update(UpdateFuelProductRequest $request, FuelProduct $fuelProduct)
    {
        $this->authorizeRoles(['admin', 'manager']);
        
        $data = $request->validated();
        
        // If price has changed, save the old price to history
        if ($fuelProduct->current_price != $data['current_price']) {
            PriceHistory::create([
                'fuel_product_id' => $fuelProduct->id,
                'old_price' => $fuelProduct->current_price,
                'new_price' => $data['current_price'],
                'changed_by' => auth()->id(),
            ]);
        }
        
        $fuelProduct->update($data);

        return redirect()
            ->route('fuel-products.index')
            ->with('success', 'Fuel product updated successfully');
    }

    /**
     * Remove the specified fuel product from storage
     */
    public function destroy(FuelProduct $fuelProduct)
    {
        $this->authorizeRoles(['admin', 'manager']);
        
        // Check if product has tanks
        if ($fuelProduct->tanks()->exists()) {
            return redirect()
                ->route('fuel-products.index')
                ->with('error', 'Cannot delete fuel product with associated tanks');
        }
        
        $productName = $fuelProduct->name;
        $fuelProduct->delete();

        return redirect()
            ->route('fuel-products.index')
            ->with('success', "Fuel product {$productName} deleted successfully");
    }

    /**
     * Authorize the action based on roles
     */
    protected function authorizeRoles($roles)
    {
        if (!auth()->user()?->hasAnyRole($roles)) {
            abort(403, 'Unauthorized access');
        }
    }
}
