@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Employee List'))

@section('content')

<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-group"></i>
            </span>
            {{\App\CPU\translate('Admin List')}}
        </h3>

        <!-- Search + Add -->
        <!-- <div class=" d-flex gap-2">
            <div class="">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group input-group-merge input-group-custom">
                        <input type="search" name="search" class="form-control"
                            placeholder="{{\App\CPU\translate('search_by_name_or_email_or_phone')}}"
                            value="{{$search}}" required>

                        <button type="submit" class="btn btn-primary">
                            {{\App\CPU\translate('search')}}
                        </button>
                    </div>
                </form>
            </div>
            <div class="">
                <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary">
                    <i class="tio-add"></i>
                    <span>{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                </a>
            </div>
        </div> -->
 <div class="">
                <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary">
                    <i class="tio-add"></i>
                    <span>{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                </a>
            </div>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('Employee List')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

     <div class=" d-flex gap-2 mb-2">
            <div class="">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group input-group-merge input-group-custom">
                        <input type="search" name="search" class="form-control"
                            placeholder="{{\App\CPU\translate('search_by_name_or_email_or_phone')}}"
                            value="{{$search}}" required>

                        <button type="submit" class="btn btn-primary">
                            {{\App\CPU\translate('search')}}
                        </button>
                    </div>
                </form>
            </div>
            <!-- <div class="">
                <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary">
                    <i class="tio-add"></i>
                    <span>{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                </a>
            </div> -->
        </div>
    <!-- Table -->
    <div class="row">
        <div class="col-lg-12">

            <div class="table-responsive">
                <table
                    class="table">

                    <thead class="text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('Name')}}</th>
                            <th>{{\App\CPU\translate('Email')}}</th>
                            <th>{{\App\CPU\translate('Phone')}}</th>
                            <th>{{\App\CPU\translate('Role')}}</th>
                            <th>{{\App\CPU\translate('Status')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($em as $k=>$e)
                        @if($e->role)
                        <tr>
                            <td>{{$em->firstItem()+$k}}</td>

                            <td class="text-capitalize">
                                {{$e['name']}}
                            </td>

                            <td>
                                <a class="title-color hover-c1" href="mailto:{{$e['email']}}">
                                    {{$e['email']}}
                                </a>
                            </td>

                            <td>
                                <a class="title-color hover-c1" href="tel:{{$e['phone']}}">
                                    {{$e['phone']}}
                                </a>
                            </td>

                            <td>{{$e->role['name']}}</td>

                            <td>
                                @if($e->status)
                                <label class="badge badge-gradient-success">
                                    {{\App\CPU\translate('Active')}}
                                </label>
                                @else
                                <label class="badge badge-gradient-danger">
                                    {{\App\CPU\translate('Inactive')}}
                                </label>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{route('admin.employee.update',[$e['id']])}}"
                                        class="btn btn-outline-info btn-sm square-btn"
                                        title="{{\App\CPU\translate('Edit')}}">
                                        {{\App\CPU\translate('Edit')}}
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>

                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-center justify-content-md-end">
                    {{$em->links()}}
                </div>
            </div>

            @if(count($em)==0)
            <div class="text-center p-4">
                <img class="mb-3 w-160"
                    src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg">
                <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
            </div>
            @endif

        </div>
    </div>


</div>
@endsection