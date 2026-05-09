@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('User FAQ'))

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-help-circle"></i>
            </span> User FAQ
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>User Management &rsaquo; User FAQ
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage User FAQ</h5>
                    <button type="button" class="btn btn-sm btn-primary" id="addFaqBtn">
                        <i class="mdi mdi-plus"></i> Add FAQ
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.business-settings.user-faq-update') }}" method="POST" id="faqForm">
                        @csrf
                        <div id="faqList">
                            @forelse($faqs as $index => $faq)
                            <div class="faq-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>FAQ #<span class="faq-number">{{ $index + 1 }}</span></strong>
                                    <button type="button" class="btn btn-sm btn-danger remove-faq-btn">
                                        <i class="mdi mdi-delete"></i> Remove
                                    </button>
                                </div>
                                <div class="form-group mb-2">
                                    <label>Question</label>
                                    <input type="text" name="faqs[{{ $index }}][question]" class="form-control"
                                        value="{{ $faq['question'] ?? '' }}" placeholder="Enter question" required>
                                </div>
                                <div class="form-group mb-0">
                                    <label>Answer</label>
                                    <textarea name="faqs[{{ $index }}][answer]" class="form-control" rows="3"
                                        placeholder="Enter answer" required>{{ $faq['answer'] ?? '' }}</textarea>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted" id="emptyMsg">No FAQs added yet. Click "Add FAQ" to get started.</p>
                            @endforelse
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="mdi mdi-content-save"></i> Save FAQs
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="faqTemplate">
    <div class="faq-item border rounded p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>FAQ #<span class="faq-number"></span></strong>
            <button type="button" class="btn btn-sm btn-danger remove-faq-btn">
                <i class="mdi mdi-delete"></i> Remove
            </button>
        </div>
        <div class="form-group mb-2">
            <label>Question</label>
            <input type="text" name="" class="form-control" placeholder="Enter question" required>
        </div>
        <div class="form-group mb-0">
            <label>Answer</label>
            <textarea name="" class="form-control" rows="3" placeholder="Enter answer" required></textarea>
        </div>
    </div>
</template>
@endsection

@push('script')
<script>
    (function () {
        const faqList = document.getElementById('faqList');
        const template = document.getElementById('faqTemplate');
        const addBtn = document.getElementById('addFaqBtn');
        const emptyMsg = document.getElementById('emptyMsg');

        function reindex() {
            faqList.querySelectorAll('.faq-item').forEach(function (item, i) {
                item.querySelector('.faq-number').textContent = i + 1;
                item.querySelector('input[type="text"]').name = 'faqs[' + i + '][question]';
                item.querySelector('textarea').name = 'faqs[' + i + '][answer]';
            });
        }

        addBtn.addEventListener('click', function () {
            if (emptyMsg) emptyMsg.remove();
            const clone = template.content.cloneNode(true);
            faqList.appendChild(clone);
            reindex();
            bindRemove();
        });

        function bindRemove() {
            faqList.querySelectorAll('.remove-faq-btn').forEach(function (btn) {
                btn.onclick = function () {
                    btn.closest('.faq-item').remove();
                    reindex();
                };
            });
        }

        bindRemove();
    })();
</script>
@endpush
