@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Create Review'))

@section('content')

@php
    $isEdit = isset($review);
@endphp

<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-baseline backbtndiv w-100">
            <a class="textfont-set" href="{{ route('admin.reviews.list') }}">
                <i class="tio-chevron-left"></i>{{ \App\CPU\translate('back') }}
            </a>
            {{ \App\CPU\translate('Create Review') }}
        </h2>
    </div>

    <!-- Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.reviews.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                @if($isEdit)
                    <input type="hidden" name="id" value="{{ $review->id }}">
                @endif

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="product_id">{{ \App\CPU\translate('Product') }}</label>
                        <select name="product_id" class="form-control" required>
                            <option value="">{{ \App\CPU\translate('Select a product') }}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $isEdit && $product->id == $review->product_id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="user_name">{{ \App\CPU\translate('User Name') }}</label>
                        <input type="text" name="user_name" class="form-control" 
                            placeholder="{{ \App\CPU\translate('Enter User Name') }}"
                            value="{{ $isEdit ? $review->user_name : '' }}">
                    </div>
                </div>

                <div class="form-group d-none">
                    <label for="order_id">{{ \App\CPU\translate('Order') }} ({{ \App\CPU\translate('Optional') }})</label>
                    <select name="order_id" class="form-control">
                        <option value="">{{ \App\CPU\translate('Select an order') }}</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}" {{ $isEdit && $order->id == $review->order_id ? 'selected' : '' }}>
                                {{ \App\CPU\translate('Order ID') }} #{{ $order->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="rating">{{ \App\CPU\translate('Rating') }}</label>
                    <select class="form-control" name="rating" required>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ $isEdit && $review->rating == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="form-group">
                    <label for="comment">{{ \App\CPU\translate('Comment') }}</label>
                    <textarea class="form-control" name="comment" rows="6" style="min-height: 150px;" required>{{ $isEdit ? $review->comment : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label for="fileUpload">{{ \App\CPU\translate('Attachment') }}</label>

                    @if($isEdit && $review->attachment)
                        <div class="existing-attachments row mb-2">
                            @foreach(json_decode($review->attachment) as $existingImage)
                                <div class="col-6 mb-2 position-relative">
                                    <img src="{{ asset('storage/app/public/review/' . $existingImage) }}" alt="Attachment" style="width:100%; height: 150px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute" style="top:5px; right:5px;" onclick="removeExistingAttachment(this, '{{ $existingImage }}')">X</button>
                                    <input type="hidden" name="existing_attachments[]" value="{{ $existingImage }}">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="row coba mb-2"></div>
                    <div class="text-info">
                        {{ \App\CPU\translate('File type: jpg, jpeg, png. Maximum size: 2MB') }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="user_image">{{ \App\CPU\translate('User Image') }}</label>
                    <input type="file" name="user_image" accept="image/*" class="form-control" onchange="previewUserImage(this)">
                    
                    <div class="text-info mt-1">
                        {{ \App\CPU\translate('Profile image (optional)') }}
                    </div>

                    <div class="mt-3">
                        <img id="user-image-preview" class="__img-70 rounded border {{ $isEdit && $review->user_image ? '' : 'd-none' }}"
                            style="width: 200px;"
                            src="{{ $isEdit && $review->user_image ? asset('storage/app/public/profile/' . $review->user_image) : asset('public/assets/front-end/img/image-place-holder.png') }}"
                            alt="{{ \App\CPU\translate('User Image Preview') }}">
                    </div>

                </div>

                <div class="form-group d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn--primary">
                        {{ $isEdit ? \App\CPU\translate('Update') : \App\CPU\translate('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/back-end/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'fileUpload[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-6',  // changed from col-md-4 to col-6 for 2 columns
                placeholderImage: {
                    image: '{{ asset('public/assets/front-end/img/image-place-holder.png') }}',
                    width: '100%'
                },
                dropFileLabel: "{{ \App\CPU\translate('drop_here') }}",
                onExtensionErr: function (index, file) {
                    toastr.error('{{ \App\CPU\translate('input_png_or_jpg') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{ \App\CPU\translate('file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        function removeExistingAttachment(button, imageName) {
            // Remove the image container div
            button.closest('div.col-6').remove();

            // Optionally, if you want to track deletions, add a hidden input for deleted images
            // For example:
            // $('<input>').attr({
            //     type: 'hidden',
            //     name: 'deleted_attachments[]',
            //     value: imageName
            // }).appendTo('form');
        }
    </script>

    <script>
        function previewUserImage(input) {
            const preview = document.getElementById('user-image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "{{ asset('public/assets/front-end/img/image-place-holder.png') }}";
                preview.classList.add('d-none');
            }
        }
    </script>
@endpush
