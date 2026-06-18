@php
    $formatAmount = function ($value) {
        $rounded = round((float) $value, 2);
        return fmod($rounded, 1.0) === 0.0
            ? number_format($rounded, 0)
            : rtrim(rtrim(number_format($rounded, 2), '0'), '.');
    };
    $brandAddress = trim(implode(', ', array_filter([
        $brand['address'] ?? '',
        trim(($brand['city'] ?? '') . ($brand['state'] ? ', ' . $brand['state'] : ''), ', '),
    ])));
@endphp
<div class="no-print" style="margin-bottom:14px;">
    <button onclick="window.print()" style="padding:7px 16px;cursor:pointer;">Print / Save as PDF</button>
</div>

<div class="company-bar">
    {{ strtoupper($company['name']) }}
</div>

<h2 class="doc-title">GST TAX INVOICE</h2>

<table class="meta">
    <tr>
        <th>Invoice No.</th>
        <th>Invoice Date</th>
        <th>Campaign ID</th>
        <th>Campaign Code</th>
    </tr>
    <tr>
        <td>{{ $invoice_number }}</td>
        <td>{{ $invoice_date }}</td>
        <td>#{{ $campaign->id }}</td>
        <td>{{ $campaign->unique_code ?: '' }}</td>
    </tr>
</table>

<div class="header-grid">
    <div class="party">
        <h2>Bill To</h2>
        <strong>{{ $brand['name'] }}</strong><br>
        @if($brandAddress)Address : {{ $brandAddress }}<br>@endif
        Contact No. {{ $brand['phone'] ?: '' }}<br>
        Email ID {{ $brand['email'] ?: '' }}<br>
        @if($is_gst_invoice)
            GST: {{ $brand['gst_number'] ?: '' }}
        @else
            PAN: {{ $brand['pan_number'] ?: '' }}
        @endif
    </div>
    <div class="party">
        <h2>From</h2>
        <strong>{{ $company['name'] }}</strong><br>
        @if($company['address'])Address: {{ $company['address'] }}<br>@endif
        Email ID {{ $company['email'] ?: '' }}<br>
        @if($company['gst_number'])GST: {{ $company['gst_number'] }}@endif
    </div>
</div>

<table class="particulars">
    <tr>
        <th>Particulars</th>
        <th class="text-center" style="width:180px;">Per Post Amount X Total Post</th>
        <th class="text-right" style="width:110px;">Amount</th>
    </tr>
    <tr>
        <td>
            Campaign Promotion Service - {{ $campaign->title ?: 'Campaign #' . $campaign->id }}<br>
            <span class="muted">Campaign ID: #{{ $campaign->id }}</span><br>
            <span class="muted">Period: {{ $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') : 'N/A' }} to {{ $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') : 'N/A' }}</span>
        </td>
        <td class="text-center">
            @if($posts['per_post_amount'] > 0 && $posts['total_posts'] > 0)
                {{ $formatAmount($posts['per_post_amount']) }} x {{ $posts['total_posts'] }}
            @else
                &mdash;
            @endif
        </td>
        <td class="text-right">{{ $formatAmount($amounts['taxable']) }}</td>
    </tr>

    @if(($amounts['discount_amount'] ?? 0) > 0)
    <tr>
        <td><strong>Discount</strong></td>
        <td class="text-center">
            @if(($amounts['discount_pct'] ?? 0) > 0)
                {{ $formatAmount($amounts['discount_pct']) }}%
            @else
                &mdash;
            @endif
        </td>
        <td class="text-right">{{ $formatAmount($amounts['discount_amount']) }}</td>
    </tr>
    @endif

    <tr>
        <td><strong>Net Amount</strong></td>
        <td class="text-center">&mdash;</td>
        <td class="text-right"><strong>{{ $formatAmount($amounts['net_taxable']) }}</strong></td>
    </tr>

    <tr>
        <td rowspan="3"><strong>GST</strong></td>
        <td>CGST</td>
        <td class="text-right">
            {{ $formatAmount($amounts['cgst_rate']) }}%
            &nbsp; {{ $formatAmount($amounts['cgst_amount']) }}
        </td>
    </tr>
    <tr class="gst-sub">
        <td>SGST</td>
        <td class="text-right">
            {{ $formatAmount($amounts['sgst_rate']) }}%
            &nbsp; {{ $formatAmount($amounts['sgst_amount']) }}
        </td>
    </tr>
    <tr class="gst-sub">
        <td>IGST</td>
        <td class="text-right">
            {{ $formatAmount($amounts['igst_rate']) }}%
            &nbsp; {{ $formatAmount($amounts['igst_amount']) }}
        </td>
    </tr>

    <tr class="total-row">
        <td colspan="2"><strong>Total Invoice Amount:</strong></td>
        <td class="text-right"><strong>{{ $formatAmount($amounts['total']) }}</strong></td>
    </tr>
</table>

<p class="declaration">
    Declaration: This invoice is issued towards advertising and campaign facilitation services provided by Rexarix Private Limited.
</p>
