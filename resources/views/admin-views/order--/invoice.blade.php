<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{\App\CPU\translate('invoice')}}</title>
    <meta http-equiv="Content-Type" content="text/html;" />
    <meta charset="UTF-8">
    <style media="all">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: "Lexend Deca", sans-serif;
            color: #333542;
        }

        @import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap');

        * {
            font-family: "Lexend Deca", sans-serif;
        }

        /* IE 6 */
        * html .footer {
            position: absolute;
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe=document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
        }

        body {
            font-size: .75rem;
        }

        img {
            max-width: 100%;
        }

        .customers {
            font-family: "Lexend Deca", sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table {
            width: 100%;
        }

        table thead th {
            padding: 8px;
            font-size: 10px;
            text-align: left;
        }

        table tbody th,
        table tbody td {
            padding: 3px;
            font-size: 10px;
        }

        table.fz-12 thead th {
            font-size: 12px;
        }

        table.fz-12 tbody th,
        table.fz-12 tbody td {
            font-size: 12px;
        }

        table.customers thead th {
            background-color: #fff;
            color: #000;
        }

        table.customers tbody th,
        table.customers tbody td {
            background-color: #FAFCFF;
        }

        table.calc-table th {
            text-align: left;
        }

        table.calc-table td {
            text-align: right;
        }

        table.calc-table td.text-left {
            text-align: left;
        }

        .table-total {
            font-family: "Lexend Deca", sans-serif;
        }


        .text-left {
            text-align: left !important;
        }

        .pb-2 {
            padding-bottom: 8px !important;
        }

        .pb-3 {
            padding-bottom: 16px !important;
        }

        .thstyle {
            border: 1px solid #000;
        }

        .text-right {
            text-align: right;
        }

        table th.text-right {
            text-align: right !important;
        }

        .content-position {
            padding: 15px 40px;
        }

        .content-position-y {
            padding: 0px 10px;
        }

        .text-white {
            color: white !important;
        }

        .bs-0 {
            border-spacing: 0;
        }

        .text-center {
            text-align: center;
        }

        .mb-1 {
            margin-bottom: 4px !important;
        }

        .mb-2 {
            margin-bottom: 8px !important;
        }

        .mb-4 {
            margin-bottom: 24px !important;
        }

        .mb-30 {
            margin-bottom: 30px !important;
        }

        .px-10 {
            padding-left: 10px;
            padding-right: 10px;
        }

        .fz-14 {
            font-size: 14px;
        }

        .fz-12 {
            font-size: 12px;
        }

        .fz-10 {
            font-size: 10px;
        }

        .font-normal {
            font-weight: 400;
        }

        .border-dashed-top {
            border-top: 1px dashed #ddd;
        }

        .font-weight-bold {
            font-weight: 700;
        }

        .bg-light {
            background-color: #F7F7F7;
        }

        .py-30 {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .py-4 {
            padding-top: 24px;
            padding-bottom: 24px;
        }

        .d-flex {
            display: flex;
        }

        .gap-2 {
            gap: 8px;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .align-items-center {
            align-items: center;
        }

        .justify-content-center {
            justify-content: center;
        }

        a {
            /* color: rgba(0, 128, 245, 1); */
        }

        .p-1 {
            padding: 4px !important;
        }

        .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .paymentdiv {
            display: none;
        }

        .hdntable {
            margin-top: 5px;
        }

        .taxdiv {
            margin: 0;
            font-size: 14px;
            /* font-weight: bold;  */
            display: flex;
        }

        .recipentdiv {
            margin: 0;
            font-size: 14px;
            /* font-weight: bold; */
            /* display: flex; */
        }

        .headdiv {
            text-decoration: underline;
        }

        .footerdiv {
            display: none;
        }

        p {
            font-size: 10px;
        }

        .tablecontent {
            display: flex;
            align-items: center;
        }

        .logoimg {
            height: 70px;
            width: 100px;
            object-fit: contain;
            margin-top: 10px;

        }
        .vendorimg{
            height: 70px;
            width: 100px;
            object-fit: contain;
            margin-top: 10px;
        }

        .tablerowdiv {
            border: 1px solid #000;
        }

        .company-details{
            margin-left: 8px;
        }
       
    </style>
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>
    <div class="content-position-y">
        <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
            <tr>
                <td style="width: 30%; vertical-align: top; padding: 8px;">
                    <h4 class="taxdiv">
                       <strong> Tax Invoice/Bill of Supply/Cash Memo/Estimate</strong>
                    </h4>
                    <p class="recipentdiv">
                       <strong> (Original for Recipient)</strong>
                    </p>
                    <table cellspacing="0" cellpadding="8" style="border-collapse: collapse;margin-top: 10px">
                        <tr class="tablerowdiv">
                            <td class="tablerowdiv">Invoice No.</td>
                           <td class="tablerowdiv">ORD{{ $order->id }}</td>
                        </tr>
                        <tr class="tablerowdiv">
                            <td class="tablerowdiv">Dated</td>
                            <td class="tablerowdiv">{{ $order->created_at->format('d-m-Y') }}</td>
                        </tr>
                        <tr class="tablerowdiv">
                            <td class="tablerowdiv">Mode/Terms of Payment</td>
                           @php
                                $payment = strtolower($order->payment_method);
                            @endphp
                            <td class="tablerowdiv">
                                {{ in_array($payment, ['cash', 'cash_on_delivery']) ? 'Cash on Delivery' : $order->payment_method }}
                            </td>
                    </table>

                </td>
                <td style="width: 50%; vertical-align: top; padding: 8px;text-align:right">

                    <?php 
                        ($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                    ?>
                    <div>
                        @if (!empty($e_commerce_logo))
                            <img src="{{asset("storage/app/public/company/$e_commerce_logo")}}" alt="" class="logoimg">
                        @else
                            <!-- <em>N/A</em> -->
                        @endif
                    </div>
                    <div class="shop-logo">
                        @if (!empty(optional($seller->shop)->image) && $seller->shop->image !== 'def.png')
                            <img src="{{ asset('storage/app/public/shop/' . $seller->shop->image) }}" alt="" class="vendorimg">
                        @else
                            <!-- <em>N/A</em> -->
                        @endif
                    </div>
                </td>
              
            </tr>
        </table>
    </div>

     <div class="content-position-y">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 8px;">
                    <h5 class="headdiv">Sold By :</h5>
                    <br>
                    <h5>{{ optional($seller->shop)->name ?? 'N/A' }}</h5>
                    <p>{{ optional($seller->shop)->address ?? 'N/A' }}</p>
                    <p>{{ optional($seller->shop)->gst_in ?? 'N/A' }}</p>

                </td>
            </tr>
        </table>
    </div>
    <div class="content-position-y">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
                <!-- Billing Address -->
                <!-- <td style="width: 40%; vertical-align: top; padding: 8px;">
                    <h5 class="headdiv">Billing Address :</h5>
                    <br>
                    <p>
                        @if (!empty($order->billingAddress))
                            <strong>{{ $order->billingAddress['contact_person_name'] ?? 'N/A' }}</strong><br>
                            {{ $order->billingAddress['address'] ?? 'N/A' }}<br>
                            {{ $order->billingAddress['city'] ?? 'N/A' }},
                            {{ $order->billingAddress['state'] ?? 'N/A' }},
                            {{ $order->billingAddress['country'] ?? 'N/A' }},
                            {{ $order->billingAddress['zip'] ?? 'N/A' }}<br>
                            <strong>Area:</strong> {{ $order->billingAddress['area'] ?? 'N/A' }}
                        @else
                            <em>No billing address available.</em>
                        @endif
                    </p>

                </td> -->

                <!-- Shipping Address -->
                <td style="width: 40%; vertical-align: top; padding: 8px;">
                    <h5 class="headdiv">Shipping Address : (Scan For Google Maps)</h5>
                    <br>
                    <p>
                        @if (!empty($order->shippingAddress))
                            <strong>{{ $order->shippingAddress['contact_person_name'] ?? '-' }}</strong><br>
                            {{ $order->shippingAddress['address'] ?? '-' }}<br>
                            {{ $order->shippingAddress['city'] ?? '-' }},
                            {{ $order->shippingAddress['state'] ?? '-' }},
                            {{ $order->shippingAddress['country'] ?? '-' }},
                            {{ $order->shippingAddress['zip'] ?? '-' }}<br>
                            {{ $order->shippingAddress['phone'] ?? '-' }}
                        @else
                            <em>No shipping address available.</em>
                        @endif
                    </p>

                </td>

                <!-- QR Code -->
               <td style="width: 20%; text-align: right; vertical-align: top; padding: 8px;">
                    @if (!empty($qrCode))
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 100px; height: 100px;">
                    @else
                        <!-- <em>N/A</em> -->
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="content-position-y">
        <table border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; width:100%; text-align:center;">
            <thead>
                <tr>
                    <th style="width: 50px;">Sl No.</th>
                    <th>Description</th>
                    <th>GST%</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            @php
                $total = 0;
                $sub_total = 0;
                $total_tax = 0;
                $total_shipping_cost = 0;
                $total_discount_on_product = 0;
            @endphp
            <tbody>
                @foreach($order->details as $key => $details)
                    @php

                        $productData = json_decode($details->product_details, true);

                        $line_total = $details->price * $details->qty;
                        $sub_total += $line_total;
                        $total += $line_total;
                        $total_tax += $details->tax;
                        $total_shipping_cost += $details->shipping ? $details->shipping->cost : 0;
                        $total_discount_on_product += $details['discount'];
                    @endphp
                    <tr>
                        <td class="tablerowdiv">{{ $key+1 }}</td>
                        <td style="text-align:left; white-space: nowrap; padding: 5px; vertical-align: top;">
                            <div style="display:flex; align-items:flex-start; gap:8px;">
                            
                                @if(!empty($productData['thumbnail']))
                                    <img src="{{ asset('storage/app/public/product/thumbnail/'.$productData['thumbnail']) }}"
                                        alt=""
                                        width="30"
                                        style="height:auto;">
                                @endif

                            
                                <span>
                                    {{ $productData['name'] ?? '' }}
                                    @if($details->variant)
                                        <br><strong>Variant:</strong> {{ $details->variant }}
                                    @endif
                                    @if(isset($productData['unit_price']))
                                        <br><strong>MRP:</strong> 
                                        {{ \App\CPU\BackEndHelper::set_symbol(
                                            \App\CPU\BackEndHelper::usd_to_currency($productData['unit_price'])
                                        ) }}
                                    @endif
                                    @if(isset($productData['hsn_code']))
                                        <br><strong>HSN:</strong> {{ $productData['hsn_code'] }}
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="tablerowdiv">{{ $productData['tax'] ?? '-' }}%</td>
                        <td class="tablerowdiv">{{ $details->qty }}</td>
                        <td class="tablerowdiv">{{ $productData['unit'] ?? '-' }}</td>
                        <td class="tablerowdiv">
                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($details->price)) }}
                        </td>
                        <td class="tablerowdiv">
                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($line_total)) }}
                        </td>
                    </tr>
                @endforeach

            
                @if($total_tax > 0)
                    <tr>
                        <td colspan="6" style="text-align:right;" class="tablerowdiv"><strong>CGST</strong></td>
                        <td class="tablerowdiv">
                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax/2)) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="text-align:right;" class="tablerowdiv"><strong>SGST</strong></td>
                        <td class="tablerowdiv">
                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax/2)) }}
                        </td>
                    </tr>
                @endif

            
                <tr>
                    <td colspan="6" style="text-align:right;" class="tablerowdiv"><strong>TOTAL</strong></td>
                    <td class="tablerowdiv"><strong>
                        {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total)) }}
                    </strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php
    if ($order['extra_discount_type'] == 'percent') {
        $ext_discount = ($sub_total / 100) * $order['extra_discount'];
    } else {
        $ext_discount = $order['extra_discount'];
    }
    ?>
    @php($shipping=$order['shipping_cost'])
    <div class="content-position-y paymentdiv">
        <table class="fz-12">
            <tr>
                <th class="text-left">
                    <h4 class="fz-12 mb-1">{{\App\CPU\translate('payment_details')}}</h4>
                    <p class="fz-12 font-normal">
                        {{$order->payment_status}}
                        , {{date('y-m-d',strtotime($order['created_at']))}}
                    </p>

                    @if ($order->delivery_type !=null)
                        <h4 class="fz-12 mb-1">{{\App\CPU\translate('delivery_info')}} </h4>
                        @if ($order->delivery_type == 'self_delivery')
                        <p class="fz-12 font-normal">
                            <span>
                                {{\App\CPU\translate('self_delivery')}}
                            </span>
                            <br>
                            <span>
                                {{\App\CPU\translate('delivery_man_name')}} : {{$order->delivery_man['f_name'].' '.$order->delivery_man['l_name']}}
                            </span>
                            <br>
                            <span>
                                {{\App\CPU\translate('delivery_man_phone')}} : {{$order->delivery_man['phone']}}
                            </span>
                        </p>
                        @else
                        <p>
                            <span>
                                {{$order->delivery_service_name}}
                            </span>
                            <br>
                            <span>
                                {{\App\CPU\translate('tracking_id')}} : {{$order->third_party_delivery_tracking_id}}
                            </span>
                        </p>
                        @endif
                    @endif
                </th>

                <th>
                    <table class="calc-table">
                        <tbody>
                            <tr>
                                <td class="p-1 text-left">{{\App\CPU\translate('sub_total')}}</td>
                                <td class="p-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($sub_total))}}</td>
                            </tr>
                            <tr>
                                <td class="p-1 text-left">{{\App\CPU\translate('tax')}}</td>
                                <td class="p-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax))}}</td>
                            </tr>
                            @if ($order->order_type=='default_type')
                            <tr>
                                <td class="p-1 text-left">{{\App\CPU\translate('shipping')}}</td>
                                <td class="p-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="p-1 text-left">{{\App\CPU\translate('coupon_discount')}}</td>
                                <td class="p-1">
                                    - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->discount_amount))}} </td>
                            </tr>
                            @if ($order->order_type=='POS')
                            <tr>
                                <td class="p-1 text-left">{{\App\CPU\translate('extra_discount')}}</td>
                                <td class="p-1">
                                    - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($ext_discount))}} </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="p-1 text-left">{{\App\CPU\translate('discount_on_product')}}</td>
                                <td class="p-1">
                                    - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_discount_on_product))}} </td>
                            </tr>
                            <tr>
                                <td class="border-dashed-top font-weight-bold text-left"><b>{{\App\CPU\translate('total')}} dsgfsdfsdfs</b></td>
                                <td class="border-dashed-top font-weight-bold">
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
        </table>
    </div>
    <br>
    <div class="content-position-y">
        <p><strong>Total Amount (in words):</strong> {{ \App\CPU\Helpers::convertNumberToWords(\App\CPU\BackEndHelper::usd_to_currency($total)) }}</p>

    </div>

    <div class="content-position-y hdntable">
        <table border="1" cellspacing="0" cellpadding="6" style="border-collapse: collapse; width: 100%; text-align: center;">
            <thead>
                <tr>
                    <th>HSN/SKU</th>
                    <th>Taxable Value(%)</th>
                    <th>CGST%</th>
                    <th>CGST Amount</th>
                    <th>SGST%</th>
                    <th>SGST Amount</th>
                    <th>Total Tax Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taxDetails as $tax)
                    <tr>
                        <td>{{ $tax['sku'] }}</td> 
                        <td>{{ $tax['tax'] }}%</td>
                        
                            <td>{{ $tax['tax'] /2}}%</td>

                        <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax/2)) }}</td> 
                    

                            <td>{{ $tax['tax']/2 }}%</td>
                        <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax/2)) }}%</td> 
                 <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax)) }}</td> 

                       
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="content-position-y hdntable">
        <p><strong>Tax Amount (in words):</strong> {{ \App\CPU\Helpers::convertNumberToWords(\App\CPU\BackEndHelper::usd_to_currency($total_tax)) }}</p>
    </div>

        <table class="company-details" style="width: 100%;">
            <tr>
                <td style="width: 40%; vertical-align: top;">
                    <strong>Company's Bank Details</strong><br>
                    Company’s Name: {{ optional($seller->shop)->name ?? 'N/A' }}<br>
                    A/c Holder's Name:{{ $order->customer != null ? $order->customer["f_name"] . ' ' . $order->customer["l_name"] : ''}}<br>
                    Bank Name: {{ optional($seller)->bank_name ?? 'N/A' }}<br>
                    Branch IFSC Code: {{ optional($seller)->ifsc_code ?? 'N/A' }}<br>
                    UPI ID: {{ optional($seller)->upi_id ?? 'N/A' }}
                </td>
                <td style="width: 20%; text-align: right; vertical-align: top; padding: 8px;">
                    @if (!empty($seller->upi_scanner))
                        <img src="{{ asset('storage/app/public/seller/' . $seller->upi_scanner) }}" alt="" style="width: 100px; height: 100px;">
                    @else
                        <!-- <em>N/A</em> -->
                    @endif
                </td>

            </tr>
        </table>

    </div>
</body>
</html>