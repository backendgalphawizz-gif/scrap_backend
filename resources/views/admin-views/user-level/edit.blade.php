@extends('layouts.back-end.app')

@section('title', 'Edit User Level')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Edit User Level</h3>
        <a href="{{ route('admin.user-level.index') }}" class="btn btn-secondary float-end">Back</a>
    </div>
    <div class="card mt-3">
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
            <form action="{{ route('admin.user-level.update', $level->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Level Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $level->name }}" required>
                </div>
                <div class="mb-3 row">
                    <div class="col">
                        <label for="range_min" class="form-label">Level Range Min</label>
                        <input type="number" class="form-control" id="range_min" name="range_min"
                               value="{{ old('range_min', $expectedMin) }}" min="0" required readonly>
                        @if($previous)
                            <small class="text-muted">Must start at {{ $expectedMin }} (after {{ $previous->name }}: {{ $previous->range_min }} - {{ $previous->range_max }})</small>
                        @else
                            <small class="text-muted">First level must start at 0</small>
                        @endif
                    </div>
                    <div class="col">
                        <label for="range_max" class="form-label">Level Range Max</label>
                        <input type="number" class="form-control" id="range_max" name="range_max"
                               value="{{ old('range_max', $level->range_max) }}"
                               min="{{ $expectedMin }}" @if($lockedMax !== null) max="{{ $lockedMax }}" @endif
                               @if($lockedMax !== null) readonly @endif required>
                        @if($next)
                            <small class="text-muted">Must end at {{ $lockedMax }} so {{ $next->name }} can start at {{ $next->range_min }}</small>
                        @else
                            <small class="text-muted">Last level — set any max &ge; {{ $expectedMin }}</small>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label for="max_participations_per_day" class="form-label">Max Participations Per Day</label>
                    <input type="number" class="form-control" id="max_participations_per_day" name="max_participations_per_day" value="{{ $level->max_participations_per_day }}" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Level</button>
            </form>
        </div>
    </div>
</div>
@endsection
