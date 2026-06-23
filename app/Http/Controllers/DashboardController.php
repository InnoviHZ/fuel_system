<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Shift;
use App\Models\StockAlert;
use App\Models\Delivery;
use App\Models\Reconciliation;
use App\Models\CreditCustomer;
use App\Models\User;
use App\Models\Tank;
use App\Models\FuelProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        $user = auth()->user();
        
        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'manager' => $this->managerDashboard(),
            'supervisor' => $this->supervisorDashboard(),
            'attendant' => $this->attendantDashboard(),
            default => $this->attendantDashboard(),
        };
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        // Stats
        $todaySalesCount = Sale::whereDate('sale_time', today())->count();
        $todaySalesAmount = Sale::whereDate('sale_time', today())->sum('total_amount');
        $todayLitresSold = Sale::whereDate('sale_time', today())->sum('quantity_litres');
        $activeShifts = Shift::where('status', 'open')->count();
        $unresolvedAlerts = StockAlert::where('is_resolved', false)->count();
        $creditBalance = CreditCustomer::sum('current_balance');
        $todayDeliveries = Delivery::whereDate('delivery_date', today())->count();
        $activeUsers = User::where('is_active', true)->count();
        $monthlyRevenue = Sale::whereMonth('sale_time', now()->month)
            ->whereYear('sale_time', now()->year)
            ->sum('total_amount');

        // Charts Data
        $dailySalesChart = $this->getDailySalesChart();
        $fuelTypeChart = $this->getFuelTypeChart();
        $tankStockChart = $this->getTankStockChart();
        $monthlyRevenueChart = $this->getMonthlyRevenueChart();

        // Recent Activity
        $recentSales = Sale::with(['attendant', 'pump', 'fuelProduct'])
            ->orderBy('sale_time', 'desc')
            ->limit(10)
            ->get();

        $recentDeliveries = Delivery::with(['tank', 'fuelProduct', 'deliveredBy'])
            ->orderBy('delivery_date', 'desc')
            ->limit(5)
            ->get();

        $activeAlerts = StockAlert::with('tank')
            ->where('is_resolved', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.admin', compact(
            'todaySalesCount', 'todaySalesAmount', 'todayLitresSold', 'activeShifts',
            'unresolvedAlerts', 'creditBalance', 'todayDeliveries', 'activeUsers',
            'monthlyRevenue', 'dailySalesChart', 'fuelTypeChart', 'tankStockChart',
            'monthlyRevenueChart', 'recentSales', 'recentDeliveries', 'activeAlerts'
        ));
    }

    /**
     * Manager Dashboard
     */
    private function managerDashboard()
    {
        // Stats
        $todaySalesCount = Sale::whereDate('sale_time', today())->count();
        $todaySalesAmount = Sale::whereDate('sale_time', today())->sum('total_amount');
        $todayLitresSold = Sale::whereDate('sale_time', today())->sum('quantity_litres');
        $activeShifts = Shift::where('status', 'open')->count();
        $unresolvedAlerts = StockAlert::where('is_resolved', false)->count();
        $creditBalance = CreditCustomer::sum('current_balance');
        $todayDeliveries = Delivery::whereDate('delivery_date', today())->count();
        $activeUsers = User::where('is_active', true)->count();
        $monthlyRevenue = Sale::whereMonth('sale_time', now()->month)
            ->whereYear('sale_time', now()->year)
            ->sum('total_amount');

        // Charts Data
        $dailySalesChart = $this->getDailySalesChart();
        $fuelTypeChart = $this->getFuelTypeChart();
        $tankStockChart = $this->getTankStockChart();
        $monthlyRevenueChart = $this->getMonthlyRevenueChart();

        // Recent Activity
        $recentSales = Sale::with(['attendant', 'pump', 'fuelProduct'])
            ->orderBy('sale_time', 'desc')
            ->limit(10)
            ->get();

        $recentDeliveries = Delivery::with(['tank', 'fuelProduct', 'deliveredBy'])
            ->orderBy('delivery_date', 'desc')
            ->limit(5)
            ->get();

        $activeAlerts = StockAlert::with('tank')
            ->where('is_resolved', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.manager', compact(
            'todaySalesCount', 'todaySalesAmount', 'todayLitresSold', 'activeShifts',
            'unresolvedAlerts', 'creditBalance', 'todayDeliveries', 'activeUsers',
            'monthlyRevenue', 'dailySalesChart', 'fuelTypeChart', 'tankStockChart',
            'monthlyRevenueChart', 'recentSales', 'recentDeliveries', 'activeAlerts'
        ));
    }

    /**
     * Supervisor Dashboard
     */
    private function supervisorDashboard()
    {
        $user = auth()->user();

        // Today's shift status
        $todayShift = Shift::whereDate('shift_date', today())->first();
        $shiftOpen = $todayShift && $todayShift->status === 'open';

        // Today's sales
        $todaySalesCount = Sale::whereDate('sale_time', today())->count();
        $todaySalesAmount = Sale::whereDate('sale_time', today())->sum('total_amount');
        $todayLitresSold = Sale::whereDate('sale_time', today())->sum('quantity_litres');

        // Pending reconciliations
        $pendingReconciliations = Reconciliation::where('reviewed_by', null)->count();

        // Tank alerts
        $tankAlerts = StockAlert::with('tank')
            ->where('is_resolved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // Recent shifts
        $recentShifts = Shift::orderBy('created_at', 'desc')->limit(5)->get();

        // Tank stock levels for quick view
        $tanks = Tank::with('fuelProduct')
            ->where('is_active', true)
            ->get();

        return view('dashboards.supervisor', compact(
            'todayShift', 'shiftOpen', 'todaySalesCount', 'todaySalesAmount',
            'todayLitresSold', 'pendingReconciliations', 'tankAlerts',
            'recentShifts', 'tanks'
        ));
    }

    /**
     * Attendant Dashboard
     */
    private function attendantDashboard()
    {
        $user = auth()->user();

        // Current shift info
        $todayShift = Shift::whereDate('shift_date', today())->first();
        $shiftOpen = $todayShift && $todayShift->status === 'open';

        // My sales today
        $myTodaySalesCount = Sale::where('attendant_id', $user->id)
            ->whereDate('sale_time', today())
            ->count();
        $myTodaySalesAmount = Sale::where('attendant_id', $user->id)
            ->whereDate('sale_time', today())
            ->sum('total_amount');

        // My sales this week
        $myWeeklySalesAmount = Sale::where('attendant_id', $user->id)
            ->whereBetween('sale_time', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total_amount');

        // My recent sales
        $myRecentSales = Sale::where('attendant_id', $user->id)
            ->with(['pump', 'fuelProduct', 'shift'])
            ->orderBy('sale_time', 'desc')
            ->limit(10)
            ->get();

        return view('dashboards.attendant', compact(
            'todayShift', 'shiftOpen', 'myTodaySalesCount', 'myTodaySalesAmount',
            'myWeeklySalesAmount', 'myRecentSales'
        ));
    }

    /**
     * Get daily sales chart data (last 7 days)
     */
    private function getDailySalesChart()
    {
        $dates = [];
        $cashSales = [];
        $creditSales = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[] = now()->subDays($i)->format('M d');

            $cash = Sale::whereDate('sale_time', $date)
                ->where('payment_method', '!=', 'credit')
                ->sum('total_amount');
            $credit = Sale::whereDate('sale_time', $date)
                ->where('payment_method', 'credit')
                ->sum('total_amount');

            $cashSales[] = (float) $cash;
            $creditSales[] = (float) $credit;
        }

        return json_encode([
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Cash Sales',
                    'data' => $cashSales,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'tension' => 0.4,
                    'fill' => true,
                ],
                [
                    'label' => 'Credit Sales',
                    'data' => $creditSales,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4,
                    'fill' => true,
                ],
            ],
        ]);
    }

    /**
     * Get fuel type chart data (today)
     */
    private function getFuelTypeChart()
    {
        $fuelTypes = Sale::whereDate('sale_time', today())
            ->with('fuelProduct')
            ->get()
            ->groupBy('fuel_product_id')
            ->map(function ($group) {
                return $group->sum('quantity_litres');
            });

        $labels = [];
        $data = [];
        $colors = ['#ef4444', '#f59e0b', '#10b981'];

        foreach ($fuelTypes as $fuelId => $litres) {
            $fuel = FuelProduct::find($fuelId);
            if ($fuel) {
                $labels[] = $fuel->name;
                $data[] = (float) $litres;
            }
        }

        return json_encode([
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
        ]);
    }

    /**
     * Get tank stock chart data
     */
    private function getTankStockChart()
    {
        $tanks = Tank::with('fuelProduct')->where('is_active', true)->get();

        $labels = [];
        $stockData = [];
        $capacityData = [];
        $borderColors = [];

        foreach ($tanks as $tank) {
            $labels[] = $tank->name;
            $stockData[] = (float) $tank->current_stock_litres;
            $capacityData[] = (float) $tank->capacity_litres;

            // Color based on stock level
            $percentage = ($tank->current_stock_litres / $tank->capacity_litres) * 100;
            if ($percentage >= 50) {
                $borderColors[] = '#22c55e';
            } elseif ($percentage >= 25) {
                $borderColors[] = '#f59e0b';
            } else {
                $borderColors[] = '#ef4444';
            }
        }

        return json_encode([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Current Stock',
                    'data' => $stockData,
                    'backgroundColor' => $borderColors,
                ],
                [
                    'label' => 'Capacity',
                    'data' => $capacityData,
                    'backgroundColor' => 'rgba(200, 200, 200, 0.5)',
                ],
            ],
        ]);
    }

    /**
     * Get monthly revenue chart data (last 6 months)
     */
    private function getMonthlyRevenueChart()
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $revenue = Sale::whereMonth('sale_time', $date->month)
                ->whereYear('sale_time', $date->year)
                ->sum('total_amount');

            $data[] = (float) $revenue;
        }

        return json_encode([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Monthly Revenue',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 2,
                ],
            ],
        ]);
    }
}
