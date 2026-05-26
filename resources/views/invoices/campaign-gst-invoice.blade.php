<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice - {{ $invoice_number }}</title>
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

    <h1>Tax Invoice</h1>
    <p class="muted">Campaign marketing services</p>

    <table class="meta">
        <tr>
            <td><strong>Invoice No.</strong><br>{{ $invoice_number }}</td>
            <td><strong>Invoice Date</strong><br>{{ $invoice_date }}</td>
            <td><strong>Campaign ID</strong><br>#{{ $campaign->id }}</td>
            <td><strong>Campaign Code</strong><br>{{ $campaign->unique_code ?: 'N/A' }}</td>
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
            @if($brand['city'] || $brand['state']){{ trim($brand['city'] . ', ' . $brand['state'], ', ') }}<br>@endif
            @if($brand['phone'])Phone: {{ $brand['phone'] }}<br>@endif
            @if($brand['email'])Email: {{ $brand['email'] }}<br>@endif
            <strong>GSTIN: {{ $brand['gst_number'] ?: 'N/A' }}</strong>
            @if($brand['gst_status'])<br><span class="muted">GST Status: {{ $brand['gst_status'] }}</span>@endif
        </div>
    </div>

    <h2>Campaign Details</h2>
    <table>
        <tr>
            <th>Description</th>
            <th class="text-right" style="width:120px;">HSN/SAC</th>
            <th class="text-right" style="width:140px;">Taxable (₹)</th>
        </tr>
        <tr>
            <td>
                Campaign promotion services — {{ $campaign->title ?: 'Campaign #' . $campaign->id }}<br>
                <span class="muted">Period: {{ $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') : 'N/A' }} to {{ $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') : 'N/A' }}</span>
            </td>
            <td class="text-right">998314</td>
            <td class="text-right">{{ number_format($amounts['taxable'], 2) }}</td>
        </tr>
    </table>

    <table class="totals" style="max-width:420px;margin-left:auto;margin-top:16px;">
        <tr>
            <td class="label">Taxable Amount</td>
            <td class="value">₹{{ number_format($amounts['taxable'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">CGST @ {{ number_format($amounts['cgst_rate'], 2) }}%</td>
            <td class="value">₹{{ number_format($amounts['cgst_amount'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">SGST @ {{ number_format($amounts['sgst_rate'], 2) }}%</td>
            <td class="value">₹{{ number_format($amounts['sgst_amount'], 2) }}</td>
        </tr>
        <tr>
            <td class="label"><strong>Total (incl. GST)</strong></td>
            <td class="value"><strong>₹{{ number_format($amounts['total'], 2) }}</strong></td>
        </tr>
    </table>

    <p class="muted" style="margin-top:32px;">
        This is a computer-generated tax invoice for campaign ID #{{ $campaign->id }}.
        Total payable: ₹{{ number_format($amounts['total'], 2) }} (GST @ {{ number_format($amounts['gst_percentage'], 2) }}%).
    </p>
</body>
</html>
