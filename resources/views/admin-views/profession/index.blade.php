@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Professions'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .profession-action-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .profession-action-btn i {
        font-size: 18px;
        line-height: 1;
    }

    .profession-status-switch .form-check-input {
        width: 2.75em;
        height: 1.4em;
        cursor: pointer;
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="content container-fluid">
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{ asset('/public/assets/back-end/img/business-setup.png') }}" alt="">
            {{ \App\CPU\translate('Business_Setup') }}
        </h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="card mt-3">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h5 class="mb-0">{{ \App\CPU\translate('Professions') }}</h5>
            <a href="{{ route('admin.profession.create') }}" class="btn btn-primary btn-sm">
                {{ \App\CPU\translate('Add Profession') }}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ \App\CPU\translate('Name') }}</th>
                            <th class="text-center">{{ \App\CPU\translate('Status') }}</th>
                            <th class="text-center">{{ \App\CPU\translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($professions as $profession)
                            <tr id="data-{{ $profession->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $profession->name }}</td>
                                <td class="text-center">
                                    <div class="form-check form-switch profession-status-switch d-inline-flex justify-content-center mb-0">
                                        <input class="form-check-input profession-status"
                                            type="checkbox"
                                            role="switch"
                                            data-id="{{ $profession->id }}"
                                            {{ $profession->status ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a class="btn btn-outline-primary btn-sm profession-action-btn"
                                            title="{{ \App\CPU\translate('Edit') }}"
                                            href="{{ route('admin.profession.edit', $profession->id) }}">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>
                                        <a class="btn btn-outline-danger btn-sm delete profession-action-btn"
                                            title="{{ \App\CPU\translate('Delete') }}"
                                            id="{{ $profession->id }}">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">{{ \App\CPU\translate('No data found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function notifySuccess(message) {
        if (typeof toastr !== 'undefined' && toastr.success) {
            toastr.success(message);
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000
        });
    }

    function notifyError(message) {
        if (typeof toastr !== 'undefined' && toastr.error) {
            toastr.error(message);
            return;
        }
        Swal.fire('', message, 'error');
    }

    $(document).on('change', '.profession-status', function () {
        const $input = $(this);
        const id = $input.data('id');
        const status = $input.is(':checked') ? 1 : 0;

        $.ajax({
            url: "{{ route('admin.profession.status') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: { id: id, status: status },
            success: function () {
                notifySuccess('{{ \App\CPU\translate('Profession status updated successfully') }}');
            },
            error: function () {
                $input.prop('checked', !status);
                notifyError('{{ \App\CPU\translate('Failed to update profession status') }}');
            }
        });
    });

    $(document).on('click', '.delete', function () {
        const id = $(this).attr('id');

        Swal.fire({
            title: '{{ \App\CPU\translate('Are you sure ?') }}',
            text: "{{ \App\CPU\translate('You won\'t be able to revert this!') }}",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ \App\CPU\translate('Yes, delete it!') }}'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: "{{ route('admin.profession.delete', ['id' => '__ID__']) }}".replace('__ID__', id),
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    $('#data-' + id).remove();
                    notifySuccess('{{ \App\CPU\translate('Profession deleted successfully') }}');
                },
                error: function () {
                    notifyError('{{ \App\CPU\translate('Failed to delete profession') }}');
                }
            });
        });
    });
</script>
@endpush
