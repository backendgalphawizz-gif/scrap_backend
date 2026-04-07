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
        }
        .vendorimg{
            height: 60px;
            width: 150px;
            object-fit: contain;
            margin-top: 10px;
        }

        .tablerowdiv {
            border: 1px solid #000;
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
                <!-- <td style="width: 50%; vertical-align: top; padding: 8px;text-align:right">
                    <div>
                    <img src="{{ asset('public/assets/back-end/img/adminlogo.png') }}" alt="" class="logoimg">
                    </div>
                    <div>
                       <img src="{{ asset('public/assets/back-end/img/shoplogo.png') }}" alt="" class="vendorimg">
                       </div>
                </td> -->
                <td style="width: 50%; vertical-align: top; padding: 8px;text-align:right">

                    <?php 
                        ($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                    ?>
                    <div>
                        <img src="{{asset("storage/app/public/company/$e_commerce_logo")}}" alt="" class="logoimg">
                    </div>
                    <div>
                        <img src="{{ asset('storage/app/public/shop/' . $seller->shop->image) }}" alt="" class="vendorimg">
                    </div>
                </td>
                <!-- <td style="width: 20%; vertical-align: top; padding: 8px;">
                 
                </td> -->
            </tr>
        </table>
    </div>
    <!-- <div class="first content-position-y">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 40%; vertical-align: top;">

                    <img height="40" src="{{asset("/public/assets/back-end/img/adminlogo.png")}}" alt="" class="logoimg">

                </td>
                <td style="width: 50%; vertical-align: top;">
                    <img src="{{asset("/public/assets/back-end/img/vendorlogo.png")}}" alt="" class="logoimg">
                </td>

                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <h4 class="taxdiv" style="">
                        Tax Invoice/Bill of Supply/Cash Memo/Estimate
                    </h4>
                    <p class="recipentdiv" style="">
                        (Original for Recipient)
                    </p>

                    <table border="1" cellspacing="0" cellpadding="6" style="margin-top: 10px; border-collapse: collapse; font-size: 10px; width: 100%;">
                        <tr>
                            <td>Invoice No.</td>
                            <td>PB-ORD21772</td>
                        </tr>
                        <tr>
                            <td>Dated</td>
                            <td>14-08-2025</td>
                        </tr>
                        <tr>
                            <td>Mode/Terms of Payment</td>
                            <td>COD</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div> -->
    <!-- <table class="content-position mb-30">
        <tr>
            <th class="p-0 text-left" style="font-size: 26px">
                {{\App\CPU\translate('Order_Invoice')}}
            </th>
            <th>
                <img height="40" src="{{asset("storage/app/public/company/$company_web_logo")}}" alt="">
            </th>
        </tr>
    </table> -->

    <!-- <table class="bs-0 mb-30 px-10">
            <tr>
                <th class="content-position-y text-left">
                    <h4 class="text-uppercase mb-1 fz-14">
                        {{\App\CPU\translate('invoice')}} #{{ $order->id }}
                    </h4><br>
                    <h4 class="text-uppercase mb-1 fz-14">
                        {{\App\CPU\translate('Shop_Name')}}
                        : {{ $order->seller_is == 'admin' ? $company_name : (isset($order->seller->shop) ? $order->seller->shop->name : \App\CPU\translate('not_found')) }}
                    </h4>
                    @if($order['seller_is']!='admin' && isset($order['seller']) && $order['seller']->gst != null)
                    <h4 class="text-capitalize fz-12">{{\App\CPU\translate('GST')}}
                        : {{ $order['seller']->gst }}</h4>
                    @endif
                </th>
                <th class="content-position-y text-right">
                    <h4 class="fz-14">{{\App\CPU\translate('date')}} : {{date('d-m-Y h:i:s a',strtotime($order['created_at']))}}</h4>
                </th>
            </tr>
        </table> -->

    <!-- <div class="content-position-y">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 8px;">
                    <h5 class="headdiv">Sold By :</h5>
                    <br>
                    <h5>TRIVENI AND COMPANY</h5>
                    <p>Sikar Road, Jaipur, Rajasthan, India, 302013</p>
                    <p>GSTIN/UIN: 08AJNPM9859P1ZV</p>

                </td>
            </tr>
        </table>
    </div> -->
     <div class="content-position-y">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding: 8px;">
                    <h5 class="headdiv">Sold By :</h5>
                    <br>
                    <!-- <h5>TRIVENI AND COMPANY</h5>
                    <p>Sikar Road, Jaipur, Rajasthan, India, 302013</p>
                    <p>GSTIN/UIN: 08AJNPM9859P1ZV</p> -->
                    <h5>{{ optional($seller->shop)->name ?? 'N/A' }}</h5>
                    <p>{{ optional($seller->shop)->address ?? 'N/A' }}</p>
                    <p>{{ optional($seller->shop)->gst_in ?? 'N/A' }}</p>

                </td>
            </tr>
        </table>
    </div>

    <!-- <div class="content-position-y">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
              
                <td style="width: 40%; vertical-align: top; padding: 8px;">
                    <h5 class="headdiv">Billing Address :</h5>
                    <br>
                    <p>
                        @if ($order->billingAddress)
                        <strong>{{ $order->billingAddress['contact_person_name'] ?? '' }}</strong><br>
                        {{ $order->billingAddress['address'] ?? '' }}<br>
                        {{ $order->billingAddress['city'] ?? '' }}, {{ $order->billingAddress['state'] ?? '' }}, {{ $order->billingAddress['country'] ?? '' }}, {{ $order->billingAddress['zip'] ?? '' }}<br>
                        <strong>Area:</strong> {{ $order->billingAddress['area'] ?? '' }}
                        @endif
                    </p>
                </td>

               
                <td style="width: 40%; vertical-align: top; padding: 8px;">
                    <h5 class="headdiv">Shipping Address : (Scan For Google Maps)</h5>
                    <br>
                    <p>
                        @if ($order->shippingAddress)
                        <strong>{{ $order->shippingAddress['contact_person_name'] ?? '' }}</strong><br>
                        {{ $order->shippingAddress['address'] ?? '' }}<br>
                        {{ $order->shippingAddress['city'] ?? '' }}, {{ $order->shippingAddress['state'] ?? '' }}, {{ $order->shippingAddress['country'] ?? '' }}, {{ $order->shippingAddress['zip'] ?? '' }}<br>
                        {{ $order->shippingAddress['phone'] ?? '' }}
                        @endif
                    </p>
                </td>

         
                <td style="width: 20%; text-align: right; vertical-align: top; padding: 0;">
                    <img src="{{ asset('public/assets/back-end/img/invoice/QR_code.png') }}" alt="QR Code" style="width: 100px; height: 100px;">
                </td>
            </tr>
        </table>
    </div> -->
 <div class="content-position-y">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <tr>
                <!-- Billing Address -->
                <td style="width: 40%; vertical-align: top; padding: 8px;">
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
                            <!-- <strong>Area:</strong> {{ $order->billingAddress['area'] ?? 'N/A' }} -->
                        @else
                            <em>No billing address available.</em>
                        @endif
                    </p>

                </td>

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
              
                <td style="width: 20%; text-align: right; vertical-align: top; padding: 0;">
          
                    <img src="{{ asset('storage/app/public/seller/' . $seller->upi_scanner) }}" alt="QR Code" style="width: 100px; height: 100px;">
                </td>
            </tr>
        </table>
    </div>
    <!-- <div class="">
        <section>
            <table class="content-position-y fz-12" style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td class="font-weight-bold p-1" style="width: 30%; vertical-align: top;">
                      
                            <tr>
                                <td style="width: 40%; vertical-align: top; padding: 8px;">
                                    @if ($order->shippingAddress)
                                    <span class="h2" style="margin: 0px;">{{\App\CPU\translate('shipping_to')}} </span>
                                    <div class="h4 montserrat-normal-600">
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['f_name'].' '.$order->customer['l_name']:\App\CPU\translate('name_not_found')}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['email']:\App\CPU\translate('email_not_found')}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['phone']:\App\CPU\translate('phone_not_found')}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->shippingAddress ? $order->shippingAddress['address'] : ""}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->shippingAddress ? $order->shippingAddress['city'] : ""}} {{$order->shippingAddress ? $order->shippingAddress['zip'] : ""}}</p>
                                    </div>
                                    @else
                                    <span class="h2" style="margin: 0px;">{{\App\CPU\translate('customer_info')}} </span>
                                    <div class="h4 montserrat-normal-600">
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['f_name'].' '.$order->customer['l_name']:\App\CPU\translate('name_not_found')}}</p>
                                        @if (isset($order->customer) && $order->customer['id']!=0)
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['email']:\App\CPU\translate('email_not_found')}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['phone']:\App\CPU\translate('phone_not_found')}}</p>
                                        @endif
                                    </div>
                                    @endif
                                    </p>
                                </td>
                            </tr>
                     
                    </td>

                    <td class="font-weight-bold p-1" style="width: 40%; vertical-align: top; padding: 8px;">
                        <table>
                            <tr>
                                <td class="">
                                    @if ($order->billingAddress)
                                    <span class="h2">{{\App\CPU\translate('billing_address')}} </span>
                                    <div class="h4 montserrat-normal-600">
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['contact_person_name'] : ""}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['phone'] : ""}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['address'] : ""}}</p>
                                        <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['city'] : ""}} {{$order->billingAddress ? $order->billingAddress['zip'] : ""}}</p>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="width: 40%; vertical-align: top; padding: 8px;">
                        <div >
                             <img height="40" src="{{asset("storage/app/public/company/$company_web_logo")}}" alt="" style=" height: 100px;">
                       </div>
                    </td>
                </tr>
            </table>


        </section>
    </div> -->


    <!-- <div class="content-position-y"> 
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
            <tbody>
                <tr>
                    <td class="tablerowdiv">1</td>
                    <td style="text-align:left; white-space: nowrap; padding: 5px; vertical-align: top;">
                        <table style="width: 100%; border: none;">
                            <tr class="tablerowdiv">
                           
                                <td style="width: 40px; vertical-align: top;">
                                    <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}"
                                        alt=""
                                        width="30"
                                        style="height: auto;">
                                </td>

                        
                                <td style="padding-left: 8px; vertical-align: top;">
                                    <span>
                                        Triveni Unbrand Laddu Gud Jaggery 27kg <br>
                                        <strong>MRP:</strong> ₹1,950.00 <br>
                                        <strong>HSN</strong>12345 
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                   <td style="text-align:left;text-wrap: nowrap;">
                    
                        <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}" alt="" width="30px" , height=auto>
                        <span>
                            Triveni Unbrand Laddu Gud Jaggery 27kg <br>
                            <strong>MRP:</strong> ₹1,950.00
                        </span>


                    </td> 
                    <td class="tablerowdiv">-</td>
                    <td class="tablerowdiv">1</td>
                    <td class="tablerowdiv">Case</td>
                    <td class="tablerowdiv">₹1,350.00</td>
                    <td class="tablerowdiv">₹1,350.00</td>
                </tr>
                <tr>
                    <td class="tablerowdiv">2</td>
                    <td style="text-align:left; white-space: nowrap; padding: 5px; vertical-align: top;" class="tablerowdiv">
                        <table style="width: 100%; border: none;">
                            <tr class="tablerowdiv">
                               
                                <td style="width: 40px; vertical-align: top;">
                                    <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}"
                                        alt=""
                                        width="30"
                                        style="height: auto;">
                                </td>

                               
                                <td style="padding-left: 8px; vertical-align: top;">
                                    <span>
                                        Triveni Unbrand Laddu Gud Jaggery 27kg <br>
                                        <strong>MRP:</strong> ₹1,950.00 <br>
                                           <strong>HSN</strong>12345 
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                 <td style="text-align:left;">
                        <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}" alt="" width="30px" , height=auto>
                        <div style="display: inline-block;">
                            Paras Sugar Bura, 25 Kg Bag <br>
                            <strong>MRP:</strong> ₹3,000.00
                        </div>
                    </td> 
                    <td class="tablerowdiv">5%</td>
                    <td class="tablerowdiv">1</td>
                    <td class="tablerowdiv">Bag</td>
                    <td class="tablerowdiv">₹1,133.33</td>
                    <td class="tablerowdiv">₹1,133.33</td>
                </tr>
                <tr>
                    <td class="tablerowdiv">3</td>
                    <td style="text-align:left; white-space: nowrap; padding: 5px; vertical-align: top;" class="tablerowdiv">
                        <table style="width: 100%; border: none;">
                            <tr class="tablerowdiv">
                              
                                <td style="width: 40px; vertical-align: top;">
                                    <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}"
                                        alt=""
                                        width="30"
                                        style="height: auto;">
                                </td>

                               
                                <td style="padding-left: 8px; vertical-align: top;">
                                    <span>
                                        Triveni Unbrand Laddu Gud Jaggery 27kg <br>
                                        <strong>MRP:</strong> ₹1,950.00 <br>
                                           <strong>HSN</strong>12345 
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                <td style="text-align:left;">
                        <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}" alt="" width="30px" , height=auto>
                        <div style="display: inline-block;">
                            Deep Jyoti Soya Nuggets (mangodi) 5kg Handle Bag 5kg <br>
                            <strong>MRP:</strong> ₹1,200.00
                        </div>
                    </td>
                    <td class="tablerowdiv">12%</td>
                    <td class="tablerowdiv">1</td>
                    <td class="tablerowdiv">Bag</td>
                    <td class="tablerowdiv">₹316.96</td>
                    <td class="tablerowdiv">₹316.96</td>
                </tr>
                <tr>
                    <td class="tablerowdiv">4</td>
                    <td style="text-align:left; white-space: nowrap; padding: 5px; vertical-align: top;" class="tablerowdiv">
                        <table style="width: 100%; border: none;">
                            <tr class="tablerowdiv">
                              
                                <td style="width: 40px; vertical-align: top;">
                                    <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}"
                                        alt=""
                                        width="30"
                                        style="height: auto;">
                                </td>

                             
                                <td style="padding-left: 8px; vertical-align: top;">
                                    <span>
                                        Triveni Unbrand Laddu Gud Jaggery 27kg <br>
                                        <strong>MRP:</strong> ₹1,950.00 <br>
                                           <strong>HSN</strong>12345 
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                     <td style="text-align:left;">
                        <img src="{{ asset('public/assets/back-end/img/grain.jpg') }}" alt="" width="30px" , height=auto>
                        <div style="display: inline-block;">
                            Tirupati Arhar Dal Neela 30 Kg 30kg <br>
                            <strong>MRP:</strong> ₹4,200.00
                        </div>
                    </td> 
                    <td class="tablerowdiv">-</td>
                    <td class="tablerowdiv">1</td>
                    <td class="tablerowdiv">Bag</td>
                    <td class="tablerowdiv">₹2,730.00</td>
                    <td class="tablerowdiv">₹2,730.00</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;" class="tablerowdiv"><strong>CGST</strong></td>
                    <td class="tablerowdiv">₹47.35</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;" class="tablerowdiv"><strong>SGST</strong></td>
                    <td class="tablerowdiv">₹47.35</td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;" class="tablerowdiv"><strong>TOTAL</strong></td>
                    <td class="tablerowdiv"><strong>₹5,625.00</strong></td>
                </tr>
            </tbody>
        </table>
    </div>-->
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
            $total_tax = 0;
        @endphp
        <tbody>
            @foreach($order->details as $key => $details)
                @php
                
                    $productData = json_decode($details->product_details, true);

                    $line_total = $details->price * $details->qty;
                    $total += $line_total;
                    $total_tax += $details->tax;
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
                    <td class="tablerowdiv">{{ $productData['tax'] ?? '' }}</td>
                    <td class="tablerowdiv">{{ $details->qty }}</td>
                    <td class="tablerowdiv">{{ $productData['unit'] ?? '' }}</td>
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
                    <!-- {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total + $total_tax)) }} -->
                    {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total)) }}
                </strong></td>
            </tr>
        </tbody>
    </table>
