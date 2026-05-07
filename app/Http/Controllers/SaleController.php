<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleWalletTransaction;
use App\Models\SaleCommissionLedger;
// use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    function list(Request $request)
    {
        $sales = Sale::query()
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->name) . '%');
            })
            ->when($request->filled('mobile'), function ($query) use ($request) {
                $query->where('mobile', 'like', '%' . trim($request->mobile) . '%');
            })
            ->when($request->filled('email'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . trim($request->email) . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin-views.sale.view', compact('sales'));
    }

    public function create()
    {
        return view('admin-views.sale.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:40', 'regex:/^[a-zA-Z ]+$/', 'regex:/^(?!.*(.)(\1{3,})).*/'],
            'email'    => ['required', 'email', 'max:150', 'unique:sales,email'],
            'mobile'   => ['required', 'digits:10'],
            'password' => ['required', 'string', 'min:8', 'max:32', 'confirmed'],
            'image'    => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ], [
            'name.regex'   => 'Name must contain only letters and spaces, with no repeated characters.',
            'mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'email.unique' => 'This email is already registered.',
        ]);

        $sale = new Sale;
        if ($request->hasFile('image')) {
            $sale->image = ImageManager::upload('profile/', 'png', $request->file('image'));
        }
        $sale->name     = $request->name;
        $sale->email    = $request->email;
        $sale->mobile   = $request->mobile;
        $sale->password = bcrypt($request->password);
        $sale->save();

        // Set referral_code as RXS-{id} after save
        $sale->referral_code = 'RXS-' . $sale->id;
        $sale->save();

        Helpers::systemActivity('sale', auth()->user(), 'deleted', 'Sale Account created by admin', $sale);

        return redirect()->route('admin.sale.list');
    }

    public function show($id)
    {
        $sale = Sale::with(['brands'])
            ->withCount(['brands', 'campaigns'])
            ->findOrFail($id);
        return view('admin-views.sale.show', compact('sale'));
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $sale = Sale::find($request->id);
            $sale->status = $request->status == 1 ? 'active' : 'inactive';
            $sale->save();
            $data = $request->status;

            Helpers::systemActivity('sale', auth()->user(), 'update', 'Sale Account status updated to ' . $sale->status, $sale);

            return response()->json($data);
        }
    }

    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $bankDetail = (array) ($sale->bank_detail ?? []);

        return view('admin-views.sale.edit', compact('sale', 'bankDetail'));
    }

    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|in:pending,active,inactive,blocked',
            'pan_number' => 'nullable|string|max:10',
            'pan_status' => 'nullable|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
            'pan_rejection_reason' => 'nullable|string|max:1000',
            'bank_status' => 'nullable|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
            'bank_rejection_reason' => 'nullable|string|max:1000',
            'kyc_status' => 'nullable|in:pending,verified,rejected',
            'kyc_rejection_reason' => 'nullable|string|max:1000',
            'bank_name' => 'nullable|string|max:120',
            'account_number' => 'nullable|string|max:60',
            'ifsc_code' => 'nullable|string|max:30',
            'branch_name' => 'nullable|string|max:120',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'pan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $sale->image = ImageManager::upload('profile/', 'png', $request->file('image'));
        }

        if ($request->hasFile('pan_image')) {
            $sale->pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'));
        }

        $sale->name = $request->name;
        $sale->status = $request->status;
        $sale->pan_number = $request->pan_number;
        $sale->pan_status = $request->pan_status ?? $sale->pan_status;
        $sale->pan_rejection_reason = $request->pan_rejection_reason;
        $sale->bank_status = $request->bank_status ?? $sale->bank_status;
        $sale->bank_rejection_reason = $request->bank_rejection_reason;
        $sale->kyc_status = $request->kyc_status ?? $sale->kyc_status;
        $sale->kyc_rejection_reason = $request->kyc_rejection_reason;
        $sale->bank_detail = json_encode([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'branch_name' => $request->branch_name,
        ]);
        $sale->save();

        Helpers::systemActivity('sale', auth()->user(), 'updated', 'Sale profile updated by admin', $sale);

        return redirect()->route('admin.sale.list');
        
    }

    public function delete(Request $request)
    {
        $br = Sale::find($request->id);
        Sale::where('id', $request->id)->delete();

        Helpers::systemActivity('sale', auth()->user(), 'deleted', 'Sale Account deleted by admin', $br);

        return response()->json();
    }

    public function campaignTransctions(Request $request)
    {
        $transactions = CampaignTransaction::with(['campaign.brand', 'user'])->orderBy('id', 'desc')->paginate(25);
        return view('admin-views.campaign.transactions', compact('transactions'));
    }
    
    public function walletTransactions(Request $request)
    {
        $transactions = SaleWalletTransaction::with(['sale'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('id', $search)
                        ->orWhere('remarks', 'like', "%{$search}%")
                        ->orWhereHas('sale', function ($saleQuery) use ($search) {
                            $saleQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('mobile', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->filled('amount_min'), function ($query) use ($request) {
                $query->where('amount', '>=', $request->amount_min);
            })
            ->when($request->filled('amount_max'), function ($query) use ($request) {
                $query->where('amount', '<=', $request->amount_max);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin-views.sale.wallet-transactions', compact('transactions'));
    }

    public function ledgerTransactions(Request $request)
    {
        $transactions = SaleCommissionLedger::with(['sale', 'brand', 'campaign'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('id', $search)
                        ->orWhere('reference_type', 'like', "%{$search}%")
                        ->orWhereHas('sale', function ($saleQuery) use ($search) {
                            $saleQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('brand', function ($brandQuery) use ($search) {
                            $brandQuery->where('username', 'like', "%{$search}%");
                        })
                        ->orWhereHas('campaign', function ($campaignQuery) use ($search) {
                            $campaignQuery->where('title', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('reference_type'), function ($query) use ($request) {
                $query->where('reference_type', $request->reference_type);
            })
            ->when($request->filled('amount_min'), function ($query) use ($request) {
                $query->where('amount', '>=', $request->amount_min);
            })
            ->when($request->filled('amount_max'), function ($query) use ($request) {
                $query->where('amount', '<=', $request->amount_max);
            })
            ->when($request->filled('commission_min'), function ($query) use ($request) {
                $query->where('commission_amount', '>=', $request->commission_min);
            })
            ->when($request->filled('commission_max'), function ($query) use ($request) {
                $query->where('commission_amount', '<=', $request->commission_max);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->orderBy('id', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('admin-views.sale.ledger-transactions', compact('transactions'));
    }

    public function updateLedgerTransactionStatus(Request $request) {
        $id = $request->id;
        $status = $request->status;

        $ledger = SaleCommissionLedger::find($id);
        $ledger->status = $status;
        $ledger->save();

        $message = $ledger->reference_type == 'campaign_budget' ? 'Amount Credit for campaign approved' : 'Amount Credit for brand approved';

        Helpers::systemActivity('sale', auth()->user(), 'deleted', $message, $ledger);

        if($status == 'approved') {
            SaleWalletTransaction::create([
                'sale_id' => $ledger->sale_id,
                'amount' => $ledger->commission_amount,
                'type' => 'credit',
                'remarks' => $message,
                'status' => 'success'
            ]);

            $sale = Sale::find($ledger->sale_id);
            $sale->balance += $ledger->commission_amount;
            $sale->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Status updated success'
        ]);

    }

}
