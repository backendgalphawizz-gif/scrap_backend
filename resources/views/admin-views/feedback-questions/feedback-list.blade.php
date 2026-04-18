@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Feedback'))

@push('css_or_js')
<style>
    .feedback-page-card {
        border: 1px solid #e0e8f2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(16, 42, 67, 0.08);
        background: #fff;
    }

    .feedback-filter-form .form-control,
    .feedback-filter-form .form-select {
        border-radius: 10px;
        border-color: #d9e3ef;
        min-height: 40px;
    }

    .feedback-filter-form .btn {
        border-radius: 10px;
        min-height: 40px;
        white-space: nowrap;
    }

    .feedback-page-card .table {
        margin-bottom: 0;
    }

    .feedback-page-card thead th {
        background: #f1f6fb;
        color: #36526e;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        white-space: nowrap;
    }

    .feedback-page-card td,
    .feedback-page-card th {
        vertical-align: top;
        padding-top: 13px;
        padding-bottom: 13px;
    }

    .feedback-note {
        white-space: pre-wrap;
        min-width: 220px;
    }

    .qa-wrap {
        margin: 0;
        padding-left: 16px;
        min-width: 320px;
    }

    .qa-wrap li + li {
        margin-top: 6px;
    }

    .premium-pagination-wrap {
        border-top: 1px solid #e8ebef;
        margin-top: 0;
        padding: 12px 18px 16px;
    }

    .premium-pagination-shell {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .premium-pagination-inline {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        overflow-x: auto;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-comment-multiple-outline"></i>
            </span> Feedback
        </h3>
    </div>

    <div class="card mb-3 p-3">
        <form method="GET" action="{{ route('admin.feedback.list') }}" class="feedback-filter-form d-flex align-items-center gap-2 flex-wrap">
            <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search by user, campaign, brand, feedback" style="max-width: 320px;">

            <select class="form-select" name="rating" style="max-width: 180px;">
                <option value="">All Ratings</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ (string) $rating === (string) $i ? 'selected' : '' }}>{{ $i }} Star</option>
                @endfor
            </select>

            <select class="form-select" name="limit" style="max-width: 140px;">
                @foreach([10,20,50,100] as $rowLimit)
                    <option value="{{ $rowLimit }}" {{ (int) $limit === $rowLimit ? 'selected' : '' }}>{{ $rowLimit }} / page</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary px-4">Filter</button>
            <a href="{{ route('admin.feedback.list') }}" class="btn btn-outline-secondary px-4">Reset</a>
        </form>
    </div>

    <div class="card feedback-page-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Campaign</th>
                        <th>Brand</th>
                        <th>Rating</th>
                        <th>Feedback Note</th>
                        <th>Q&A</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $index => $feedback)
                        <tr>
                            <td>{{ $feedbacks->firstItem() + $index }}</td>
                            <td>{{ $feedback->user->name ?? 'N/A' }}</td>
                            <td>{{ $feedback->campaign->title ?? 'N/A' }}</td>
                            <td>{{ $feedback->brand->name ?? ($feedback->brand->username ?? 'N/A') }}</td>
                            <td>{{ $feedback->ratings ?? '-' }}</td>
                            <td class="feedback-note">{{ $feedback->user_feedback ?: '-' }}</td>
                            <td>
                                @php
                                    $answers = is_array($feedback->questions) ? $feedback->questions : [];
                                @endphp

                                @if(count($answers))
                                    <ol class="qa-wrap">
                                        @foreach($answers as $answer)
                                            <li>
                                                <strong>{{ $answer['question'] ?? 'Question' }}:</strong>
                                                {{ $answer['answer'] ?? '-' }}
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ \App\CPU\Helpers::setDateTime($feedback) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No feedback found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($feedbacks->hasPages())
            <div class="premium-pagination-wrap">
                <div class="premium-pagination-shell">
                    <div class="premium-pagination-inline">
                        {!! $feedbacks->onEachSide(1)->links('vendor.pagination.premium') !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
