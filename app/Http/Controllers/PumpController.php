<?php

namespace App\Http\Controllers;

use App\Models\Pump;
use App\Models\Tank;
use App\Http\Requests\StorePumpRequest;
use App\Http\Requests\UpdatePumpRequest;

class PumpController extends Controller
{
    /**
     * Display a listing of pumps
     */
    public function index()
    {
        $this->authorize('admin');
        
        $pumps = Pump::with('tank.fuelProduct')->paginate(15);
        return view('pumps.index', compact('pumps'));
    }

    /**
     * Show the form for creating a new pump
     */
    public function create()
    {
        $this->authorize('admin');
        
        $tanks = Tank::where('is_active', true)->get();
        return view('pumps.create', compact('tanks'));
    }

    /**
     * Store a newly created pump in storage
     */
    public function store(StorePumpRequest $request)
    {
        $this->authorize('admin');
        
        Pump::create($request->validated());

        return redirect()
            ->route('pumps.index')
            ->with('success', 'Pump created successfully');
    }

    /**
     * Display the specified pump
     */
    public function show(Pump $pump)
    {
        $this->authorize('admin');
        
        return view('pumps.show', compact('pump'));
    }

    /**
     * Show the form for editing the specified pump
     */
    public function edit(Pump $pump)
    {
        $this->authorize('admin');
        
        $tanks = Tank::where('is_active', true)->get();
        return view('pumps.edit', compact('pump', 'tanks'));
    }

    /**
     * Update the specified pump in storage
     */
    public function update(UpdatePumpRequest $request, Pump $pump)
    {
        $this->authorize('admin');
        
        $pump->update($request->validated());

        return redirect()
            ->route('pumps.index')
            ->with('success', 'Pump updated successfully');
    }

    /**
     * Remove the specified pump from storage
     */
    public function destroy(Pump $pump)
    {
        $this->authorize('admin');
        
        $pumpName = $pump->name;
        $pump->delete();

        return redirect()
            ->route('pumps.index')
            ->with('success', "Pump {$pumpName} deleted successfully");
    }

    /**
     * Authorize the action
     */
    protected function authorize($role)
    {
        if (!auth()->user()?->hasRole($role)) {
            abort(403, 'Unauthorized access');
        }
    }
}
