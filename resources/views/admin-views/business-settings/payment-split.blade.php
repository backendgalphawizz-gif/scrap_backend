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

{{-- ─── Sales Commission Slabs ─────────────────────────────────────────── --}}
<div class="row justify-content-left mb-5 ml-5">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-0">Sales Commission Slabs</h4>
                    <small class="text-muted">
                        Stepped commission rates applied on campaign completion based on the salesperson's
                        cumulative approved earnings. Only the slab matching the salesperson's
                        <strong>new total</strong> after the current payout is used.
                    </small>
                </div>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSlabModal">
                    + Add Slab
                </button>
            </div>
            <div class="card-body">

                @if(session('slab_success'))
                    <div class="alert alert-success">{{ session('slab_success') }}</div>
                @endif

                @if($errors->has('slab'))
                    <div class="alert alert-danger">{{ $errors->first('slab') }}</div>
                @endif

                {{-- Gaps / Overlap warnings --}}
                @if(!empty($slabIssues['overlaps']))
                    <div class="alert alert-danger">
                        <strong>Overlap detected:</strong>
                        <ul class="mb-0">
                            @foreach($slabIssues['overlaps'] as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(!empty($slabIssues['gaps']))
                    <div class="alert alert-warning">
                        <strong>Gap in coverage:</strong>
                        <ul class="mb-0">
                            @foreach($slabIssues['gaps'] as $msg)
                                <li>{{ $msg }}</li>
                            @endforeach
                        </ul>
                        <small>If a salesperson's earnings fall in a gap, the campaign's snapshotted <code>sales_percentage</code> is used as fallback.</small>
                    </div>
                @endif

                @if($slabs->isEmpty())
                    <div class="alert alert-info">
                        No commission slabs configured yet. The campaign-level <strong>Sales&nbsp;Percentage</strong>
                        (from the Payment Split above) will be used as a flat rate. Add slabs to enable tiered commissions.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Min Earning (₹)</th>
                                    <th>Max Earning (₹)</th>
                                    <th>Commission Rate (%)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slabs as $slab)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>₹{{ number_format($slab->min_earning, 2) }}</td>
                                    <td>
                                        @if($slab->max_earning !== null)
                                            ₹{{ number_format($slab->max_earning, 2) }}
                                        @else
                                            <span class="badge bg-secondary">No limit (∞)</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ number_format($slab->rate, 2) }}%</strong></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSlabModal{{ $slab->id }}">
                                            Edit
                                        </button>
                                        <form method="POST"
                                            action="{{ route('admin.payment-split.slab.destroy', $slab->id) }}"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete this slab? Campaigns already settled keep their original commission rate.');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editSlabModal{{ $slab->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.payment-split.slab.update', $slab->id) }}">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Slab</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($errors->has('slab_update_' . $slab->id))
                                                        <div class="alert alert-danger">{{ $errors->first('slab_update_' . $slab->id) }}</div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label>Min Earning (₹) <span class="text-danger">*</span></label>
                                                        <input type="number" name="min_earning" class="form-control"
                                                            value="{{ old('min_earning', $slab->min_earning) }}"
                                                            min="0" step="0.01" required>
                                                        <small class="text-muted">Inclusive lower bound for this slab.</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Max Earning (₹)</label>
                                                        <input type="number" name="max_earning" class="form-control"
                                                            value="{{ old('max_earning', $slab->max_earning) }}"
                                                            min="0" step="0.01"
                                                            placeholder="Leave blank for no upper limit (last slab)">
                                                        <small class="text-muted">Exclusive upper bound. Leave blank for the final open-ended slab.</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Commission Rate (%) <span class="text-danger">*</span></label>
                                                        <input type="number" name="rate" class="form-control"
                                                            value="{{ old('rate', $slab->rate) }}"
                                                            min="0.01" max="100" step="0.01" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Slab</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- /Edit Modal --}}

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-light border mt-2">
                        <strong>How it works:</strong>
                        When a campaign is settled, the salesperson's total approved earnings
                        (sum of all past approved <em>campaign_reward</em> commission amounts) plus
                        the current payout base is compared against these slabs. The rate from the
                        matching slab is used. If no slab matches, the campaign's snapshotted
                        Sales&nbsp;Percentage is used as fallback.
                        <br><br>
                        <strong>Example:</strong> Slab 1 → ₹0 – ₹50,000 @ 10% &nbsp;|&nbsp;
                        Slab 2 → ₹50,000+ @ 6%. A salesperson with ₹48,000 in earnings settling
                        a ₹5,000 campaign would have a new total of ₹53,000, which falls in Slab 2
                        → commission rate = <strong>6%</strong>.
                    </div>
                @endif
            </div>
        </div>
    </div>
{{-- Add Slab Modal --}}
<div class="modal fade" id="addSlabModal" tabindex="-1" aria-labelledby="addSlabModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.payment-split.slab.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSlabModalLabel">Add Commission Slab</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Min Earning (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="min_earning" class="form-control"
                            value="{{ old('min_earning', 0) }}" min="0" step="0.01" required>
                        <small class="text-muted">Inclusive lower bound. e.g. <code>0</code> for the first slab, <code>50000</code> for the next.</small>
                    </div>
                    <div class="form-group">
                        <label>Max Earning (₹)</label>
                        <input type="number" name="max_earning" class="form-control"
                            value="{{ old('max_earning') }}" min="0" step="0.01"
                            placeholder="Leave blank for no upper limit (last slab)">
                        <small class="text-muted">Exclusive upper bound. Leave blank if this is the final open-ended slab.</small>
                    </div>
                    <div class="form-group">
                        <label>Commission Rate (%) <span class="text-danger">*</span></label>
                        <input type="number" name="rate" class="form-control"
                            value="{{ old('rate') }}" min="0.01" max="100" step="0.01" required
                            placeholder="e.g. 10">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Slab</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- /Add Slab Modal --}}
</div>{{-- /div.content.container-fluid --}}

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

    // Auto-open Add Slab modal if validation returned an error
    @if(session('open_slab_form'))
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('addSlabModal');
        if (el) { new bootstrap.Modal(el).show(); }
    });
    @endif
</script>
@endpush
@endsection
