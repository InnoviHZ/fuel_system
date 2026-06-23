<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reconciliation Report</title>
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
        <h1>Reconciliation Report</h1>
        <p>Generated on {{ \Carbon\Carbon::now()->format('M d, Y H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <span>Total Shifts</span>
            <strong>{{ $summary['total_shifts'] }}</strong>
        </div>
        <div class="summary-box">
            <span>Total Expected</span>
            <strong>${{ number_format($summary['total_expected'], 2) }}</strong>
        </div>
        <div class="summary-box">
            <span>Total Collected</span>
            <strong>${{ number_format($summary['total_collected'], 2) }}</strong>
        </div>
        <div class="summary-box">
            <span>Variance</span>
            @php
                $variance = $summary['total_collected'] - $summary['total_expected'];
            @endphp
            <strong style="color: {{ $variance > 0 ? '#10b981' : ($variance < 0 ? '#ef4444' : '#0f172a') }};">
                @if($variance > 0)
                    +${{ number_format($variance, 2) }}\n                @elseif($variance < 0)\n                    -${{ number_format(abs($variance), 2) }}\n                @else\n                    $0.00\n                @endif\n            </strong>\n        </div>\n    </div>\n\n    <table>\n        <thead>\n            <tr>\n                <th>Date</th>\n                <th>Shift</th>\n                <th>Expected</th>\n                <th>Collected</th>\n                <th>Variance</th>\n                <th>Status</th>\n                <th>Reconciled By</th>\n            </tr>\n        </thead>\n        <tbody>\n            @foreach($reconciliations as $recon)\n                <tr>\n                    <td>{{ $recon->shift->shift_date->format('M d, Y') }}</td>\n                    <td>{{ $recon->shift->shift_type }}</td>\n                    <td style=\"text-align: right;\">${{ number_format($recon->expected_amount, 2) }}</td>\n                    <td style=\"text-align: right;\">${{ number_format($recon->amount_collected, 2) }}</td>\n                    <td style=\"text-align: right;\">\n                        @php\n                            $reconVariance = $recon->amount_collected - $recon->expected_amount;\n                        @endphp\n                        @if($reconVariance > 0)\n                            +${{ number_format($reconVariance, 2) }}\n                        @elseif($reconVariance < 0)\n                            -${{ number_format(abs($reconVariance), 2) }}\n                        @else\n                            Balanced\n                        @endif\n                    </td>\n                    <td>{{ ucfirst($recon->status) }}</td>\n                    <td>{{ $recon->reconciledByUser->name }}</td>\n                </tr>\n            @endforeach\n        </tbody>\n    </table>\n\n    <div class=\"footer\">\n        <p>This is an automatically generated report. For questions, contact the station administrator.</p>\n    </div>\n</body>\n</html>