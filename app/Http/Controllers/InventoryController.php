<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\StockAlert;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display all tanks with current stock levels
     */
    public function index()
    {
        $tanks = Tank::with('fuelProduct', 'activeAlerts')
            ->orderBy('name')
            ->paginate(12);

        return view('inventory.index', compact('tanks'));
    }

    /**
     * Display all unresolved stock alerts
     */
    public function alerts()
    {
        $alerts = StockAlert::where('is_resolved', false)
            ->with('tank.fuelProduct')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('inventory.alerts', compact('alerts'));
    }
}
