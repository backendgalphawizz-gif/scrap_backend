@extends('layouts.back-end.app')
@section('title','Witness Details')

@section('content')
<div class="content container-fluid">

    <h2 class="h1 mb-3 d-flex align-items-center gap-2">
        <a href="{{ route('admin.application.witness-list') }}">
            <i class="tio-chevron-left"></i> Back
        </a>
        Witness Details 
        <!-- <span class="text-primary">{{ $witness->application_number }}</span> -->
    </h2>


    <div class="card mb-3">
        <div class="card-body d-flex gap-2">

            <a href="{{ route('admin.application.witness-view', [$witness->id,'details']) }}"
               class="btn {{ $tab=='details' ? 'btn-dark' : 'btn-outline-dark' }}">
                Witness Details
            </a>

            <a href="{{ route('admin.application.witness-view', [$witness->id,'assessment']) }}"
               class="btn {{ $tab=='assessment' ? 'btn-dark' : 'btn-outline-dark' }}">
                Assessment
            </a>

            <a href="{{ route('admin.application.witness-view', [$witness->id,'findings']) }}"
               class="btn {{ $tab=='findings' ? 'btn-dark' : 'btn-outline-dark' }}">
                Findings
            </a>

        </div>
    </div>

    {{-- TAB CONTENT --}}
    @include("admin-views.witness.tabs.$tab")

</div>
@endsection
