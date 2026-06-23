<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\FuelProduct;
use App\Http\Requests\StoreTankRequest;
use App\Http\Requests\UpdateTankRequest;

class TankController extends Controller
{
    /**
     * Display a listing of tanks
     */
    public function index()
    {
        $this->authorizeRoles(['admin', 'manager', 'supervisor']);
        
        $tanks = Tank::with('fuelProduct')->paginate(15);
        return view('tanks.index', compact('tanks'));
    }

    /**
     * Show the form for creating a new tank
     */
    public function create()
    {
        $this->authorize('admin');
        
        $fuelProducts = FuelProduct::where('is_active', true)->get();
        return view('tanks.create', compact('fuelProducts'));
    }

    /**
     * Store a newly created tank in storage
     */
    public function store(StoreTankRequest $request)
    {
        $this->authorize('admin');
        
        Tank::create($request->validated());

        return redirect()
            ->route('tanks.index')
            ->with('success', 'Tank created successfully');
    }

    /**
     * Display the specified tank
     */
    public function show(Tank $tank)
    {
        $this->authorizeRoles(['admin', 'manager', 'supervisor']);
        
        $stockPercentage = $tank->getStockPercentage();
        $isCritical = $tank->isCriticalStock();
        $isLow = $tank->isLowStock();
        
        return view('tanks.show', compact('tank', 'stockPercentage', 'isCritical', 'isLow'));
    }

    /**
     * Show the form for editing the specified tank
     */
    public function edit(Tank $tank)
    {
        $this->authorize('admin');
        
        $fuelProducts = FuelProduct::where('is_active', true)->get();
        return view('tanks.edit', compact('tank', 'fuelProducts'));
    }

    /**
     * Update the specified tank in storage
     */
    public function update(UpdateTankRequest $request, Tank $tank)
    {
        $this->authorize('admin');
        
        $tank->update($request->validated());

        return redirect()
            ->route('tanks.index')
            ->with('success', 'Tank updated successfully');
    }

    /**
     * Remove the specified tank from storage
     */
    public function destroy(Tank $tank)
    {
        $this->authorize('admin');
        
        // Check if tank has pumps
        if ($tank->pumps()->exists()) {
            return redirect()
                ->route('tanks.index')
                ->with('error', 'Cannot delete tank with associated pumps');
        }
        
        $tankName = $tank->name;
        $tank->delete();

        return redirect()
            ->route('tanks.index')
            ->with('success', "Tank {$tankName} deleted successfully");
    }

    /**
     * Authorize the action based on single role
     */
    protected function authorize($role)
    {
        if (!auth()->user()?->hasRole($role)) {
            abort(403, 'Unauthorized access');
        }
    }

    /**
     * Authorize the action based on multiple roles
     */
    protected function authorizeRoles($roles)
    {
        if (!auth()->user()?->hasAnyRole($roles)) {
            abort(403, 'Unauthorized access');
        }
    }
}
