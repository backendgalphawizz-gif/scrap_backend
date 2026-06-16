<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Note - {{ $credit_note_no }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #222; margin: 24px; }
        h1 { font-size: 18px; margin: 0 0 2px; }
        h2 { font-size: 13px; margin: 16px 0 6px; color: #333; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #aaa; padding: 7px 9px; text-align: left; vertical-align: top; }
        th { background: #e8e8e8; font-weight: bold; }
        .header-box { border: 2px solid #333; text-align: center; padding: 8px; margin-bottom: 14px; }
        .header-box h1 { font-size: 16px; font-weight: bold; letter-spacing: 1px; }
        .header-box p { margin: 2px 0; font-size: 12px; }
        .meta td { border: 1px solid #aaa; padding: 6px 9px; vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .header-grid { display: flex; gap: 0; margin-top: 10px; }
        .party { flex: 1; border: 1px solid #aaa; padding: 8px 10px; }
        .party:first-child { border-right: none; }
        .party h2 { margin: 0 0 6px; }
        .muted { color: #555; font-size: 11px; }
        .total-row td { font-weight: bold; background: #f0f0f0; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:14px;">
        <button onclick="window.print()" style="padding:7px 16px;cursor:pointer;">Print / Save as PDF</button>
    </div>

    {{-- Company header --}}
    <div class="header-box">
        <h1>{{ strtoupper($company['name']) }}</h1>
        @if($company['address'])<p>{{ $company['address'] }}</p>@endif
    </div>

    <h2>CREDIT NOTE</h2>

    {{-- Credit note meta --}}
    <table class="meta">
        <tr>
            <th>Credit Note No.</th>
            <th>Credit Note Date</th>
            <th>Original Invoice No.</th>
            <th>Campaign ID</th>
        </tr>
        <tr>
            <td>{{ $credit_note_no }}</td>
            <td>{{ $credit_note_date }}</td>
            <td>{{ $original_invoice_no }}</td>
            <td>#{{ $campaign->id }}</td>
        </tr>
        <tr>
            <td colspan="4"><strong>Reason:</strong> {{ $reason }}</td>
        </tr>
    </table>

    {{-- Parties --}}
    <div class="header-grid">
        <div class="party">
            <h2>Credit To</h2>
            <strong>{{ $brand['name'] }}</strong>
            @if($brand['username']) ({{ $brand['username'] }})@endif<br>
            @if($brand['address'])Address: {{ $brand['address'] }}<br>@endif
            @if($brand['city'] || $brand['state']){{ trim(($brand['city'] ?? '') . ', ' . ($brand['state'] ?? ''), ', ') }}<br>@endif
            @if($brand['phone'])Contact No. {{ $brand['phone'] }}<br>@endif
            @if($brand['email'])Email ID: {{ $brand['email'] }}<br>@endif
        </div>
        <div class="party">
            <h2>From</h2>
            <strong>{{ $company['name'] }}</strong><br>
            @if($company['address'])Address: {{ $company['address'] }}<br>@endif
            @if($company['phone'])Contact No. {{ $company['phone'] }}<br>@endif
            @if($company['email'])Email ID: {{ $company['email'] }}<br>@endif
        </div>
    </div>

    {{-- Reversal details --}}
    <table style="margin-top:14px;">
        <tr>
            <th>Description</th>
            <th class="text-center" style="width:160px;">Per Post Amount X Unutilised</th>
            <th class="text-right" style="width:110px;">Amount (₹)</th>
        </tr>

        {{-- Main service row --}}
        <tr>
            <td>
                Partial cancellation / unutilized campaign inventory.<br>
                {{ $campaign->title ?: 'Campaign #' . $campaign->id }}<br>
                <span class="muted">Campaign ID: #{{ $campaign->id }}</span>
                @if($posts['purchased'] > 0)
                    <br><span class="muted">Total Post: {{ $posts['purchased'] }}</span>
                @endif
            </td>
            <td class="text-center">
                @if($posts['per_post_amount'] > 0 && $posts['unutilized'] > 0)
                    {{ number_format($posts['per_post_amount'], 0) }} x {{ $posts['unutilized'] }}
                @else
                    &mdash;
                @endif
            </td>
            <td class="text-right">{{ number_format($amounts['gross_reversal'], 2) }}</td>
        </tr>

        {{-- Discount row --}}
        @if($amounts['discount_reversal'] > 0)
        <tr>
            <td>
                Discount
                @if($amounts['discount_pct'] > 0)
                    {{ number_format($amounts['discount_pct'], 0) }}%
                @endif
            </td>
            <td class="text-center">&mdash;</td>
            <td class="text-right">{{ number_format($amounts['discount_reversal'], 2) }}</td>
        </tr>
        @endif

        {{-- Total row --}}
        <tr class="total-row">
            <td colspan="2"><strong>Total Credit Note Amount:</strong></td>
            <td class="text-right"><strong>{{ number_format($amounts['taxable_reversal'], 2) }}</strong></td>
        </tr>
    </table>

    <p class="muted" style="margin-top:20px;">
        Adjustment: The above amount shall be credited to the Brand Wallet and may be utilized for future campaigns or refunded in accordance with Rexarix policies.
    </p>

    <p class="muted" style="margin-top:8px;">
        This is a computer-generated credit note against invoice {{ $original_invoice_no }} for campaign ID #{{ $campaign->id }}.
    </p>
</body>
</html>
