<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delivery Report</title>
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
        <h1>Delivery Report</h1>
        <p>Generated on {{ \Carbon\Carbon::now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <span>Total Deliveries</span>
            <strong>{{ $summary['delivery_count'] }}</strong>
        </div>
        <div class="summary-box">
            <span>Total Litres</span>
            <strong>{{ number_format($summary['total_litres'], 2) }} L</strong>
        </div>
        <div class="summary-box">
            <span>Total Cost</span>
            <strong>${{ number_format($summary['total_cost'], 2) }}</strong>
        </div>
        <div class="summary-box">
            <span>Avg Cost/Litre</span>
            <strong>${{ number_format($summary['avg_cost_per_litre'], 3) }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Tank</th>
                <th>Fuel Type</th>
                <th>Supplier</th>
                <th>Quantity (L)</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Delivered By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $delivery)
                <tr>
                    <td>{{ $delivery->delivery_date->format('M d, Y') }}</td>
                    <td>{{ $delivery->tank->name }}</td>
                    <td>{{ $delivery->fuelProduct->name }}</td>
                    <td>{{ $delivery->supplier_name }}</td>
                    <td style=\"text-align: right;\">{{ number_format($delivery->quantity_litres, 2) }}</td>\n                    <td style=\"text-align: right;\">${{ number_format($delivery->unit_cost, 3) }}</td>\n                    <td style=\"text-align: right;\">${{ number_format($delivery->total_cost, 2) }}</td>\n                    <td>{{ $delivery->deliveredByUser->name }}</td>\n                </tr>\n            @endforeach\n        </tbody>\n        <tfoot>\n            <tr>\n                <td colspan=\"4\" style=\"text-align: right;\">TOTALS</td>\n                <td style=\"text-align: right;\">{{ number_format($deliveries->sum('quantity_litres'), 2) }}</td>\n                <td style=\"text-align: right;\"></td>\n                <td style=\"text-align: right;\">${{ number_format($deliveries->sum('total_cost'), 2) }}</td>\n                <td></td>\n            </tr>\n        </tfoot>\n    </table>\n\n    <div class=\"footer\">\n        <p>This is an automatically generated report. For questions, contact the station administrator.</p>\n    </div>\n</body>\n</html>