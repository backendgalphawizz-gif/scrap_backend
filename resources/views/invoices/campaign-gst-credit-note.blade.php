<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Note - {{ $credit_note_no }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #222; margin: 24px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        h2 { font-size: 14px; margin: 20px 0 8px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .meta { width: 100%; margin-bottom: 20px; }
        .meta td { border: none; padding: 4px 8px 4px 0; vertical-align: top; }
        .text-right { text-align: right; }
        .totals td { border: none; padding: 4px 0; }
        .totals .label { text-align: right; padding-right: 12px; width: 75%; }
        .totals .value { text-align: right; font-weight: bold; width: 25%; }
        .header-grid { display: flex; justify-content: space-between; gap: 24px; flex-wrap: wrap; }
        .party { flex: 1; min-width: 260px; }
        .muted { color: #666; font-size: 12px; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:16px;">
        <button onclick="window.print()" style="padding:8px 16px;cursor:pointer;">Print / Save as PDF</button>
    </div>

    <h1>Credit Note</h1>
    <p class="muted">GST reversal for unused campaign budget</p>

    <table class="meta">
        <tr>
            <td><strong>Credit Note No.</strong><br>{{ $credit_note_no }}</td>
            <td><strong>Credit Note Date</strong><br>{{ $credit_note_date }}</td>
            <td><strong>Original Invoice No.</strong><br>{{ $original_invoice_no }}</td>
            <td><strong>Campaign ID</strong><br>#{{ $campaign->id }}</td>
        </tr>
        <tr>
            <td colspan="4"><strong>Reason</strong><br>{{ $reason }}</td>
        </tr>
    </table>

    <div class="header-grid">
        <div class="party">
            <h2>From (Supplier)</h2>
            <strong>{{ $company['name'] }}</strong><br>
            @if($company['address']){{ $company['address'] }}<br>@endif
            @if($company['phone'])Phone: {{ $company['phone'] }}<br>@endif
            @if($company['email'])Email: {{ $company['email'] }}<br>@endif
            @if($company['gst_number'])GSTIN: {{ $company['gst_number'] }}@endif
        </div>
        <div class="party">
            <h2>Bill To (Brand)</h2>
            <strong>{{ $brand['name'] }}</strong>
            @if($brand['username']) ({{ $brand['username'] }})@endif<br>
            @if($brand['address']){{ $brand['address'] }}<br>@endif
            <strong>GSTIN: {{ $brand['gst_number'] ?: 'N/A' }}</strong>
        </div>
    </div>

    <h2>Reversal Details</h2>
    <table>
        <tr>
            <th>Description</th>
            <th class="text-right" style="width:140px;">Taxable Reversal (₹)</th>
            <th class="text-right" style="width:140px;">GST Reversal (₹)</th>
        </tr>
        <tr>
            <td>
                Unused campaign budget — {{ $campaign->title ?: 'Campaign #' . $campaign->id }}
            </td>
            <td class="text-right">{{ number_format($amounts['taxable_reversal'], 2) }}</td>
            <td class="text-right">{{ number_format($amounts['gst_reversal'], 2) }}</td>
        </tr>
    </table>

    <table class="totals" style="max-width:420px;margin-left:auto;margin-top:16px;">
        <tr>
            <td class="label">Taxable Reversal Amount</td>
            <td class="value">₹{{ number_format($amounts['taxable_reversal'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">CGST Reversal @ {{ number_format($amounts['cgst_rate'], 2) }}%</td>
            <td class="value">₹{{ number_format($amounts['cgst_reversal'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">SGST Reversal @ {{ number_format($amounts['sgst_rate'], 2) }}%</td>
            <td class="value">₹{{ number_format($amounts['sgst_reversal'], 2) }}</td>
        </tr>
        <tr>
            <td class="label"><strong>Total GST Reversal</strong></td>
            <td class="value"><strong>₹{{ number_format($amounts['gst_reversal'], 2) }}</strong></td>
        </tr>
        <tr>
            <td class="label"><strong>Total Credit (incl. GST)</strong></td>
            <td class="value"><strong>₹{{ number_format($amounts['total_reversal'], 2) }}</strong></td>
        </tr>
    </table>

    <p class="muted" style="margin-top:32px;">
        This is a computer-generated credit note against invoice {{ $original_invoice_no }} for campaign ID #{{ $campaign->id }}.
    </p>
</body>
</html>
