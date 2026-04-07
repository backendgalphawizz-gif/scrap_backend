@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product List'))

@push('css_or_js')
<style>
    .customPrice {
        text-align: left !important;
    }

    .text-right {
        text-align: left !important;
    }
</style>
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
            <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" alt="">
            @if($type == 'in_house')
            {{\App\CPU\translate('Product_List')}}
            @elseif($type == 'seller')
            {{\App\CPU\translate('Seller_Product_List')}}
            @endif
            <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $pro->total() }}</span>
        </h2>
    </div>
    <!-- End Page Title -->

    <div class="row mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-lg-4">
                            <!-- Search -->
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-custom input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{\App\CPU\translate('Search Product Name')}}" aria-label="Search orders" value="{{ $search }}">
                                    <input type="hidden" value="{{ $request_status }}" name="status">
                                    <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                        </div>
                        <div class="col-lg-8 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">
                            @if($type == 'in_house')
                            <div>
                                <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{\App\CPU\translate('Export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a class="dropdown-item" href="{{route('admin.product.export-excel',['in_house', ''])}}">{{\App\CPU\translate('Excel')}}</a></li>
                                    <div class="dropdown-divider"></div>
                                </ul>
                            </div>
                            <a href="{{route('admin.product.stock-limit-list',['in_house'])}}" class="btn btn-info">
                                <span class="text">{{\App\CPU\translate('Limited Sotcks')}}</span>
                            </a>
                            @endif
                            @if (!isset($request_status))
                            <a href="{{route('admin.product.add-new')}}" class="btn btn--primary">
                                <i class="tio-add"></i>
                                <span class="text">{{\App\CPU\translate('Add_New_Product')}}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>



                <div class="table-responsive">
                    <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('Product Name')}}</th>
                                <!-- <th class="text-right">{{\App\CPU\translate('Product Type')}}</th> -->
                                <th class="">{{\App\CPU\translate('Category')}}</th>
                                <th class="">{{\App\CPU\translate('Sub Category')}}</th>
                                <th class="">{{\App\CPU\translate('Vendor')}}</th>
                                <!-- <th class="text-right">{{\App\CPU\translate('purchase_price')}}</th>    -->
                                <th>{{\App\CPU\translate('MRP')}}</th>
                                <th class="text-right">{{\App\CPU\translate('selling_price')}}</th>
                                <th>{{\App\CPU\translate('Discount')}}(%)</th>
                                <th>{{\App\CPU\translate('Calculated Package Weight')}}</th>
                                <th>{{\App\CPU\translate('Stock')}}</th>
                                <th>{{\App\CPU\translate('out_of_Stock')}}</th>
                                <th class="text-right">{{\App\CPU\translate('is_variant')}}</th>
                                <!-- <th class="text-center">{{\App\CPU\translate('Show_as_featured')}}</th> -->
                                <th class="text-center">{{\App\CPU\translate('Active')}} {{\App\CPU\translate('status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pro as $k=>$p)
                            <tr>
                                <th scope="row">{{$pro->firstItem()+$k}}</th>
                                <td>
                                    <a href="{{route('admin.product.view',[$p['id']])}}" class="media align-items-center gap-2">
                                        <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$p['thumbnail']}}"
                                            onerror="this.src='{{asset('/public/assets/back-end/img/brand-logo.png')}}'" class="avatar border" alt="">
                                        <span class="media-body title-color hover-c1">
                                            {{\Illuminate\Support\Str::limit($p['name'],20)}}
                                        </span>
                                    </a>
                                </td>
                                <!-- <td class="text-right">
                                    {{\App\CPU\translate(str_replace('_',' ',$p['product_type']))}}
                                </td> -->

                                <td class="">
                                    {{ $p->category->name ?? "" }}
                                </td>
                                <td class="">
                                    {{ $p->subcategory->name ?? "" }}
                                </td>

                                <td class="">
                                    {{ucwords($p->seller->f_name??"Admin")}} {{ isset($p->seller->shop->name) ? "(".ucwords($p->seller->shop->name).")" : "" }}
                                </td>
                               

                                <!-- <td class="text-right">
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price']))}}
                                </td> -->
                                <td class="text-right">
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price'])) }}
                                            <span class="btn btn-outline-primary btn-sm square-btn"
                                                title="{{ \App\CPU\translate('Edit_Price') }}" data-toggle="modal"
                                                data-target="#mrpEditModal{{ $p['id'] }}">
                                                <i class="tio-edit"></i>
                                            </span>

                                            <!-- Mrp Edit Modal -->
                                            <div class="modal fade" id="mrpEditModal{{ $p['id'] }}" tabindex="-1" role="dialog"
                                                aria-labelledby="mrpEditModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ route('admin.product.update-price', [$p['id']]) }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="mrpEditModalLabel">Edit MRP</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <input type="hidden" name="type" value="mrp">
                                                                        <!-- Base Product Price -->
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="mt-2 customPrice" for="old_price">{{ \App\CPU\translate('Old MRP') }}</label>
                                                                            <input type="text" name="old_price"
                                                                                value="{{ \App\CPU\BackEndHelper::usd_to_currency($p['unit_price']) }}"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="mt-2 customPrice" for="new_price">{{ \App\CPU\translate('New MRP') }}</label>
                                                                            <input type="text" name="new_price"
                                                                                value="{{ \App\CPU\BackEndHelper::usd_to_currency($p['unit_price']) }}"
                                                                                class="form-control"
                                                                                maxlength="6"
                                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <!-- Variation Prices -->
                                                                @php
                                                                $variations = json_decode($p['variation'], true);
                                                                @endphp

                                                                @if (!empty($variations))
                                                                <div class="table-responsive mt-3">
                                                                    <label><strong>Variation Prices</strong></label>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Type</th>
                                                                                <th>Old Price</th>
                                                                                <th>New Price</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($variations as $index => $var)
                                                                            <tr>
                                                                                <td>{{ $var['type'] }}</td>
                                                                                <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($var['price'])) }}</td>
                                                                                <td>
                                                                                    <input type="hidden" name="variations[{{ $index }}][type]" value="{{ $var['type'] }}">
                                                                                    <input type="text" name="variations[{{ $index }}][new_price]"
                                                                                        value="{{ \App\CPU\BackEndHelper::usd_to_currency($var['price']) }}"
                                                                                        class="form-control"
                                                                                        maxlength="6"
                                                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                @endif
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn--primary">Update MRP</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </td>

                                <td class="text-right">
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['selling_price'])) }}
                                            <span class="btn btn-outline-primary btn-sm square-btn"
                                                title="{{ \App\CPU\translate('Edit_Selling_Price') }}" data-toggle="modal"
                                                data-target="#sellingPriceModal{{ $p['id'] }}">
                                                <i class="tio-edit"></i>
                                            </span>

                                            <!-- Selling Price Edit Modal -->
                                            <div class="modal fade" id="sellingPriceModal{{ $p['id'] }}" tabindex="-1" role="dialog"
                                                aria-labelledby="sellingPriceModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ route('admin.product.update-price', [$p['id']]) }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="sellingPriceModalLabel">Edit Selling Price</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <input type="hidden" name="type" value="selling_price">
                                                                        <!-- Base Product Price -->
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="mt-2 customPrice" for="old_price">{{ \App\CPU\translate('Old Selling Price') }}</label>
                                                                            <input type="text" name="old_selling_price"
                                                                                value="{{ \App\CPU\BackEndHelper::usd_to_currency($p['selling_price']) }}"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="mt-2 customPrice" for="selling_price">{{ \App\CPU\translate('New Selling Price') }}</label>
                                                                            <input type="text" name="selling_price"
                                                                                value="{{ \App\CPU\BackEndHelper::usd_to_currency($p['selling_price']) }}"
                                                                                class="form-control"
                                                                                maxlength="6"
                                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <!-- Variation Prices -->
                                                                @php
                                                                $variations = json_decode($p['variation'], true);
                                                                @endphp

                                                                @if (!empty($variations))
                                                                <div class="table-responsive mt-3">
                                                                    <label><strong>Variation Prices</strong></label>
                                                                    <table class="table table-bordered">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Type</th>
                                                                                <th>Old Price</th>
                                                                                <th>New Price</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($variations as $index => $var)
                                                                            <tr>
                                                                                <td>{{ $var['type'] }}</td>
                                                                                <td>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($var['price'])) }}</td>
                                                                                <td>
                                                                                    <input type="hidden" name="variations[{{ $index }}][type]" value="{{ $var['type'] }}">
                                                                                    <input type="text" name="variations[{{ $index }}][new_price]"
                                                                                        value="{{ \App\CPU\BackEndHelper::usd_to_currency($var['price']) }}"
                                                                                        class="form-control"
                                                                                        maxlength="6"
                                                                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                @endif
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn--primary">Update Selling Price</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </td>  

                                <td class="text-right">
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                {{ $p['discount'] }} (%)
                                                <span class="btn btn-outline-primary btn-sm square-btn"
                                                    title="{{ \App\CPU\translate('Edit_Discount') }}" data-toggle="modal"
                                                    data-target="#discountModal{{ $p['id'] }}">
                                                    <i class="tio-edit"></i>
                                                </span>

                                                <!-- Selling Price Edit Modal -->
                                                <div class="modal fade" id="discountModal{{ $p['id'] }}" tabindex="-1" role="dialog"
                                                    aria-labelledby="discountModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form action="{{ route('admin.product.update-price', [$p['id']]) }}" method="post"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="discountModalLabel">Edit Discount</h5>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <input type="hidden" name="type" value="discount">
                                                                            <!-- Base Product Price -->
                                                                            <div class="col-md-12 mb-3">
                                                                                <label class="mt-2 customPrice" for="old_discount">{{ \App\CPU\translate('Old Discount') }}(%)</label>
                                                                                <input type="text" name="old_discount"
                                                                                    value="{{ $p['discount'] }}"
                                                                                    class="form-control" readonly>
                                                                            </div>
                                                                            <div class="col-md-12 mb-3">
                                                                                <label class="mt-2 customPrice" for="discount">{{ \App\CPU\translate('New Discount') }}(%)</label>
                                                                                <input type="text" name="discount"
                                                                                    value="{{ $p['discount'] }}"
                                                                                    class="form-control"
                                                                                    maxlength="4"
                                                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn--primary">Update Discount</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                </td> 
                                
                                <td>{{ $p->package_weight ?? 'N/A' }}</td>

                                <td class="text-right">
                                        <div style="display: flex; gap: 10px; align-items: center;">
                                            {{ $p['current_stock'] }}
                                            <span class="btn btn-outline-primary btn-sm square-btn"
                                                title="{{ \App\CPU\translate('Edit_Quantity') }}" data-toggle="modal"
                                                data-target="#quantityModal{{ $p['id'] }}">
                                                <i class="tio-edit"></i>
                                            </span>

                                            <!-- Selling Price Edit Modal -->
                                            <div class="modal fade" id="quantityModal{{ $p['id'] }}" tabindex="-1" role="dialog"
                                                aria-labelledby="quantityModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ route('admin.product.update-price', [$p['id']]) }}" method="post"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="quantityModalLabel">Edit Stock</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <input type="hidden" name="type" value="quantity">
                                                                        <!-- Base Product Price -->
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="mt-2 customPrice" for="old_quantity">{{ \App\CPU\translate('Old Quantity') }}</label>
                                                                            <input type="text" name="old_quantity"
                                                                                value="{{ $p['current_stock'] }}"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="mt-2 customPrice" for="quantity">{{ \App\CPU\translate('New Quantity') }}</label>
                                                                            <input type="text" name="quantity"
                                                                                value="{{ $p['current_stock'] }}"
                                                                                class="form-control"
                                                                                maxlength="4"
                                                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn--primary">Update Quantity</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                </td>

                               <td class="text-right">
                                    <label class="mx-auto switcher" style="display: flex; align-items: center; justify-content: flex-end;">
                                        <input
                                            type="checkbox"
                                            class="switcher_input"
                                            onclick="toggleOutOfStock({{ $p['id'] }}, this)"
                                            {{ $p['current_stock'] == 0 ? 'checked' : '' }}
                                            title="{{ \App\CPU\translate('Make_Out_of_Stock') }}"
                                        >
                                        <span class="switcher_control"></span>
                                    </label>

                                    <form id="outOfStockForm{{ $p['id'] }}"
                                        action="{{ route('admin.product.update-price', [$p['id']]) }}"
                                        method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="type" value="quantity">
                                        <input type="hidden" name="old_quantity" value="{{ $p['current_stock'] }}">
                                        <input type="hidden" name="quantity" value="0">
                                    </form>
                                </td>



                                <td class="">
                                    @php
                                        $variations = json_decode($p->variation, true);
                                    @endphp

                                    @if (empty($variations))
                                        no
                                    @else
                                        Yes
                                    @endif
                                </td>

                                <!-- <td class="text-center">
                                    <label class="mx-auto switcher">
                                        <input class="switcher_input" type="checkbox"
                                            onclick="featured_status('{{$p['id']}}')" {{$p->featured == 1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td> -->
                                <td class="text-center">
                                    <label class="mx-auto switcher">
                                        <input type="checkbox" class="status switcher_input"
                                            id="{{$p['id']}}" {{$p->status == 1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- <a class="btn btn-outline-info btn-sm square-btn" title="{{ \App\CPU\translate('barcode') }}"
                                            href="{{ route('admin.product.barcode', [$p['id']]) }}">
                                            <i class="tio-barcode"></i>
                                        </a> -->
                                        <a class="btn btn-outline-info btn-sm square-btn" title="View" href="{{route('admin.product.view',[$p['id']])}}">
                                            <i class="tio-invisible"></i>
                                        </a>
                                        <a class="btn btn-outline--primary btn-sm square-btn"
                                            title="{{\App\CPU\translate('Edit')}}"
                                            href="{{route('admin.product.edit',[$p['id']])}}">
                                            <i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm square-btn" href="javascript:"
                                            title="{{\App\CPU\translate('Delete')}}"
                                            onclick="form_alert('product-{{$p['id']}}','Want to delete this item ?')">
                                            <i class="tio-delete"></i>
                                        </a>
                                    </div>
                                    <form action="{{route('admin.product.delete',[$p['id']])}}"
                                        method="post" id="product-{{$p['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {{$pro->links()}}
                    </div>
                </div>

                @if(count($pro)==0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160" src="{{ asset('public/assets/back-end') }}/svg/illustrations/sorry.svg" alt="Image Description">
                    <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                </div>
                @endif

                <div class="table-responsive d-none">
                    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" class="product-list table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('Product Name')}}</th>
                                <th class="text-right">{{\App\CPU\translate('Product Type')}}</th>
                                <th class="">{{\App\CPU\translate('Category')}}</th>
                                <th class="">{{\App\CPU\translate('Sub Category')}}</th>
                                <th class="">{{\App\CPU\translate('Vendor')}}</th>
                                <th class="text-right">{{\App\CPU\translate('purchase_price')}}</th>
                                <th class="text-right">{{\App\CPU\translate('selling_price')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Show_as_featured')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Active')}} {{\App\CPU\translate('status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<!-- Page level plugins -->
<script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Page level custom scripts -->
<script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    var productList = $('.product-list').DataTable({
        ordering: true,
        processing: true,
        serverSide: true,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "{{ route('admin.product.list.paginate') }}"
        }
    })

    $(document).on('change', '.status', function() {
        var id = $(this).attr("id");
        if ($(this).prop("checked") == true) {
            var status = 1;
        } else if ($(this).prop("checked") == false) {
            var status = 0;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.product.status-update')}}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data.success == true) {
                    toastr.success('{{\App\CPU\translate('
                        Status updated successfully ')}}');
                } else if (data.success == false) {
                    toastr.error('{{\App\CPU\translate('
                        Status updated failed.Product must be approved ')}}');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            }
        });
    });

    function featured_status(id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.product.featured-status')}}",
            method: 'POST',
            data: {
                id: id
            },
            success: function() {
                toastr.success('{{\App\CPU\translate('
                    Featured status updated successfully ')}}');
            }
        });
    }
</script>
<script>
    function toggleOutOfStock(productId, checkbox) {
        if (checkbox.checked) {
            // User is trying to mark as "Out of Stock"
            Swal.fire({
                title: '{{ \App\CPU\translate('Are you sure') }}?',
                text: "This will set the stock to 0 (out of stock).",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    const form = document.getElementById('outOfStockForm' + productId);
                    form.querySelector('input[name="quantity"]').value = 0;
                    form.submit();
                } else {
                    // If canceled, revert the toggle back to unchecked
                    checkbox.checked = false;
                }
            });
        } else {
            // User is trying to mark as "In Stock" - handle as needed
            Swal.fire({
                title: 'Update stock quantity manually',
                text: "To mark this product as in stock, please update its quantity manually.",
                icon: 'info',
                confirmButtonText: 'OK'
            });
            // Revert toggle to checked since no automatic restore
            checkbox.checked = true;
        }
    }

</script>



@endpush