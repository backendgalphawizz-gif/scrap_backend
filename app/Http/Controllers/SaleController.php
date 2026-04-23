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
        $sale = new Sale;
        if($request->hasFile('image')) {
            $sale->image = ImageManager::upload('profile/', 'png', $request->file('image'));
        }
        $sale->name = $request->name;
        $sale->email = $request->email;
        $sale->mobile = $request->mobile;
        $sale->save();

        // Set referral_code as RXS-{id} after save
        $sale->referral_code = 'RXS-' . $sale->id;
        $sale->save();

        Helpers::systemActivity('sale', auth()->user(), 'deleted', 'Sale Account created by admin', $sale);

        return redirect()->route('admin.sale.list');
    }

    public function show($id)
    {
        $sale = Sale::with(['brands'])->where('id', $id)->first();
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
        $sale = Sale::find($id);
        return view('admin-views.sale.edit', compact('sale'));
    }

    public function update(Request $request, $id)
    {

        $sale = Sale::find($id);
        if($request->hasFile('image')) {
            $sale->image = ImageManager::upload('profile/', 'png', $request->file('image'));
        }
        $sale->name = $request->name;
        $sale->email = $request->email;
        $sale->mobile = $request->mobile;
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
