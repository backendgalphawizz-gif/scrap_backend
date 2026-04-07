@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Product Preview'))
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@section('content')
<div class="content container-fluid"
style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
<!-- Page Header -->
<!-- PRODUCT-DETAILS-PAGE  -->
<div class="card mb-5 d-none">
   <div class="card-body">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12 mb-4">
               <div class="d-flex justify-content-end flex-wrap gap-10">
                  <a href="{{url()->previous()}}" class="btn btn--primary">
                  <i class="tio-back-ui"></i> {{\App\CPU\translate('Back')}}
                  </a>
                  <a href="{{route('product',$product['slug'])}}" class="btn btn--primary d-none" target="_blank"><i
                     class="tio-globe"></i> {{ \App\CPU\translate('View') }} {{ \App\CPU\translate('from') }} {{ \App\CPU\translate('Website') }}
                  </a>
               </div>
            </div>
            <!-- colum 4 -->
            <div class="col-md-4">
               <div class="product-set-box">
                  <!-- <img src="{{asset('/public/assets/back-end/img/productdetail/product.png')}}" alt="" /> -->
                  <img
                  class=" {{Session::get('direction') === "rtl" ? 'ml-4' : 'mr-4'}}"
                  onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                  src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                  alt="Image Description"/>
               </div>
            </div>
            <!-- colum 4 end  -->
            <!-- colum 8  -->
            <div class="col-md-8">
               <div class="product-set-box-content">
                  <h2>
                     {{$product['name']}}
                  </h2>
                  <p class="text-content">
                     <!-- <b><span class="text-muted">SKUDI </span> - SKUDI44531</b> -->
                  </p>
                  <div class="product-prize">
                     <p class="large-prize">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price']))}}</p>
                     {{-- 
                     <p class="rupes-set-prize"><s>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price']))}}</s></p>
                     --}}
                     <span class="offer-present">
                        <div class="flex-start">
                           <span>{{\App\CPU\translate('Discount')}} : </span>
                           <span
                              class="mx-1">{{ $product->discount_type=='flat'?(\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['discount']))): $product->discount.''.'%'}} </span>
                        </div>
                     </span>
                  </div>
                  <div>
                     @if (count(json_decode($product->colors)) > 0)
                     <div class="d-flex align-items-center flex-wrap gap-10 mb-4">
                        <div class="">
                           <div class="product-description-label">{{\App\CPU\translate('Available color')}}:
                           </div>
                        </div>
                        <div class="">
                           <ul class="align-items-center d-flex flex-wrap gap-2 list-inline mb-0">
                              @foreach (json_decode($product->colors) as $key => $color)
                              <li>
                                 <label class="d-block p-2" style="background: {{ $color }};"
                                    for="{{ $product->id }}-color-{{ $key }}"
                                    ></label>
                              </li>
                              @endforeach
                           </ul>
                        </div>
                     </div>
                     @endif
                  </div>
                  <div class="row">
                     @foreach (json_decode($product->images) as $key => $photo)
                     <div class="col-6 col-md-4 col-lg-3 product-view-admin-img">
                        <div class="card">
                           <div class="card-body">
                              <img class="width-100"
                              onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                              src="{{asset("storage/app/public/product/$photo")}}" alt="Product image">
                           </div>
                        </div>
                     </div>
                     @endforeach
                  </div>
               </div>
               <div class="col-md-10 mt-4">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="tax border-right border-2">
                           <p class="text-muted">{{\App\CPU\translate('TAX')}}</p>
                           <p><b>{{($product['tax'])}}% ({{ ucfirst($product->tax_model) }})</b></p>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="tax text-center border-right">
                           <p class="text-muted">{{\App\CPU\translate('Discount')}}</p>
                           <p><b>{{ $product->discount_type=='flat'?(\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['discount']))): $product->discount.''.'%'}}</b></p>
                        </div>
                     </div>
                     <div class="col-md-4 d-none">
                        <div class="tax text-center">
                           <p class="text-muted">{{\App\CPU\translate('shipping Cost')}}</p>
                           <p><b>{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product->shipping_cost)) }}</b></p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- colum 8 end  -->
            <div class="div-line-throught"></div>
            <!-- colum 6 start  -->
            <div class="col-md-6">
               <div class="d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                     <img
                     class="avatar avatar-xxl d-none avatar-4by3 {{Session::get('direction') === "rtl" ? 'ml-4' : 'mr-4'}}"
                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                     src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                     alt="Image Description">
                     <div class="d-block">
                        <h4 class="display-2 text-dark mb-0">{{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}</h4>
                        <p> {{\App\CPU\translate('of')}} {{$product->reviews->count()}} {{\App\CPU\translate('reviews')}}
                           <span class="badge badge-soft-dark badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></span>
                        </p>
                     </div>
                  </div>
                  <div class="div-review-part-line"></div>
                  <div class="w-50">
                     <ul class="list-unstyled list-unstyled-py-2 mb-0">
                        @php($total=$product->reviews->count())
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                           @php($five=\App\CPU\Helpers::rating_count($product['id'],5))
                           <span
                           class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('5 star')}}</span>
                           <div class="progress flex-grow-1">
                              <div class="progress-bar" role="progressbar"
                                 style="width: {{$total==0?0:($five/$total)*100}}%;"
                                 aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$five}}</span>
                        </li>
                        <!-- End Review Ratings -->
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                           @php($four=\App\CPU\Helpers::rating_count($product['id'],4))
                           <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('4 star')}}</span>
                           <div class="progress flex-grow-1">
                              <div class="progress-bar" role="progressbar"
                                 style="width: {{$total==0?0:($four/$total)*100}}%;"
                                 aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$four}}</span>
                        </li>
                        <!-- End Review Ratings -->
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                           @php($three=\App\CPU\Helpers::rating_count($product['id'],3))
                           <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('3 star')}}</span>
                           <div class="progress flex-grow-1">
                              <div class="progress-bar" role="progressbar"
                                 style="width: {{$total==0?0:($three/$total)*100}}%;"
                                 aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span
                           class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$three}}</span>
                        </li>
                        <!-- End Review Ratings -->
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                           @php($two=\App\CPU\Helpers::rating_count($product['id'],2))
                           <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('2 star')}}</span>
                           <div class="progress flex-grow-1">
                              <div class="progress-bar" role="progressbar"
                                 style="width: {{$total==0?0:($two/$total)*100}}%;"
                                 aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$two}}</span>
                        </li>
                        <!-- End Review Ratings -->
                        <!-- Review Ratings -->
                        <li class="d-flex align-items-center font-size-sm">
                           @php($one=\App\CPU\Helpers::rating_count($product['id'],1))
                           <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('1 star')}}</span>
                           <div class="progress flex-grow-1">
                              <div class="progress-bar" role="progressbar"
                                 style="width: {{$total==0?0:($one/$total)*100}}%;"
                                 aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                           <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$one}}</span>
                        </li>
                        <!-- End Review Ratings -->
                     </ul>
                  </div>
               </div>
               <div class="testimonial-box border-bottom d-none">
                  <!--top------------------------->
                  <div class="box-top">
                     <!--profile----->
                     <div class="profile">
                        <!--img---->
                        <div class="profile-img">
                           <img src="{{asset('/public/assets/back-end/img/productdetail/avtar.png')}}" alt="" />
                        </div>
                        <!--name-and-username-->
                        <div class="name-user">
                           <strong>Customer Name</strong>
                           <div class="reviews">
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <!--Empty star-->
                           </div>
                        </div>
                     </div>
                  </div>
                  <!--Comments---------------------------------------->
                  <div class="client-comment">
                     <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat
                        quis? Provident temporibus architecto asperiores nobis maiores nisi a. Quae
                        doloribus ipsum aliquam tenetur voluptates
                        incidunt blanditiis sed atque cumque.
                     </p>
                  </div>
               </div>
               <div class="testimonial-box border-bottom d-none">
                  <!--top------------------------->
                  <div class="box-top">
                     <!--profile----->
                     <div class="profile">
                        <!--img---->
                        <div class="profile-img">
                           <img src="{{asset('/public/assets/back-end/img/productdetail/avtar.png')}}" alt="" />
                        </div>
                        <!--name-and-username-->
                        <div class="name-user">
                           <strong>Customer Name</strong>
                           <div class="reviews">
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <i class="fas fa-star"></i>
                              <!--Empty star-->
                           </div>
                        </div>
                     </div>
                  </div>
                  <!--Comments---------------------------------------->
                  <div class="client-comment">
                     <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat
                        quis? Provident temporibus architecto asperiores nobis maiores nisi a. Quae
                        doloribus ipsum aliquam tenetur voluptates
                        incidunt blanditiis sed atque cumque.
                     </p>
                  </div>
               </div>
            </div>
            <!-- colum 6 end  -->
            <div class="col-md-6">
               <div class="border-left-type d-none">
                  <div class="specific table-responsive">
                     <h3>Specification </h3>
                     <table class="table table-borderless">
                        <tbody>
                           <tr>
                              <td class="text-muted">Screen Size</td>
                              <td>24 Inches</td>
                           </tr>
                           <tr>
                              <td class="text-muted">Display Resolution Maximum</td>
                              <td>1920x1080</td>
                           </tr>
                           <tr>
                              <td class="text-muted">Brand</td>
                              <td>1 year</td>
                           </tr>
                           <tr>
                              <td class="text-muted">Special Feature</td>
                              <td>1 Year</td>
                           </tr>
                           <tr>
                              <td class="text-muted">Refresh Rate</td>
                              <td>75 Hz</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <div  class="describtion d-none" >
                     <h3>Description</h3>
                     <ul class="">
                        <li>
                           <p>Full HD monitor with 1920x1080 resolution and AOC brand technology; ideal
                              for webinars, meetings, digital work, entertainment, and more
                           </p>
                        </li>
                        <li>
                           <p>
                              In-Plane Switching (IPS) panel technology delivers rich color production
                              that can be viewed accurately from all angles
                           </p>
                        </li>
                        <li>
                           <p>
                              Full HD monitor with 1920x1080 resolution and AOC brand technology;
                              ideal for webinars, meetings, digital work, entertainment, and more
                           </p>
                        </li>
                        <li>
                           <p>
                              In-Plane Switching (IPS) panel technology delivers rich color production
                              that can be viewed accurately from all angle
                           </p>
                        </li>
                        <li>
                           <p>
                              Full HD monitor with 1920x1080 resolution and AOC brand technology;
                              ideal for webinars, meetings, digital work, entertainment, and more
                           </p>
                        </li>
                        <li>
                           <p>
                              In-Plane Switching (IPS) panel technology delivers rich color production
                              that can be viewed accurately from all angles
                           </p>
                        </li>
                        <li>
                           <p>
                              Full HD monitor with 1920x1080 resolution and AOC brand technology;
                              ideal for webinars, meetings, digital work, entertainment, and more
                           </p>
                        </li>
                        <li>
                           <p>
                              In-Plane Switching (IPS) panel technology delivers rich color production
                              that can be viewed accurately from all angles
                           </p>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
         <!-- row end  -->
      </div>
      <!-- container fluid end  -->
   </div>
   <!-- card body end  -->
