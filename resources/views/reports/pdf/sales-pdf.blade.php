<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0f172a;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .summary-box {
            flex: 1;
            min-width: 200px;
            border: 1px solid #e0e0e0;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .summary-box strong {
            display: block;
            font-size: 18px;
            color: #0f172a;
            margin-top: 5px;
        }
        .summary-box span {
            color: #999;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #0f172a;
            color: white;
        }
        th {
            padding: 10px;
            text-align: left;
            font-size: 12px;
            font-weight: bold;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 11px;
        }
        tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        tfoot {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 10px;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p>Generated on {{ \Carbon\Carbon::now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <span>Total Sales</span>
            <strong>{{ $summary['total_sales_count'] }} transactions</strong>
        </div>
        <div class="summary-box">
            <span>Total Litres</span>
            <strong>{{ number_format($summary['total_litres'], 2) }} L</strong>
        </div>
        <div class="summary-box">
            <span>Total Amount</span>
            <strong>${{ number_format($summary['total_sales_count'] > 0 ? $sales->sum('total_amount') : 0, 2) }}</strong>
        </div>
        <div class="summary-box">
            <span>Cash Sales</span>
            <strong>${{ number_format($summary['cash_sales'], 2) }}</strong>
        </div>
        <div class="summary-box">
            <span>Credit Sales</span>\n            <strong>${{ number_format($summary['credit_sales'], 2) }}</strong>\n        </div>\n    </div>\n\n    <table>\n        <thead>\n            <tr>\n                <th>Date/Time</th>\n                <th>Attendant</th>\n                <th>Pump</th>\n                <th>Fuel Type</th>\n                <th>Quantity (L)</th>\n                <th>Amount</th>\n                <th>Payment</th>\n            </tr>\n        </thead>\n        <tbody>\n            @foreach($sales as $sale)\n                <tr>\n                    <td>{{ $sale->sale_time->format('M d H:i') }}</td>\n                    <td>{{ $sale->attendant->name }}</td>\n                    <td>{{ $sale->pump->name }}</td>\n                    <td>{{ $sale->fuelProduct->name }}</td>\n                    <td style=\"text-align: right;\">{{ number_format($sale->quantity_litres, 2) }}</td>\n                    <td style=\"text-align: right;\">${{ number_format($sale->total_amount, 2) }}</td>\n                    <td>{{ ucfirst($sale->payment_method) }}</td>\n                </tr>\n            @endforeach\n        </tbody>\n        <tfoot>\n            <tr>\n                <td colspan=\"4\" style=\"text-align: right;\">TOTALS</td>\n                <td style=\"text-align: right;\">{{ number_format($sales->sum('quantity_litres'), 2) }}</td>\n                <td style=\"text-align: right;\">${{ number_format($sales->sum('total_amount'), 2) }}</td>\n                <td></td>\n            </tr>\n        </tfoot>\n    </table>\n\n    <div class=\"footer\">\n        <p>This is an automatically generated report. For questions, contact the station administrator.</p>\n    </div>\n</body>\n</html>