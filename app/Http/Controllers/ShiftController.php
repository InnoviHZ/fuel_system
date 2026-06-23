<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\ShiftPumpReading;
use App\Models\Pump;
use App\Models\User;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\CloseShiftRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts
     */
    public function index()
    {
        $shifts = Shift::with(['openedByUser', 'closedByUser', 'pumpReadings'])
            ->orderBy('shift_date', 'desc')
            ->orderBy('shift_type', 'desc')
            ->paginate(10);

        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new shift
     */
    public function create()
    {
        $pumps = Pump::with('tank.fuelProduct')
            ->where('is_active', true)
            ->get();

        $attendants = User::where('role', 'attendant')
            ->where('is_active', true)
            ->get();

        return view('shifts.create', compact('pumps', 'attendants'));
    }

    /**
     * Store a newly created shift in the database
     */
    public function store(StoreShiftRequest $request)
    {
        // Check if shift of same type already exists for same date
        $existingShift = Shift::where('shift_date', $request->shift_date)
            ->where('shift_type', $request->shift_type)
            ->where('status', 'open')
            ->first();

        if ($existingShift) {
            return back()
                ->withInput()
                ->withErrors(['error' => "A {$request->shift_type} shift is already open for this date"]);
        }

        try {
            DB::transaction(function () use ($request) {
                // Create the shift
                $shift = Shift::create([
                    'shift_date' => $request->shift_date,
                    'shift_type' => $request->shift_type,
                    'status' => 'open',
                    'opened_by' => Auth::id(),
                    'opened_at' => now(),
                    'notes' => $request->notes,
                ]);

                // Create shift pump readings
                foreach ($request->pump_readings as $reading) {
                    $lastReading = ShiftPumpReading::where('pump_id', $reading['pump_id'])
                        ->whereHas('shift', function ($q) {
                            $q->where('status', 'closed');
                        })
                        ->orderBy('created_at', 'desc')
                        ->first();

                    // Validate opening meter >= last closing meter
                    if ($lastReading && $reading['opening_meter'] < $lastReading->closing_meter) {
                        throw new \Exception("Opening meter for pump {$reading['pump_id']} cannot be less than previous closing meter");
                    }

                    ShiftPumpReading::create([
                        'shift_id' => $shift->id,
                        'pump_id' => $reading['pump_id'],
                        'attendant_id' => $reading['attendant_id'],
                        'opening_meter' => $reading['opening_meter'],
                    ]);
                }
            });

            return redirect()
                ->route('shifts.index')
                ->with('success', 'Shift opened successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified shift
     */
    public function show(Shift $shift)
    {
        $shift->load([
            'openedByUser',
            'closedByUser',
            'pumpReadings.pump.tank.fuelProduct',
            'pumpReadings.attendant',
            'sales.attendant',
        ]);

        $totalLitresSold = $shift->pumpReadings->sum('litres_sold') ?? 0;
        $totalRevenue = $shift->sales->sum('total_amount') ?? 0;

        return view('shifts.show', compact('shift', 'totalLitresSold', 'totalRevenue'));
    }

    /**
     * Show the form for closing a shift
     */
    public function close(Shift $shift)
    {
        if ($shift->status !== 'open') {
            return redirect()
                ->route('shifts.show', $shift)
                ->with('error', 'Only open shifts can be closed');
        }

        $shift->load([
            'pumpReadings.pump.tank.fuelProduct',
            'pumpReadings.attendant',
        ]);

        return view('shifts.close', compact('shift'));
    }

    /**
     * Update the specified shift to closed status
     */
    public function closeShift(CloseShiftRequest $request, Shift $shift)
    {
        if ($shift->status !== 'open') {
            return redirect()
                ->route('shifts.show', $shift)
                ->with('error', 'Only open shifts can be closed');
        }

        try {
            DB::transaction(function () use ($request, $shift) {
                foreach ($request->pump_readings as $reading) {
                    $pumpReading = ShiftPumpReading::where('shift_id', $shift->id)
                        ->where('pump_id', $reading['pump_id'])
                        ->firstOrFail();

                    // Validate closing meter >= opening meter
                    if ($reading['closing_meter'] < $pumpReading->opening_meter) {
                        throw new \Exception(
                            "Closing meter cannot be less than opening meter for pump {$reading['pump_id']}"
                        );
                    }

                    // Calculate litres sold
                    $litresSold = $reading['closing_meter'] - $pumpReading->opening_meter;

                    $pumpReading->update([
                        'closing_meter' => $reading['closing_meter'],
                        'litres_sold' => $litresSold,
                    ]);
                }

                // Close the shift
                $shift->update([
                    'status' => 'closed',
                    'closed_by' => Auth::id(),
                    'closed_at' => now(),
                    'notes' => $request->notes,
                ]);
            });

            return redirect()
                ->route('shifts.show', $shift)
                ->with('success', 'Shift closed successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}