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
                            <input type="number" name="user_percentage" class="form-control split-input" value="{{ old('user_percentage', $split->user_percentage) }}" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Sales Percentage (%)</label>
                            <input type="number" name="sales_percentage" class="form-control split-input" value="{{ old('sales_percentage', $split->sales_percentage) }}" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Admin Percentage (%)</label>
                            <input type="number" name="admin_percentage" class="form-control split-input" value="{{ old('admin_percentage', $split->admin_percentage) }}" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>User Feedback Percentage (%)</label>
                            <input type="number" name="feedback_percentage" class="form-control split-input" value="{{ old('feedback_percentage', $split->feedback_percentage) }}" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Sales Repeat Brand Percentage (%)</label>
                            <input type="number" name="repeat_brand_percentage" class="form-control split-input" value="{{ old('repeat_brand_percentage', $split->repeat_brand_percentage) }}" min="0" max="100" step="0.01" required>
                            <small class="text-muted">Bonus commission when a salesperson brings the same brand a second time within 100 days.</small>
                        </div>
                        <div class="form-group">
                            <label>User Referral Percentage (%)</label>
                            <input type="number" name="user_referral_percentage" class="form-control split-input" value="{{ old('user_referral_percentage', $split->user_referral_percentage) }}" min="0" max="100" step="0.01" required>
                            <small class="text-muted">Coin bonus paid to the referrer when a referred user's campaign post is approved (capped at max 2%).</small>
                        </div>
                        <div class="alert alert-info mt-2 p-2">
                            <strong>Total:</strong>
                            <span id="split-total">{{ ($split->user_percentage + $split->sales_percentage + $split->admin_percentage + $split->feedback_percentage + $split->repeat_brand_percentage + $split->user_referral_percentage) }}</span>%
                            &nbsp;(must equal 100%)
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
    document.querySelectorAll('.split-input').forEach(function(input) {
        input.addEventListener('input', function() {
            var total = 0;
            document.querySelectorAll('.split-input').forEach(function(i) {
                total += parseFloat(i.value) || 0;
            });
            var el = document.getElementById('split-total');
            el.textContent = Math.round(total * 100) / 100;
            el.closest('.alert').className = 'alert mt-2 p-2 ' + (Math.abs(total - 100) < 0.01 ? 'alert-success' : 'alert-danger');
        });
    });
</script>
@endpush
@endsection
