@extends('layouts.back-end.app')
@section('title', 'Edit Question')

@section('content')
<div class="content container-fluid">

    <div class="mb-3">
        <h2 class="h1 mb-0 d-flex align-items-center gap-2">
            <a class="textfont-set" href="{{ route('admin.training.training-view', [$question->training_id, 'questions']) }}">
                <i class="tio-chevron-left"></i> Back
            </a>
            Edit Question ({{ $training->title }})
        </h2>
    </div>


    <div class="card">
        <div class="card-body">

            <h5 class="mb-3 page-header-title border-bottom pb-3">
                <i class="tio-edit"></i> Update Question
            </h5>

            <form action="{{ route('admin.training.questions-update', $question->id) }}" method="POST">
                @csrf

                <input type="hidden" name="training_id" value="{{ $training->id }}">

                <div class="row">

                    <!-- QUESTION -->
                    <div class="col-md-12 mb-3">
                        <label for="question" class="title-color">Question</label>
                        <textarea name="question" id="question" class="form-control" required>{{ $question->question }}</textarea>
                    </div>

                    <!-- A -->
                    <div class="col-md-6 mb-3">
                        <label for="option_a" class="title-color">Option A</label>
                        <input type="text" id="option_a" name="option_a" class="form-control" value="{{ $question->option_a }}" required>
                    </div>

                    <!-- B -->
                    <div class="col-md-6 mb-3">
                        <label for="option_b" class="title-color">Option B</label>
                        <input type="text" id="option_b" name="option_b" class="form-control" value="{{ $question->option_b }}" required>
                    </div>

                    <!-- C -->
                    <div class="col-md-6 mb-3">
                        <label for="option_c" class="title-color">Option C</label>
                        <input type="text" id="option_c" name="option_c" class="form-control" value="{{ $question->option_c }}" required>
                    </div>

                    <!-- D -->
                    <div class="col-md-6 mb-3">
                        <label for="option_d" class="title-color">Option D</label>
                        <input type="text" id="option_d" name="option_d" class="form-control" value="{{ $question->option_d }}" required>
                    </div>

                    <!-- CORRECT ANSWER -->
                    <div class="col-md-4 mb-3">
                        <label for="correct_answer" class="title-color">Correct Answer</label>
                        <select name="correct_answer" id="correct_answer" class="form-control" required>
                            <option value="option_a" {{ $question->correct_answer == 'option_a' ? 'selected' : '' }}>Option A</option>
                            <option value="option_b" {{ $question->correct_answer == 'option_b' ? 'selected' : '' }}>Option B</option>
                            <option value="option_c" {{ $question->correct_answer == 'option_c' ? 'selected' : '' }}>Option C</option>
                            <option value="option_d" {{ $question->correct_answer == 'option_d' ? 'selected' : '' }}>Option D</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="btn btn--primary px-4">Update Question</button>

            </form>

        </div>
    </div>

</div>
@endsection
