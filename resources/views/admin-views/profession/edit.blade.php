@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Edit Profession'))

@section('content')
<div class="content container-fluid">
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{ asset('/public/assets/back-end/img/business-setup.png') }}" alt="">
            {{ \App\CPU\translate('Business_Setup') }}
        </h2>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0">{{ \App\CPU\translate('Edit Profession') }}</h5>
            <a href="{{ route('admin.profession.index') }}" class="btn btn-outline-secondary btn-sm">
                {{ \App\CPU\translate('back') }}
            </a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.profession.update', $profession->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{ \App\CPU\translate('Name') }}</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name', $profession->name) }}" maxlength="45" required>
                </div>
                <div class="form-group form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                        {{ old('status', $profession->status) ? 'checked' : '' }}>
                    <label class="form-check-label" for="status">{{ \App\CPU\translate('Active') }}</label>
                </div>
                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('Update') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
