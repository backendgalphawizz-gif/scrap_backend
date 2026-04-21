<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentSplit;

class PaymentSplitController extends Controller
{
    public function edit()
    {
        $split = PaymentSplit::first();
        if (!$split) {
            $split = PaymentSplit::create([
                'user_percentage' => 50,
                'sales_percentage' => 20,
                'admin_percentage' => 30,
            ]);
        }
        return view('admin-views.business-settings.payment-split', compact('split'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'user_percentage' => 'required|integer|min:0',
            'sales_percentage' => 'required|integer|min:0',
            'admin_percentage' => 'required|integer|min:0',
        ]);
        $total = $request->user_percentage + $request->sales_percentage + $request->admin_percentage;
        if ($total !== 100) {
            return back()->withErrors(['sum' => 'The sum of all percentages must be 100.'])->withInput();
        }
        $split = PaymentSplit::first();
        $split->update($request->only(['user_percentage', 'sales_percentage', 'admin_percentage']));
        return back()->with('success', 'Payment split updated successfully.');
    }
}
