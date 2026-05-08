@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Feedback Management'))

@push('css_or_js')
<style>

    .content-wrapper {
        background:
            radial-gradient(1200px 400px at -10% -20%, rgba(19, 103, 173, 0.08), transparent 60%),
            radial-gradient(900px 360px at 110% -15%, rgba(15, 76, 129, 0.08), transparent 55%);
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-title {
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .feedback-subtitle {
        margin-top: 6px;
        color: #6a7f95;
        font-size: 13px;
    }

    .feedback-create-card,
    .feedback-list-card {
        border: 1px solid #e0e8f2;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 28px rgba(16, 42, 67, 0.08);
        background: #fff;
    }

    .feedback-create-card .card-body {
        padding: 20px;
    }

    .feedback-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        font-size: 15px;
        font-weight: 700;
        color: #1f3550;
    }

    .feedback-section-title .pill {
        background: linear-gradient(135deg, #0f4c81 0%, #1367ad 100%);
        color: #fff;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
    }

    .feedback-filter-form .form-control,
    .feedback-filter-form .form-select {
        border-radius: 10px;
        border-color: #d9e3ef;
        background: #fff;
    }

    .feedback-filter-form .btn {
        border-radius: 10px;
        font-weight: 600;
    }

    .option-row {
        background: #fff;
        border: 1px solid #cfd9e5;
        border-radius: 12px;
        padding: 10px;
        transition: all .18s ease;
    }

    #create-options-wrap,
    #edit-options-wrap {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .option-row:hover {
        border-color: #b8cade;
        box-shadow: 0 4px 12px rgba(15, 76, 129, 0.1);
    }

    .option-row .form-control {
        min-width: 0;
        border-radius: 8px;
        border-color: #d9e2ec;
        background: #fff;
    }

    .option-row-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .option-index {
        min-width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #eef4fb;
        color: #0f4c81;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d2e3f4;
        padding: 0 6px;
    }

    .option-remove-btn {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        border: 1px solid #f3c7cf;
        background: #fff5f7;
        color: #d9304f;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .16s ease;
    }

    .option-remove-btn:hover {
        background: #ffe9ee;
        border-color: #eca9b7;
    }

    .option-remove-btn:disabled {
        opacity: .45;
        cursor: not-allowed;
    }

    .option-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .options-builder {
        border: 1px solid #dfe7f1;
        border-radius: 12px;
        padding: 12px;
        background: #fbfdff;
    }

    .options-note {
        font-size: 12px;
        color: #607286;
        margin-bottom: 8px;
    }

    .input-type-note {
        border: 1px dashed #cbd8e6;
        border-radius: 8px;
        padding: 10px 12px;
        background: #f6f9fd;
        color: #54677d;
        font-size: 13px;
    }

    .option-chip {
        display: inline-block;
        border: 1px solid #d6e6f6;
        border-radius: 999px;
        color: #184f86;
        background: #eef4fb;
        padding: 2px 10px;
        margin: 3px 4px 3px 0;
        font-size: 12px;
    }

    .feedback-create-card .form-label,
    #editQuestionModal .form-label {
        font-weight: 600;
        color: #334b63;
    }

    .feedback-create-card .form-control,
    .feedback-create-card .form-select,
    #editQuestionModal .form-control,
    #editQuestionModal .form-select {
        border-radius: 10px;
        border-color: #d9e3ef;
        min-height: 42px;
    }

    .feedback-create-card .form-control:focus,
    .feedback-create-card .form-select:focus,
    #editQuestionModal .form-control:focus,
    #editQuestionModal .form-select:focus {
        border-color: #1367ad;
        box-shadow: 0 0 0 0.18rem rgba(19, 103, 173, 0.14);
    }

    .feedback-create-card .btn-primary,
    .feedback-filter-form .btn-primary,
    #editQuestionModal .btn-primary {
        background: linear-gradient(135deg, #0f4c81 0%, #1367ad 100%);
        border: none;
        box-shadow: 0 8px 18px rgba(15, 76, 129, 0.24);
    }

    .feedback-create-card .btn-primary:hover,
    .feedback-filter-form .btn-primary:hover,
    #editQuestionModal .btn-primary:hover {
        filter: brightness(0.98);
        transform: translateY(-1px);
    }

    .feedback-list-card .table {
        margin-bottom: 0;
    }

    .feedback-list-card thead th {
        background: #f1f6fb;
        color: #36526e;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        border-bottom: 1px solid #d7e4f1;
        white-space: nowrap;
    }

    .feedback-list-card tbody tr {
        transition: all .18s ease;
    }

    .feedback-list-card tbody tr:hover {
        background: #fbfdff;
        box-shadow: inset 0 0 0 1px #edf3fa;
    }

    .feedback-list-card td,
    .feedback-list-card th {
        vertical-align: middle;
        padding-top: 13px;
        padding-bottom: 13px;
    }

    .feedback-list-card .btn-sm {
        border-radius: 8px;
        font-weight: 600;
    }

    .question-action-group {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .question-action-btn {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .question-action-btn i {
        font-size: 17px;
        line-height: 1;
    }

    .question-status-btn {
        border: none;
        color: #fff;
        box-shadow: 0 6px 14px rgba(16, 42, 67, 0.18);
    }

    .question-status-btn.status-deactivate {
        background: linear-gradient(135deg, #d9485f 0%, #c83349 100%);
    }

    .question-status-btn.status-activate {
        background: linear-gradient(135deg, #1f9d62 0%, #168750 100%);
    }

    .question-status-btn:hover {
        color: #fff;
        filter: brightness(0.98);
        transform: translateY(-1px);
    }

    #editQuestionModal .modal-content {
        border: 1px solid #dfe9f4;
        border-radius: 14px;
        box-shadow: 0 16px 34px rgba(16, 42, 67, 0.2);
    }

    #editQuestionModal .modal-header {
        border-bottom: 1px solid #e6eef7;
        background: linear-gradient(180deg, #f8fbff, #ffffff);
    }

    #editQuestionModal .modal-title {
        font-weight: 700;
        color: #1f3550;
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


    @media (max-width: 767px) {
        .feedback-create-card .card-body {
            padding: 14px;
        }

        .feedback-section-title {
            margin-bottom: 12px;
        }

        .option-actions {
            gap: 6px;
        }

        #create-options-wrap,
        #edit-options-wrap {
            grid-template-columns: 1fr;
        }

        .feedback-filter-form {
            justify-content: flex-start !important;
        }
    }
</style>
@endpush

@section('content')

<style>
    .option-actions {
    width: 100%;
    float: left;
    margin-bottom: 19px;
}
 .option-row {
    width: 45%;
    float: left;
    margin-right: 5%;
    margin-bottom: 32px;
    border: 1px solid;
    padding: 11px;
    border-radius: 10px;
}
button.option-remove-btn.remove-option {
    float: right;
    border: 1px solid;
    border-radius: 50%;
    height: 25px;
    width: 25px;
    font-size: 15px;
    line-height: 22px;
    padding: 1px;
    color: red;
}
</style>
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-comment-multiple-outline"></i>
            </span> Feedback Management
        </h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-4 feedback-create-card">
        <div class="card-body">
            <div class="feedback-section-title">
                <span class="pill">Builder</span>
                <span>Add New Feedback Question</span>
            </div>
            <form method="POST" action="{{ route('admin.feedback-questions.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-9">
                        <label class="form-label">Question</label>
                        <input type="text" class="form-control" name="question" value="{{ old('question') }}" required placeholder="Enter question text">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select class="form-select question-type-select" name="question_type" id="create-question-type" required>
                            <option value="multiple_choice" {{ old('question_type', 'multiple_choice') === 'multiple_choice' ? 'selected' : '' }}>MCQ</option>
                            <option value="input" {{ old('question_type') === 'input' ? 'selected' : '' }}>Input</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12" id="create-options-section">
                        <label class="form-label">Multiple Choice Options</label>
                        <div class="options-builder">
                            <p class="options-note mb-2">Add clear answer choices. Minimum 2 options are required for MCQ.</p>
                            <div id="create-options-wrap">
                                <div class="option-row">
                                    <div class="option-row-head">
                                        <span class="option-index">1</span>
                                        <button type="button" class="option-remove-btn remove-option" title="Remove option" aria-label="Remove option">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" name="options[]" placeholder="Enter option 1" required>
                                </div>
                                <div class="option-row">
                                    <div class="option-row-head">
                                        <span class="option-index">2</span>
                                        <button type="button" class="option-remove-btn remove-option" title="Remove option" aria-label="Remove option">
                                            <i class="mdi mdi-close"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" name="options[]" placeholder="Enter option 2" required>
                                </div>
                            </div>
                            <div class="option-actions">
                                <button type="button" class="btn btn-outline-primary" id="addCreateOption">Add Option</button>
                                <small class="text-muted">Tip: Keep options concise and mutually exclusive.</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-none" id="create-input-note">
                        <div class="input-type-note">This question will accept a free-text input from user. Options are not required.</div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary px-4">Save Question</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="px-3 pt-3 pb-3 d-flex justify-content-end mb-2">
        <form method="GET" action="{{ route('admin.feedback-questions.index') }}" class="feedback-filter-form d-flex align-items-center justify-content-end gap-2">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search question" style="max-width: 260px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.feedback-questions.index') }}" class="btn btn-outline-secondary">Reset</a>
        </form>
    </div>

    <div class="card feedback-list-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Type</th>
                        <th>Options</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $index => $question)
                        <tr>
                            <td>{{ $questions->firstItem() + $index }}</td>
                            <td>{{ $question->question }}</td>
                            <td>{{ $question->question_type === 'input' ? 'Input' : 'MCQ' }}</td>
                            <td>
                                @php($options = is_array($question->options) ? $question->options : [])
                                @if($question->question_type === 'input')
                                    <span class="text-muted">Text input answer</span>
                                @else
                                    @forelse($options as $option)
                                        <span class="option-chip">{{ $option }}</span>
                                    @empty
                                        -
                                    @endforelse
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $question->status ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                    {{ $question->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="question-action-group">
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary edit-question-btn question-action-btn"
                                            title="Edit"
                                            aria-label="Edit"
                                            data-id="{{ $question->id }}"
                                            data-question="{{ $question->question }}"
                                            data-question-type="{{ $question->question_type ?? 'multiple_choice' }}"
                                            data-status="{{ $question->status ? 1 : 0 }}"
                                            data-options='@json($options)'>
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </button>

                                    <form method="POST" action="{{ route('admin.feedback-questions.toggle-status', $question->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-sm question-action-btn question-status-btn {{ $question->status ? 'status-deactivate' : 'status-activate' }}"
                                                title="{{ $question->status ? 'Deactivate' : 'Activate' }}"
                                                aria-label="{{ $question->status ? 'Deactivate' : 'Activate' }}">
                                            <i class="mdi {{ $question->status ? 'mdi-close-circle-outline' : 'mdi-check-circle-outline' }}"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.feedback-questions.delete', $question->id) }}" class="d-inline" onsubmit="return confirm('Delete this question?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger question-action-btn" title="Delete" aria-label="Delete">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No feedback questions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questions->hasPages())
            <div class="premium-pagination-wrap">
                <div class="premium-pagination-shell">
                    <div class="premium-pagination-inline">
                        {!! $questions->onEachSide(1)->links('vendor.pagination.premium') !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="editQuestionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="editQuestionForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Feedback Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <label class="form-label">Question</label>
                            <input type="text" class="form-control" name="question" id="edit-question" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select class="form-select question-type-select" name="question_type" id="edit-question-type" required>
                                <option value="multiple_choice">MCQ</option>
                                <option value="input">Input</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit-status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12" id="edit-options-section">
                            <label class="form-label">Multiple Choice Options</label>
                            <div class="options-builder">
                                <p class="options-note mb-2">Keep at least 2 options for MCQ questions.</p>
                                <div id="edit-options-wrap"></div>
                                <div class="option-actions">
                                    <button type="button" class="btn btn-outline-primary" id="addEditOption">Add Option</button>
                                    <small class="text-muted">Short, direct options improve response quality.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-none" id="edit-input-note">
                            <div class="input-type-note">This question accepts user text input only. MCQ options are ignored.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script_2')
<script>
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    function optionRowHtml(value = '') {
        return `<div class="option-row">
            <div class="option-row-head">
                <span class="option-index">#</span>
                <button type="button" class="option-remove-btn remove-option" title="Remove option" aria-label="Remove option">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
            <input type="text" class="form-control" name="options[]" value="${escapeHtml(value)}" required>
        </div>`;
    }

    function syncOptionRowNumbers(wrapId) {
        const wrap = document.getElementById(wrapId);
        const rows = wrap.querySelectorAll('.option-row');
        rows.forEach(function(row, index) {
            const badge = row.querySelector('.option-index');
            const input = row.querySelector('input[name="options[]"]');
            if (badge) {
                badge.textContent = index + 1;
            }
            if (input && !input.value) {
                input.setAttribute('placeholder', `Enter option ${index + 1}`);
            }
        });
    }

    function updateRemoveButtonsState(wrapId) {
        const wrap = document.getElementById(wrapId);
        const rows = wrap.querySelectorAll('.option-row');
        rows.forEach(function(row) {
            const button = row.querySelector('.remove-option');
            if (!button) {
                return;
            }
            button.disabled = rows.length <= 2;
        });
    }

    function toggleOptionsSection(typeSelectId, sectionId, wrapId, inputNoteId) {
        const type = document.getElementById(typeSelectId).value;
        const section = document.getElementById(sectionId);
        const wrap = document.getElementById(wrapId);
        const inputNote = document.getElementById(inputNoteId);

        if (type === 'input') {
            section.style.display = 'none';
            if (inputNote) {
                inputNote.classList.remove('d-none');
            }
            wrap.querySelectorAll('input[name="options[]"]').forEach(function(input) {
                input.removeAttribute('required');
            });
        } else {
            section.style.display = '';
            if (inputNote) {
                inputNote.classList.add('d-none');
            }
            while (wrap.children.length < 2) {
                wrap.insertAdjacentHTML('beforeend', optionRowHtml(''));
            }
            wrap.querySelectorAll('input[name="options[]"]').forEach(function(input) {
                input.setAttribute('required', 'required');
            });
            syncOptionRowNumbers(wrapId);
            updateRemoveButtonsState(wrapId);
        }
    }

    document.getElementById('addCreateOption').addEventListener('click', function() {
        document.getElementById('create-options-wrap').insertAdjacentHTML('beforeend', optionRowHtml(''));
        syncOptionRowNumbers('create-options-wrap');
        updateRemoveButtonsState('create-options-wrap');
    });

    document.getElementById('addEditOption').addEventListener('click', function() {
        document.getElementById('edit-options-wrap').insertAdjacentHTML('beforeend', optionRowHtml(''));
        syncOptionRowNumbers('edit-options-wrap');
        updateRemoveButtonsState('edit-options-wrap');
    });

    document.getElementById('create-question-type').addEventListener('change', function() {
        toggleOptionsSection('create-question-type', 'create-options-section', 'create-options-wrap', 'create-input-note');
    });

    document.getElementById('edit-question-type').addEventListener('change', function() {
        toggleOptionsSection('edit-question-type', 'edit-options-section', 'edit-options-wrap', 'edit-input-note');
    });

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-option');
        if (!btn) return;
        if (btn.disabled) return;

        const row = btn.closest('.option-row');
        const wrap = row ? row.parentElement : null;
        if (row) row.remove();

        if (wrap && wrap.id) {
            syncOptionRowNumbers(wrap.id);
            updateRemoveButtonsState(wrap.id);
        }
    });

    document.querySelectorAll('.edit-question-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const question = this.dataset.question || '';
            const questionType = this.dataset.questionType || 'multiple_choice';
            const status = this.dataset.status || '1';
            let options = [];

            try {
                options = JSON.parse(this.dataset.options || '[]');
            } catch (e) {
                options = [];
            }

            document.getElementById('edit-question').value = question;
            document.getElementById('edit-question-type').value = questionType;
            document.getElementById('edit-status').value = status;

            const wrap = document.getElementById('edit-options-wrap');
            wrap.innerHTML = '';

            if (Array.isArray(options) && options.length) {
                options.forEach(function(item) {
                    wrap.insertAdjacentHTML('beforeend', optionRowHtml(item));
                });
            }

            while (wrap.children.length < 2) {
                wrap.insertAdjacentHTML('beforeend', optionRowHtml(''));
            }

            syncOptionRowNumbers('edit-options-wrap');
            updateRemoveButtonsState('edit-options-wrap');
            toggleOptionsSection('edit-question-type', 'edit-options-section', 'edit-options-wrap', 'edit-input-note');

            document.getElementById('editQuestionForm').setAttribute('action', `{{ url('admin/feedback-questions/update') }}/${id}`);

            const modal = new bootstrap.Modal(document.getElementById('editQuestionModal'));
            modal.show();
        });
    });

    syncOptionRowNumbers('create-options-wrap');
    updateRemoveButtonsState('create-options-wrap');
    toggleOptionsSection('create-question-type', 'create-options-section', 'create-options-wrap', 'create-input-note');
</script>
@endpush
