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
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $sales = Sale::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    // $q->Where('banner_type', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $sales = Sale::orderBy('id', 'desc');
        }
        $sales = $sales->paginate(25)->appends($query_param);

        return view('admin-views.sale.view', compact('sales', 'search'));
    }

    public function create()
    {
        return view('admin-views.sale.add');
    }

    public function store(Request $request)
    {
        $campaign = new Sale;
        if($request->hasFile('image')) {
            $campaign->image = ImageManager::upload('profile/', 'png', $request->file('image'));
        }
        $campaign->name = $request->name;
        $campaign->email = $request->email;
        $campaign->mobile = $request->mobile;
        $campaign->save();

        Helpers::systemActivity('sale', auth()->user(), 'deleted', 'Sale Account created by admin', $campaign);

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
        $transactions = SaleWalletTransaction::with(['sale'])->orderBy('id', 'desc')->paginate(25);
        return view('admin-views.sale.wallet-transactions', compact('transactions'));
    }

    public function ledgerTransactions(Request $request)
    {
        $transactions = SaleCommissionLedger::with(['sale', 'brand','campaign'])->orderBy('id', 'desc')->paginate(25);
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
