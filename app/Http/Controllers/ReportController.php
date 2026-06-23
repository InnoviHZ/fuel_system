<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Delivery;
use App\Models\Reconciliation;
use App\Models\CreditCustomer;
use App\Models\Tank;
use App\Models\FuelProduct;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    /**
     * Show reports home page
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Sales report
     */
    public function salesReport(Request $request)
    {
        $query = Sale::with(['attendant', 'pump.tank.fuelProduct', 'fuelProduct', 'shift']);

        // Date range filter
        if ($request->date_from) {
            $query->whereDate('sale_time', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('sale_time', '<=', $request->date_to);
        }

        // Fuel type filter
        if ($request->fuel_product_id) {
            $query->where('fuel_product_id', $request->fuel_product_id);
        }

        // Payment method filter
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Attendant filter
        if ($request->attendant_id) {
            $query->where('attendant_id', $request->attendant_id);
        }

        // Shift type filter
        if ($request->shift_type) {
            $query->whereHas('shift', function ($q) use ($request) {
                $q->where('shift_type', $request->shift_type);
            });
        }

        $sales = $query->orderBy('sale_time', 'desc')->paginate(50);

        // Summary stats - use cloned queries to avoid filter stacking
        $baseQuery = Sale::with(['attendant', 'pump.tank.fuelProduct', 'fuelProduct', 'shift']);

        // Apply same filters to base query
        if ($request->date_from) {
            $baseQuery->whereDate('sale_time', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $baseQuery->whereDate('sale_time', '<=', $request->date_to);
        }
        if ($request->fuel_product_id) {
            $baseQuery->where('fuel_product_id', $request->fuel_product_id);
        }
        if ($request->attendant_id) {
            $baseQuery->where('attendant_id', $request->attendant_id);
        }
        if ($request->shift_type) {
            $baseQuery->whereHas('shift', function ($q) use ($request) {
                $q->where('shift_type', $request->shift_type);
            });
        }

        $summary = [
            'total_sales' => $baseQuery->count(),
            'total_litres' => $baseQuery->sum('quantity_litres'),
            'total_amount' => $baseQuery->sum('total_amount'),
            'cash_sales' => $baseQuery->clone()->where('payment_method', '!=', 'credit')->sum('total_amount'),
            'pos_sales' => $baseQuery->clone()->where('payment_method', 'pos')->sum('total_amount'),
            'bank_sales' => $baseQuery->clone()->where('payment_method', 'bank_transfer')->sum('total_amount'),
            'credit_sales' => $baseQuery->clone()->where('payment_method', 'credit')->sum('total_amount'),
        ];

        $fuels = FuelProduct::where('is_active', true)->get();
        $attendants = \App\Models\User::where('role', 'attendant')->where('is_active', true)->get();

        return view('reports.sales', compact('sales', 'summary', 'fuels', 'attendants', 'request'));
    }

    /**
     * Inventory report
     */
    public function inventoryReport(Request $request)
    {
        $tanks = Tank::with('fuelProduct')->where('is_active', true)->get();

        $inventory = [];
        $totalStock = 0;
        $deliveriesThisMonth = 0;
        $salesThisMonth = 0;

        foreach ($tanks as $tank) {
            $deliveries = Delivery::where('tank_id', $tank->id)
                ->whereMonth('delivery_date', now()->month)
                ->whereYear('delivery_date', now()->year)
                ->sum('quantity_litres');

            $sales = Sale::where('fuel_product_id', $tank->fuel_product_id)
                ->whereDate('sale_time', '>=', now()->startOfMonth())
                ->whereDate('sale_time', '<=', now()->endOfMonth())
                ->sum('quantity_litres');

            $totalStock += $tank->current_stock_litres;
            $deliveriesThisMonth += $deliveries;
            $salesThisMonth += $sales;

            $inventory[] = [
                'tank' => $tank,
                'deliveries_this_month' => $deliveries,
                'sales_this_month' => $sales,
            ];
        }

        return view('reports.inventory', compact('inventory', 'totalStock', 'deliveriesThisMonth', 'salesThisMonth'));
    }

    /**
     * Reconciliation report
     */
    public function reconciliationReport(Request $request)
    {
        $query = Reconciliation::with(['shift', 'reconciledBy', 'reviewedBy']);

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $reconciliations = $query->orderBy('created_at', 'desc')->paginate(50);

        // Summary - use fresh query to avoid pagination issues
        $summaryQuery = Reconciliation::query();
        if ($request->date_from) {
            $summaryQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $summaryQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->status) {
            $summaryQuery->where('status', $request->status);
        }

        $summary = [
            'total_shifts' => $summaryQuery->count(),
            'total_expected' => $summaryQuery->sum('expected_cash'),
            'total_collected' => $summaryQuery->sum('total_collected'),
        ];

        return view('reports.reconciliation', compact('reconciliations', 'summary', 'request'));
    }

    /**
     * Delivery report
     */
    public function deliveryReport(Request $request)
    {
        $query = Delivery::with(['tank', 'fuelProduct', 'deliveredBy']);

        if ($request->date_from) {
            $query->whereDate('delivery_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('delivery_date', '<=', $request->date_to);
        }

        if ($request->tank_id) {
            $query->where('tank_id', $request->tank_id);
        }

        $deliveries = $query->orderBy('delivery_date', 'desc')->paginate(50);

        // Summary - use fresh query
        $summaryQuery = Delivery::query();
        if ($request->date_from) {
            $summaryQuery->whereDate('delivery_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $summaryQuery->whereDate('delivery_date', '<=', $request->date_to);
        }
        if ($request->tank_id) {
            $summaryQuery->where('tank_id', $request->tank_id);
        }

        $total_litres = $summaryQuery->sum('quantity_litres');
        $total_cost = $summaryQuery->sum('total_cost');

        $summary = [
            'delivery_count' => $summaryQuery->count(),
            'total_litres' => $total_litres,
            'total_cost' => $total_cost,
            'avg_cost_per_litre' => $total_litres > 0 ? $total_cost / $total_litres : 0,
        ];

        $tanks = Tank::where('is_active', true)->get();

        return view('reports.deliveries', compact('deliveries', 'summary', 'tanks', 'request'));
    }

    /**
     * Credit report
     */
    public function creditReport(Request $request)
    {
        $query = CreditCustomer::with(['sales', 'repayments'])->where('is_active', true);

        // Status filter
        if ($request->status == 'near_limit') {
            $query->whereRaw('(current_balance / credit_limit) >= 0.8')
                ->where('credit_limit', '>', 0);
        }

        $customers = $query->orderBy('current_balance', 'desc')->paginate(50);

        // Summary - fresh query for accurate counts
        $allCustomers = CreditCustomer::where('is_active', true);

        $summary = [
            'active_customers' => $allCustomers->count(),
            'total_credit_limit' => $allCustomers->sum('credit_limit'),
            'total_outstanding' => $allCustomers->sum('current_balance'),
        ];

        return view('reports.credit', compact('customers', 'summary', 'request'));
    }

    /**
     * Export report to PDF
     */
    public function export(Request $request)
    {
        $reportType = $request->report_type ?? 'sales';
        $dateFrom = $request->date_from ?? null;
        $dateTo = $request->date_to ?? null;

        $filename = "{$reportType}-report-" . now()->format('Y-m-d-His') . ".pdf";

        return match ($reportType) {
            'sales' => $this->exportSalesReport($dateFrom, $dateTo, $filename),
            'reconciliation' => $this->exportReconciliationReport($dateFrom, $dateTo, $filename),
            'deliveries' => $this->exportDeliveryReport($dateFrom, $dateTo, $filename),
            default => back()->with('error', 'Invalid report type'),
        };
    }

    /**
     * Export sales report to PDF
     */
    private function exportSalesReport($dateFrom, $dateTo, $filename)
    {
        $query = Sale::with(['attendant', 'pump.tank.fuelProduct', 'fuelProduct', 'shift']);

        if ($dateFrom) {
            $query->whereDate('sale_time', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('sale_time', '<=', $dateTo);
        }

        $sales = $query->orderBy('sale_time', 'desc')->get();

        $summary = [
            'total_sales' => $sales->count(),
            'total_litres' => $sales->sum('quantity_litres'),
            'total_amount' => $sales->sum('total_amount'),
            'cash_sales' => $sales->where('payment_method', '!=', 'credit')->sum('total_amount'),
            'pos_sales' => $sales->where('payment_method', 'pos')->sum('total_amount'),
            'bank_sales' => $sales->where('payment_method', 'bank_transfer')->sum('total_amount'),
            'credit_sales' => $sales->where('payment_method', 'credit')->sum('total_amount'),
        ];

        $pdf = PDF::loadView('reports.pdf.sales-pdf', compact('sales', 'summary', 'dateFrom', 'dateTo'));
        return $pdf->download($filename);
    }

    /**
     * Export reconciliation report to PDF
     */
    private function exportReconciliationReport($dateFrom, $dateTo, $filename)
    {
        $query = Reconciliation::with(['shift', 'reconciledBy', 'reviewedBy']);

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $reconciliations = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_shifts' => $reconciliations->count(),
            'total_expected' => $reconciliations->sum('expected_cash'),
            'total_collected' => $reconciliations->sum('total_collected'),
            'total_shortages' => $reconciliations->where('status', 'shortage')->sum('cash_difference'),
            'total_surplus' => $reconciliations->where('status', 'surplus')->sum('cash_difference'),
        ];

        $pdf = PDF::loadView('reports.pdf.reconciliation-pdf', compact('reconciliations', 'summary', 'dateFrom', 'dateTo'));
        return $pdf->download($filename);
    }

    /**
     * Export delivery report to PDF
     */
    private function exportDeliveryReport($dateFrom, $dateTo, $filename)
    {
        $query = Delivery::with(['tank', 'fuelProduct', 'deliveredBy']);

        if ($dateFrom) {
            $query->whereDate('delivery_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('delivery_date', '<=', $dateTo);
        }

        $deliveries = $query->orderBy('delivery_date', 'desc')->get();

        $summary = [
            'total_deliveries' => $deliveries->count(),
            'total_litres' => $deliveries->sum('quantity_litres'),
            'total_cost' => $deliveries->sum('total_cost'),
        ];

        $pdf = PDF::loadView('reports.pdf.delivery-pdf', compact('deliveries', 'summary', 'dateFrom', 'dateTo'));
        return $pdf->download($filename);
    }
}
