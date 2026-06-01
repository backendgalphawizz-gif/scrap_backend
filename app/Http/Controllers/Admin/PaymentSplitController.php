<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSplit;
use App\Models\SalesCommissionSlab;
use App\Models\SalesCommissionSlabAudit;

class PaymentSplitController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────
    //  Payment Split (existing)
    // ─────────────────────────────────────────────────────────────────────

    public function edit()
    {
        $split = PaymentSplit::first();
        if (!$split) {
            $split = PaymentSplit::create([
                'user_percentage'          => 45,
                'sales_percentage'         => 20,
                'admin_percentage'         => 30,
                'feedback_percentage'      => 2,
                'repeat_brand_percentage'  => 0,
                'user_referral_percentage' => 0,
            ]);
        }

        $slabs      = SalesCommissionSlab::ordered();
        $slabIssues = SalesCommissionSlab::validateSlabs($slabs->toArray());

        return view('admin-views.business-settings.payment-split', compact('split', 'slabs', 'slabIssues'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_percentage'          => 'required|numeric|min:0',
            'sales_percentage'         => 'required|numeric|min:0',
            'admin_percentage'         => 'required|numeric|min:0',
            'feedback_percentage'      => 'required|numeric|min:0',
            'repeat_brand_percentage'  => 'required|numeric|min:0',
            'user_referral_percentage' => 'required|numeric|min:0',
        ]);

        $total = $request->user_percentage
            + $request->sales_percentage
            + $request->admin_percentage
            + $request->feedback_percentage
            + $request->repeat_brand_percentage
            + $request->user_referral_percentage;

        if ($total != 100) {
            return back()
                ->withErrors(['sum' => 'The sum of all six percentages must equal 100. Current total: ' . $total . '%.'])
                ->withInput();
        }

        $split = PaymentSplit::first();
        $split->update($request->only([
            'user_percentage',
            'sales_percentage',
            'admin_percentage',
            'feedback_percentage',
            'repeat_brand_percentage',
            'user_referral_percentage',
        ]));

        return back()->with('success', 'Payment split updated successfully.');
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Sales Commission Slabs (new)
    // ─────────────────────────────────────────────────────────────────────

    public function storeSlab(Request $request)
    {
        $data = $request->validate([
            'min_earning' => 'required|numeric|min:0',
            'max_earning' => 'nullable|numeric|gt:min_earning',
            'rate'        => 'required|numeric|min:0.01|max:100',
        ]);

        // Normalise empty string → null
        $data['max_earning'] = ($data['max_earning'] ?? '') !== '' ? $data['max_earning'] : null;

        $error = $this->checkSlabConflict(null, $data['min_earning'], $data['max_earning']);
        if ($error) {
            return back()->withErrors(['slab' => $error])->withInput()->with('open_slab_form', true);
        }

        $slab = SalesCommissionSlab::create($data);

        $this->auditSlab('created', $slab->id, $slab->toArray());

        return back()->with('slab_success', 'Commission slab added successfully.');
    }

    public function updateSlab(Request $request, int $id)
    {
        $slab = SalesCommissionSlab::findOrFail($id);

        $data = $request->validate([
            'min_earning' => 'required|numeric|min:0',
            'max_earning' => 'nullable|numeric|gt:min_earning',
            'rate'        => 'required|numeric|min:0.01|max:100',
        ]);

        $data['max_earning'] = ($data['max_earning'] ?? '') !== '' ? $data['max_earning'] : null;

        $error = $this->checkSlabConflict($id, $data['min_earning'], $data['max_earning']);
        if ($error) {
            return back()->withErrors(['slab_update_' . $id => $error])->withInput();
        }

        $before = $slab->toArray();
        $slab->update($data);

        $this->auditSlab('updated', $slab->id, ['before' => $before, 'after' => $slab->fresh()->toArray()]);

        return back()->with('slab_success', 'Commission slab updated successfully.');
    }

    public function destroySlab(int $id)
    {
        $slab = SalesCommissionSlab::findOrFail($id);
        $snapshot = $slab->toArray();
        $slab->delete();

        $this->auditSlab('deleted', $id, $snapshot);

        return back()->with('slab_success', 'Commission slab deleted. Existing campaigns retain their original rate.');
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Check whether a proposed [min, max) range conflicts with any existing slab.
     * Returns an error string or null if there is no conflict.
     */
    private function checkSlabConflict(?int $excludeId, float $min, ?float $max): ?string
    {
        $query = SalesCommissionSlab::query();
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        foreach ($query->get() as $existing) {
            $eMin = (float) $existing->min_earning;
            $eMax = $existing->max_earning !== null ? (float) $existing->max_earning : PHP_FLOAT_MAX;

            $proposedMax = $max !== null ? $max : PHP_FLOAT_MAX;

            // Two ranges [a,b) and [c,d) overlap when a < d AND c < b
            if ($min < $eMax && $eMin < $proposedMax) {
                return 'This slab range overlaps with an existing slab (₹' . number_format($eMin, 2)
                    . ' – ' . ($existing->max_earning !== null ? '₹' . number_format((float)$existing->max_earning, 2) : '∞') . ').';
            }
        }

        return null;
    }

    private function auditSlab(string $action, ?int $slabId, ?array $data): void
    {
        SalesCommissionSlabAudit::create([
            'action'       => $action,
            'slab_id'      => $slabId,
            'slab_data'    => $data,
            'performed_by' => auth('admin')->user()?->name ?? 'admin',
        ]);
    }
}