</div>
<!-- Card end  -->
<div class="">
   <div class="d-flex align-items-center justify-content-between flex-wrap gap-10 mb-3 ">
      <!-- Page Title -->
      <div class="">
         <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/inhouse-product-list.png')}}" alt="">
            {{$product['name']}}
         </h2>
      </div>
      <!-- End Page Title -->
      <div class="d-flex justify-content-end flex-wrap gap-10">
         <a href="{{url()->previous()}}" class="btn btn--primary">
         <i class="tio-back-ui"></i> {{\App\CPU\translate('Back')}}
         </a>
         <a href="{{route('product',$product['slug'])}}" class="d-none btn btn--primary " target="_blank"><i
            class="tio-globe"></i> {{ \App\CPU\translate('View') }} {{ \App\CPU\translate('from') }} {{ \App\CPU\translate('Website') }}
         </a>
      </div>
   </div>
   @if($product['added_by'] == 'seller' && ($product['request_status'] == 0 || $product['request_status'] == 1))
   <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
      <div>
         @if($product['request_status'] == 0)
         <a href="{{route('admin.product.approve-status', ['id'=>$product['id']])}}"
            class="btn btn--primary">
         {{\App\CPU\translate('Approve')}}
         </a>
         @endif
      </div>
      <div>
         <button class="btn btn-warning" data-toggle="modal" data-target="#publishNoteModal">
         {{\App\CPU\translate('deny')}}
         </button>
         <!-- Modal -->
         <div class="modal fade" id="publishNoteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title"
                        id="exampleModalLabel">{{ \App\CPU\translate('denied_note') }}</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <form class="form-group"
                     action="{{ route('admin.product.deny', ['id'=>$product['id']]) }}"
                     method="post">
                     <div class="modal-body">
                        <textarea class="form-control" name="denied_note" rows="3"></textarea>
                        <input type="hidden" name="_token" id="csrf-token"
                           value="{{ csrf_token() }}"/>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('Close')}}
                        </button>
                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Save changes')}}</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   @elseif($product['request_status'] == 2)
   <!-- Card -->
   <div class="card mb-3 mb-lg-5 mt-2 mt-lg-3 bg-warning">
      <!-- Body -->
      <div class="card-body text-center">
         <span class="text-dark">{{ $product['denied_note'] }}</span>
      </div>
   </div>
   @endif
   <!-- Card -->
   <div class="card">
      <!-- Body -->
      <div class="card-body">
         <div class="row align-items-md-center gx-md-5">
            <div class="col-md-auto mb-3 mb-md-0">
               <div class="d-flex align-items-center">
                  <img
                  class="avatar avatar-xxl avatar-4by3 {{Session::get('direction') === "rtl" ? 'ml-4' : 'mr-4'}}"
                  onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                  src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                  alt="Image Description">
                  <div class="d-block">
                     <h4 class="display-2 text-dark mb-0">{{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}</h4>
                     <p> {{\App\CPU\translate('of')}} {{$product->reviews->count()}} {{\App\CPU\translate('reviews')}}
                        <span class="badge badge-soft-dark badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></span>
                     </p>
                  </div>
               </div>
            </div>
            <div class="col-md">
               <ul class="list-unstyled list-unstyled-py-2 mb-0">
                  @php($total=$product->reviews->count())
                  <!-- Review Ratings -->
                  <li class="d-flex align-items-center font-size-sm">
                     @php($five=\App\CPU\Helpers::rating_count($product['id'],5))
                     <span
                     class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('5 star')}}</span>
                     <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                           style="width: {{$total==0?0:($five/$total)*100}}%;"
                           aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                           aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                     <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$five}}</span>
                  </li>
                  <!-- End Review Ratings -->
                  <!-- Review Ratings -->
                  <li class="d-flex align-items-center font-size-sm">
                     @php($four=\App\CPU\Helpers::rating_count($product['id'],4))
                     <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('4 star')}}</span>
                     <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                           style="width: {{$total==0?0:($four/$total)*100}}%;"
                           aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                           aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                     <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$four}}</span>
                  </li>
                  <!-- End Review Ratings -->
                  <!-- Review Ratings -->
                  <li class="d-flex align-items-center font-size-sm">
                     @php($three=\App\CPU\Helpers::rating_count($product['id'],3))
                     <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('3 star')}}</span>
                     <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                           style="width: {{$total==0?0:($three/$total)*100}}%;"
                           aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                           aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                     <span
                     class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$three}}</span>
                  </li>
                  <!-- End Review Ratings -->
                  <!-- Review Ratings -->
                  <li class="d-flex align-items-center font-size-sm">
                     @php($two=\App\CPU\Helpers::rating_count($product['id'],2))
                     <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('2 star')}}</span>
                     <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                           style="width: {{$total==0?0:($two/$total)*100}}%;"
                           aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                           aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                     <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$two}}</span>
                  </li>
                  <!-- End Review Ratings -->
                  <!-- Review Ratings -->
                  <li class="d-flex align-items-center font-size-sm">
                     @php($one=\App\CPU\Helpers::rating_count($product['id'],1))
                     <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('1 star')}}</span>
                     <div class="progress flex-grow-1">
                        <div class="progress-bar" role="progressbar"
                           style="width: {{$total==0?0:($one/$total)*100}}%;"
                           aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                           aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                     <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$one}}</span>
                  </li>
                  <!-- End Review Ratings -->
               </ul>
            </div>
            <div class="col-12">
               <hr>
            </div>
            <div class="col-lg-4 mb-5 mb-lg-0 d-flex flex-column gap-1">
               <div class="flex-start">
                  <h5 class="">{{$product['name']}}</h5>
               </div>
               <div class="flex-start">
                  <span>{{\App\CPU\translate('Price')}} : </span>
                  <span
                     class="mx-1">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['unit_price']))}}</span>
               </div>
               <div class="flex-start">
                  <span>{{\App\CPU\translate('TAX')}} : </span>
                  <span class="mx-1">{{($product['tax'])}}% ({{ ucfirst($product->tax_model) }})</span>
               </div>
               <div class="flex-start">
                  <span>{{\App\CPU\translate('Discount')}} : </span>
                  <span
                     class="mx-1">{{ $product->discount_type=='flat'?(\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product['discount']))): $product->discount.''.'%'}} </span>
               </div>
               @if($product->product_type == 'physical')
               <div class="flex-start">
                  <span>{{\App\CPU\translate('shipping Cost')}} : </span>
                  <span class="mx-1">{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product->shipping_cost)) }}</span>
               </div>
               <div class="flex-start">
                  <span>{{\App\CPU\translate('Current Stock')}} : </span>
                  <span class="mx-1">{{ $product->current_stock }}</span>
               </div>
               @endif
               @if(($product->product_type == 'digital') && ($product->digital_product_type == 'ready_product'))
               <div>
                  <a href="{{asset("storage/app/public/product/digital-product/$product->digital_file_ready")}}" class="btn btn--primary py-1 mt-3" download>{{\App\CPU\translate('download')}}</a>
               </div>
               @endif
            </div>
            <div class="col-lg-8 border-lg-left">
               <div>
                  @if (count(json_decode($product->colors)) > 0)
                  <div class="d-flex align-items-center flex-wrap gap-10 mb-4">
                     <div class="">
                        <div class="product-description-label">{{\App\CPU\translate('Available color')}}:
                        </div>
                     </div>
                     <div class="">
                        <ul class="align-items-center d-flex flex-wrap gap-2 list-inline mb-0">
                           @foreach (json_decode($product->colors) as $key => $color)
                           <li>
                              <label class="d-block p-2" style="background: {{ $color }};"
                                 for="{{ $product->id }}-color-{{ $key }}"
                                 ></label>
                           </li>
                           @endforeach
                        </ul>
                     </div>
                  </div>
                  @endif
               </div>
               <div>
                  <div class="mb-2">{{\App\CPU\translate('Product Image')}}</div>
                  <div class="row g-2">
                     @foreach (json_decode($product->images) as $key => $photo)
                     <div class="col-6 col-md-4 col-lg-3">
                        <div class="card">
                           <div class="card-body">
                             <img class="width-100" style="cursor: pointer;" onclick="showImageModal('{{ asset("storage/app/public/product/$photo") }}')" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" src="{{ asset("storage/app/public/product/$photo") }}" alt="Product image">

                           </div>
                        </div>
                     </div>
                     @endforeach
                  </div>
                  <!-- Image Modal -->
                     <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered modal-md">
                        <div class="modal-content">
                           <div class="modal-body p-0">
                           <img id="modalImage" src="" class="img-fluid w-100" alt="Enlarged image">
                           </div>
                        </div>
                     </div>
                     </div>

               </div>
            </div>
         </div>
      </div>
      <!-- End Body -->
   </div>
   <!-- End Card -->
   <!-- Card -->
   <div class="card mt-3">
      <!-- Table -->
      <div class="table-responsive datatable-custom">
         <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
         <thead class="thead-light thead-50 text-capitalize">
            <tr>
               <th>{{\App\CPU\translate('Reviewer')}}</th>
               <th>{{\App\CPU\translate('Review')}}</th>
               <th>{{\App\CPU\translate('Date')}}</th>
            </tr>
         </thead>
         <tbody>
            @foreach($reviews as $review)
            @if(isset($review->customer))
            <tr>
               <td>
                  <a class="d-flex align-items-center"
                     href="{{route('admin.customer.view',[$review['customer_id']])}}">
                     <div class="avatar avatar-circle">
                        <img
                           class="avatar-img"
                           onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                           src="{{asset('storage/app/public/profile/'.$review->customer->image)}}"
                           alt="Image Description">
                     </div>
                     <div class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                     <span class="d-block h5 text-hover-primary mb-0">{{$review->customer['f_name']." ".$review->customer['l_name']}} <i
                        class="tio-verified text-primary" data-toggle="tooltip" data-placement="top"
                        title="Verified Customer"></i></span>
                     <span class="d-block font-size-sm text-body">{{$review->customer->email??""}}</span>
      </div>
      </a>
      </td>
      <td>
      <div class="text-wrap">
      <div class="d-flex mb-2">
      <label class="badge badge-soft-info">
      <span>{{$review->rating}} <i class="tio-star"></i> </span>
      </label>
      </div>
      <p>
      {{$review['comment']}}
      </p>
      @foreach (json_decode($review->attachment) as $img)
      <a class="float-left" href="{{asset('storage/app/public/review')}}/{{$img}}" data-lightbox="mygallery">
      <img class="p-2" width="60" height="60" src="{{asset('storage/app/public/review')}}/{{$img}}" alt="">
      </a>
      @endforeach
      </div>
      </td>
      <td>
      {{date('d M Y H:i:s',strtotime($review['updated_at']))}}
      </td>
      </tr>
      @endif
      @endforeach
      </tbody>
      </table>
   </div>
   <!-- End Table -->
   <div class="table-responsive mt-4">
      <div class="px-4 d-flex justify-content-lg-end">
         <!-- Pagination -->
         {!! $reviews->links() !!}
      </div>
   </div>
   @if(count($reviews)==0)
   <div class="text-center p-4">
      <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
      <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
   </div>
   @endif
</div>
<!-- End Card -->
</div>
</div>
@endsection
@push('script_2')
<script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
<script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
<script>
   $('input[name="colors_active"]').on('change', function () {
       if (!$('input[name="colors_active"]').is(':checked')) {
           $('#colors-selector').prop('disabled', true);
       } else {
           $('#colors-selector').prop('disabled', false);
       }
   });
   $(document).ready(function () {
       $('.color-var-select').select2({
           templateResult: colorCodeSelect,
           templateSelection: colorCodeSelect,
           escapeMarkup: function (m) {
               return m;
           }
       });
   
       function colorCodeSelect(state) {
           var colorCode = $(state.element).val();
           if (!colorCode) return state.text;
           return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
       }
   });
</script>
<script>
  function showImageModal(src) {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
  }
</script>

@endpush