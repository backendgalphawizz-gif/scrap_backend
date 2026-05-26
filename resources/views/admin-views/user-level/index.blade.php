@extends('layouts.back-end.app')

@section('title', 'User Levels')

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .user-level-action-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .user-level-action-btn i {
        font-size: 18px;
        line-height: 1;
    }
</style>
@endpush

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
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($levels as $level)
                        <tr id="data-{{ $level->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $level->name }}</td>
                            <td>{{ $level->range_min }} - {{ $level->range_max }}</td>
                            <td>{{ $level->max_participations_per_day }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a class="btn btn-outline-primary btn-sm cursor-pointer user-level-action-btn"
                                        title="{{ \App\CPU\translate('Edit') }}"
                                        href="{{ route('admin.user-level.edit', $level->id) }}">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm cursor-pointer delete user-level-action-btn"
                                        title="{{ \App\CPU\translate('Delete') }}"
                                        id="{{ $level->id }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).on('click', '.delete', function() {
        var id = $(this).attr('id');
        Swal.fire({
            title: '{{ \App\CPU\translate('Are you sure ?') }}',
            text: "{{ \App\CPU\translate('You won\'t be able to revert this!') }}",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ \App\CPU\translate('Yes, delete it!') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.user-level.delete', ['id' => '__ID__']) }}".replace('__ID__', id),
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function() {
                        $('#data-' + id).remove();
                        Swal.fire('', '{{ \App\CPU\translate('User level deleted successfully') }}', 'success');
                    },
                    error: function() {
                        Swal.fire('', '{{ \App\CPU\translate('Failed to delete user level') }}', 'error');
                    }
                });
            }
        });
    });
</script>
@endpush
