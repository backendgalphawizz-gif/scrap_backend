@extends('layouts.back-end.app')
@section('title', 'View Training')

@section('content')
<div class="content container-fluid">

   <h2 class="h1 mb-3 d-flex align-items-center gap-2">
        <a class="textfont-set" href="{{ route('admin.training.list-training') }}">
            <i class="tio-chevron-left"></i> Back
        </a>
        Training Details —( <span class="text-primary">{{ $training->title }}</span> )
    </h2>


    <div class="card mb-3">
        <div class="card-body d-flex gap-2">

            <a href="{{ route('admin.training.training-view', [$training->id, 'details']) }}"
               class="btn {{ $tab=='details' ? 'btn-dark' : 'btn-outline-dark'}}">
                Training Details
            </a>

            <a href="{{ route('admin.training.training-view', [$training->id, 'questions']) }}"
               class="btn {{ $tab=='questions' ? 'btn-dark' : 'btn-outline-dark'}}">
                Questions
            </a>

            <a href="{{ route('admin.training.training-view', [$training->id, 'users-training']) }}"
               class="btn {{ $tab=='users-training' ? 'btn-dark' : 'btn-outline-dark'}}">
                Assessor Training Result
            </a>

        </div>
    </div>

    
    @include("admin-views.training.tabs.$tab")

</div>
@endsection