</div>

    <!-- <div class="" >
        <div class="content-position-y">
            <table class="customers bs-0">
                <thead>
                    <tr>
                        <th style="width: 100px;" class="thstyle">{{\App\CPU\translate('SL')}}</th>
                        <th style="width: 100px;" class="thstyle">{{\App\CPU\translate('item_description')}}</th>
                        <th style="width: 100px;" class="thstyle">GST%</th>
                        <th style="width: 100px;" class="thstyle">
                            {{\App\CPU\translate('unit_price')}}
                        </th>
                        <th style="width: 100px;" class="thstyle">
                            {{\App\CPU\translate('qty')}}
                        </th>
                        <th style="width: 100px;" class="thstyle">unit</th>
                        <th style="width: 100px;" class="thstyle">Rate</th>
                        <th class="text-right thstyle" style="width: 100px;">
                            {{\App\CPU\translate('total')}}
                        </th>

                    </tr>
                </thead>
                @php
                $subtotal=0;
                $total=0;
                $sub_total=0;
                $total_tax=0;
                $total_shipping_cost=0;
                $total_discount_on_product=0;
                $ext_discount=0;
                @endphp
                <tbody>
                    @foreach($order->details as $key=>$details)
                    @php $subtotal=($details['price'])*$details->qty @endphp
                    <tr>
                        <td class="thstyle">{{$key+1}}</td>
                        <td class="thstyle">
                            {{$details['product']?$details['product']->name:''}}
                            @if($details['variant'])
                            <br>
                            {{\App\CPU\translate('variation')}} : {{$details['variant']}}
                            @endif
                        </td>
                        <td class="thstyle">NA</td>
                        <td class="thstyle">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($details['price']))}}</td>
                        <td class="thstyle">{{$details->qty}}</td>
                        <td class="thstyle">NA</td>
                        <td class="thstyle">NA</td>
                        <td class="text-right thstyle">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                    </tr>

                    @php
                    $sub_total+=$details['price']*$details['qty'];
                    $total_tax+=$details['tax'];
                    $total_shipping_cost+=$details->shipping ? $details->shipping->cost :0;
                    $total_discount_on_product+=$details['discount'];
                    $total+=$subtotal;
                    @endphp
                    @endforeach
                </tbody>
            </table>

            <table border="1" cellspacing="0" cellpadding="6" width="100%">

                
                <tr>
                    <td colspan="6" align="right" style="width: 88%;"><strong>CGST</strong></td>
                    <td>₹47.35</td>
                </tr>

                <tr>
                    <td colspan="6" align="right" style="width: 88%;"><strong>SGST</strong></td>
                    <td>₹47.35</td>
                </tr>

                <tr>
                    <td colspan="6" align="right" style="width: 88%;"><strong>TOTAL</strong></td>
                    <td><strong>₹5,625.00</strong></td>
                </tr>
            </table>

        </div>
    </div> -->


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
        <!-- <p><strong>Total Amount (in words):</strong> FIVE THOUSAND, SIX HUNDRED TWENTY-FIVE RUPEES ONLY</p> -->
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
                        <td>{{ $tax['sku'] }}</td> <!-- SKU code from tax details -->
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

        <table style="width: 100%;">
            <tr>
                <!-- <td style="width: 40%; vertical-align: top;">
                    <strong>Declaration:</strong><br>
                    The details in this estimate are accurate to the best of our knowledge. Prices are based on current rates and may change with scope or market variations.
                </td> -->
                <td style="width: 40%; vertical-align: top;">
                    <strong>Company's Bank Details</strong><br>
                     <!-- {{ $order->customer != null ? $order->customer["shop_name"] : ''}} -->
                    Company’s Name: {{ optional($seller->shop)->name ?? 'N/A' }}<br>
                    A/c Holder's Name:{{ $order->customer != null ? $order->customer["f_name"] . ' ' . $order->customer["l_name"] : ''}}<br>
                    Bank Name: {{ optional($seller)->bank_name ?? 'N/A' }}<br>
                    Branch IFSC Code: {{ optional($seller)->ifsc_code ?? 'N/A' }}<br>
                    UPI ID: {{ optional($seller)->upi_id ?? 'N/A' }}
                </td>
                <td style="width: 20%; text-align: right; vertical-align: top; padding: 8px;">
                    <img src="{{ asset('storage/app/public/seller/' . $seller->upi_scanner) }}" alt="QR Code" style="width: 100px; height: 100px;">
                </td>
            </tr>
        </table>

    </div>
    <!-- <div class="row footerdiv">
            <section>
                <table class="">
                    <tr>
                        <th class="fz-12 font-normal pb-3">
                            {{\App\CPU\translate('If_you_require_any_assistance_or_have_feedback_or_suggestions_about_our_site,_you')}} <br /> {{\App\CPU\translate('can_email_us_at')}} <a href="mail::to({{ $company_email }})">{{ $company_email }}</a>
                        </th>
                    </tr>
                    <tr>
                        <th class="content-position-y bg-light py-4">
                            <div class="d-flex justify-content-center gap-2">
                                <div class="mb-2">
                                    <i class="fa fa-phone"></i>
                                    {{\App\CPU\translate('phone')}}
                                    : {{ $company_phone }}
                                </div>
                                <div class="mb-2">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    {{\App\CPU\translate('email')}}
                                    : {{$company_email}}
                                </div>
                            </div>
                            <div class="mb-2">
                                {{url('/')}}
                            </div>
                            <div>
                                {{\App\CPU\translate('All_copy_right_reserved_©_'.date('Y').'_').$company_name}}
                            </div>
                        </th>
                    </tr>
                </table>
            </section>
        </div> -->

</body>

</html>