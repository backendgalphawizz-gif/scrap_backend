@extends('layouts.back-end.app')

@section('content')
<div class="content container-fluid">
    <div class="row justify-content-left mb-5 mt-5 ml-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Payment Split Settings</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.payment-split.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>User Percentage (%)</label>
                            <input type="number" name="user_percentage" class="form-control" value="{{ old('user_percentage', $split->user_percentage) }}" min="0" max="100" required>
                        </div>
                        <div class="form-group">
                            <label>Sales Percentage (%)</label>
                            <input type="number" name="sales_percentage" class="form-control" value="{{ old('sales_percentage', $split->sales_percentage) }}" min="0" max="100" required>
                        </div>
                        <div class="form-group">
                            <label>Admin Percentage (%)</label>
                            <input type="number" name="admin_percentage" class="form-control" value="{{ old('admin_percentage', $split->admin_percentage) }}" min="0" max="100" required>
                        </div>
                        <div class="form-group">
                            <label>User Feedback Percentage (%)</label>
                            <input type="number" name="feedback_percentage" class="form-control" value="{{ old('feedback_percentage', $split->feedback_percentage) }}" min="0" max="100" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
