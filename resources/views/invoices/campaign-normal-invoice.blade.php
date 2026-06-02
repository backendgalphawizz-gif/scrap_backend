<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice_number }}</title>
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

    <h1>Invoice</h1>
    <p class="muted">Campaign marketing services (non-GST invoice)</p>

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
            <h2>From</h2>
            <strong>{{ $company['name'] }}</strong><br>
            @if($company['address']){{ $company['address'] }}<br>@endif
            @if($company['phone'])Phone: {{ $company['phone'] }}<br>@endif
            @if($company['email'])Email: {{ $company['email'] }}<br>@endif
        </div>
        <div class="party">
            <h2>Bill To (Brand)</h2>
            <strong>{{ $brand['name'] }}</strong>
            @if($brand['username']) ({{ $brand['username'] }})@endif<br>
            @if($brand['address']){{ $brand['address'] }}<br>@endif
            @if($brand['city'] || $brand['state']){{ trim($brand['city'] . ', ' . $brand['state'], ', ') }}<br>@endif
            @if($brand['phone'])Phone: {{ $brand['phone'] }}<br>@endif
            @if($brand['email'])Email: {{ $brand['email'] }}<br>@endif
        </div>
    </div>

    <h2>Campaign Details</h2>
    <table>
        <tr>
            <th>Description</th>
            <th class="text-right" style="width:160px;">Amount (₹)</th>
        </tr>
        <tr>
            <td>
                Campaign promotion services — {{ $campaign->title ?: 'Campaign #' . $campaign->id }}<br>
                <span class="muted">Period: {{ $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') : 'N/A' }} to {{ $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') : 'N/A' }}</span>
            </td>
            <td class="text-right">{{ number_format($amounts['total'], 2) }}</td>
        </tr>
    </table>

    <table class="totals" style="max-width:420px;margin-left:auto;margin-top:16px;">
        <tr>
            <td class="label">Total Amount (Base)</td>
            <td class="value">₹{{ number_format($amounts['taxable'], 2) }}</td>
        </tr>
        @if(($amounts['discount_amount'] ?? 0) > 0)
        <tr>
            <td class="label" style="color:#c0392b;">Less: Voucher Discount
                @if($campaign->discount_code)<span class="muted"> ({{ $campaign->discount_code }})</span>@endif
            </td>
            <td class="value" style="color:#c0392b;">- ₹{{ number_format($amounts['discount_amount'], 2) }}</td>
        </tr>
        @endif
        <tr>
            <td class="label"><strong>Total Amount Paid</strong></td>
            <td class="value"><strong>₹{{ number_format($amounts['total'], 2) }}</strong></td>
        </tr>
    </table>

    <p class="muted" style="margin-top:32px;">
        This is a computer-generated invoice for campaign ID #{{ $campaign->id }}.
        GST tax breakup is not included on this invoice.
    </p>
</body>
</html>
