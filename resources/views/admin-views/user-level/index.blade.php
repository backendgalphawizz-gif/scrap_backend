@extends('layouts.back-end.app')

@section('title', 'User Levels')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">User Levels</h3>
        <a href="{{ route('admin.user-level.create') }}" class="btn btn-primary float-end">Add New Level</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Level Name</th>
                        <th>Level Range</th>
                        <th>Max Participations/Day</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levels as $level)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $level->name }}</td>
                            <td>{{ $level->range_min }} - {{ $level->range_max }}</td>
                            <td>{{ $level->max_participations_per_day }}</td>
                            <td>
                                <a href="{{ route('admin.user-level.edit', $level->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.user-level.delete', $level->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
