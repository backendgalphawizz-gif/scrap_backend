@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign Guideline'))

@section('content')
<style>
    .guideline-item {
        border: 1px solid #e7eaf3;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 10px;
        background: #fff;
    }

    .guideline-text {
        margin-bottom: 0;
        word-break: break-word;
    }

    .guideline-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }

    .guideline-empty {
        color: #8c98a4;
        font-style: italic;
    }
</style>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
                </span> Campaign Guideline
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Campaign Guideline <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('admin.business-settings.campaign-guideline-update') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label mb-2">{{ \App\CPU\translate('Guideline line') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="guideline-input" placeholder="{{ \App\CPU\translate('Enter a guideline line') }}">
                                <button type="button" class="btn btn--primary" id="add-guideline-btn">{{ \App\CPU\translate('add') }}</button>
                            </div>
                            <small class="text-muted d-block mt-2">{{ \App\CPU\translate('Each line will be saved as a comma separated value') }}</small>
                            <input type="hidden" id="guideline-value" name="value" value="{{ $campaign_guideline->value ?? '' }}">
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label mb-2">{{ \App\CPU\translate('Guideline list') }}</label>
                            <div id="guideline-list"></div>
                        </div>
                        <div class="form-group termdiv">
                            <input class="form-control btn--primary submitbtn" type="submit" value="{{ \App\CPU\translate('submit') }}" name="btn">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function () {
            const guidelineInput = document.getElementById('guideline-input');
            const addGuidelineBtn = document.getElementById('add-guideline-btn');
            const guidelineList = document.getElementById('guideline-list');
            const guidelineValueField = document.getElementById('guideline-value');

            let guidelines = (guidelineValueField.value || '')
                .split(',')
                .map(function (line) {
                    return line.trim();
                })
                .filter(function (line) {
                    return line.length > 0;
                });

            function syncHiddenValue() {
                guidelineValueField.value = guidelines.join(',');
            }

            function renderGuidelines() {
                guidelineList.innerHTML = '';

                if (!guidelines.length) {
                    const emptyState = document.createElement('p');
                    emptyState.className = 'guideline-empty';
                    emptyState.textContent = "{{ \App\CPU\translate('No guideline lines added yet') }}";
                    guidelineList.appendChild(emptyState);
                    syncHiddenValue();
                    return;
                }

                guidelines.forEach(function (line, index) {
                    const item = document.createElement('div');
                    item.className = 'guideline-item';

                    const text = document.createElement('p');
                    text.className = 'guideline-text';
                    text.textContent = (index + 1) + '. ' + line;
                    item.appendChild(text);

                    const actions = document.createElement('div');
                    actions.className = 'guideline-actions';

                    const editBtn = document.createElement('button');
                    editBtn.type = 'button';
                    editBtn.className = 'btn btn-sm btn-outline--primary';
                    editBtn.textContent = "{{ \App\CPU\translate('edit') }}";
                    editBtn.addEventListener('click', function () {
                        const updated = prompt("{{ \App\CPU\translate('Update guideline line') }}", line);
                        if (updated === null) return;

                        const nextValue = updated.trim();
                        if (!nextValue.length) {
                            toastr.error("{{ \App\CPU\translate('Guideline line cannot be empty') }}");
                            return;
                        }

                        guidelines[index] = nextValue;
                        renderGuidelines();
                    });

                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className = 'btn btn-sm btn-outline-danger';
                    deleteBtn.textContent = "{{ \App\CPU\translate('delete') }}";
                    deleteBtn.addEventListener('click', function () {
                        guidelines.splice(index, 1);
                        renderGuidelines();
                    });

                    actions.appendChild(editBtn);
                    actions.appendChild(deleteBtn);
                    item.appendChild(actions);

                    guidelineList.appendChild(item);
                });

                syncHiddenValue();
            }

            function addGuideline() {
                const line = guidelineInput.value.trim();
                if (!line.length) {
                    toastr.error("{{ \App\CPU\translate('Please enter a guideline line') }}");
                    return;
                }

                guidelines.push(line);
                guidelineInput.value = '';
                renderGuidelines();
                guidelineInput.focus();
            }

            addGuidelineBtn.addEventListener('click', addGuideline);
            guidelineInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    addGuideline();
                }
            });

            renderGuidelines();
        })();
    </script>
@endpush
