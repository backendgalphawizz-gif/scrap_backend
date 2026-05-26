<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CampaignTransaction;
use App\Models\CoinTransaction;
use App\Models\SaleCommissionLedger;
use App\Models\SaleWalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $search = trim((string) $request->get('search', ''));

        if ($type === 'user_wallet') {
            $transactions = $this->transformPaginator(
                $this->userWalletQuery($request)->paginate(25)->withQueryString(),
                fn($row) => $this->normalizeUserWallet($row)
            );
            return view('admin-views.transactions.index', compact('transactions', 'type'));
        }

        if ($type === 'sale_wallet') {
            $transactions = $this->transformPaginator(
                $this->saleWalletQuery($request)->paginate(25)->withQueryString(),
                fn($row) => $this->normalizeSaleWallet($row)
            );
            return view('admin-views.transactions.index', compact('transactions', 'type'));
        }

        if ($type === 'commission_ledger') {
            $transactions = $this->transformPaginator(
                $this->commissionLedgerQuery($request)->paginate(25)->withQueryString(),
                fn($row) => $this->normalizeCommissionLedger($row)
            );
            return view('admin-views.transactions.index', compact('transactions', 'type'));
        }

        if ($type === 'campaign') {
            $transactions = $this->transformPaginator(
                $this->campaignQuery($request)->paginate(25)->withQueryString(),
                fn($row) => $this->normalizeCampaign($row)
            );
            return view('admin-views.transactions.index', compact('transactions', 'type'));
        }

        $transactions = $this->paginateMergedTransactions(
            $this->buildMergedTransactions($request),
            $request
        );

        return view('admin-views.transactions.index', compact('transactions', 'type'));
    }

    private function userWalletQuery(Request $request)
    {
        return CoinTransaction::with(['wallet.user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('wallet.user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->orderByDesc('id');
    }

    private function saleWalletQuery(Request $request)
    {
        return SaleWalletTransaction::with(['sale'])
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
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->orderByDesc('id');
    }

    private function commissionLedgerQuery(Request $request)
    {
        return SaleCommissionLedger::with(['sale', 'brand', 'campaign'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('id', $search)
                        ->orWhere('reference_type', 'like', "%{$search}%")
                        ->orWhereHas('sale', fn($saleQuery) => $saleQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('brand', fn($brandQuery) => $brandQuery->where('username', 'like', "%{$search}%"))
                        ->orWhereHas('campaign', fn($campaignQuery) => $campaignQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->orderByDesc('id');
    }

    private function campaignQuery(Request $request)
    {
        return CampaignTransaction::with(['campaign.brand', 'user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('unique_code', 'like', "%{$search}%")
                        ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('campaign', fn($campaignQuery) => $campaignQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
            ->orderByDesc('id');
    }

    private function buildMergedTransactions(Request $request): Collection
    {
        $perSource = 150;
        $search = trim((string) $request->get('search', ''));
        $items = collect();

        $coinQuery = $this->userWalletQuery($request);
        foreach ($coinQuery->limit($perSource)->get() as $row) {
            $items->push($this->normalizeUserWallet($row));
        }

        $saleQuery = $this->saleWalletQuery($request);
        foreach ($saleQuery->limit($perSource)->get() as $row) {
            $items->push($this->normalizeSaleWallet($row));
        }

        $ledgerQuery = $this->commissionLedgerQuery($request);
        foreach ($ledgerQuery->limit($perSource)->get() as $row) {
            $items->push($this->normalizeCommissionLedger($row));
        }

        $campaignQuery = $this->campaignQuery($request);
        foreach ($campaignQuery->limit($perSource)->get() as $row) {
            $items->push($this->normalizeCampaign($row));
        }

        if ($search !== '') {
            $needle = mb_strtolower($search);
            $items = $items->filter(function ($item) use ($needle) {
                return str_contains(mb_strtolower($item['reference']), $needle)
                    || str_contains(mb_strtolower($item['party']), $needle)
                    || str_contains(mb_strtolower($item['category']), $needle)
                    || str_contains(mb_strtolower($item['details']), $needle);
            });
        }

        return $items->sortByDesc('sort_at')->values();
    }

    private function transformPaginator(LengthAwarePaginator $paginator, callable $normalizer): LengthAwarePaginator
    {
        $paginator->setCollection(
            $paginator->getCollection()->map($normalizer)->values()
        );

        return $paginator;
    }

    private function paginateMergedTransactions(Collection $items, Request $request): LengthAwarePaginator
    {
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 25;
        $total = $items->count();
        $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    private function normalizeUserWallet(CoinTransaction $row): array
    {
        return [
            'key' => 'user_wallet-' . $row->id,
            'category' => 'User Wallet',
            'reference' => $row->transaction_id ?: ('#' . $row->id),
            'party' => $row->wallet->user->name ?? 'N/A',
            'amount' => $row->coin,
            'amount_label' => 'Coins',
            'flow' => $row->type,
            'status' => $row->status,
            'details' => $row->description ?: ($row->transaction_type ?? '-'),
            'date' => Helpers::formatAdminDateTime($row->created_at),
            'sort_at' => $row->created_at ?? Carbon::now(),
            'link' => route('admin.user.wallet', ['search' => $row->transaction_id]),
        ];
    }

    private function normalizeSaleWallet(SaleWalletTransaction $row): array
    {
        return [
            'key' => 'sale_wallet-' . $row->id,
            'category' => 'Sale Wallet',
            'reference' => '#' . $row->id,
            'party' => $row->sale->name ?? 'N/A',
            'amount' => $row->amount,
            'amount_label' => 'INR',
            'flow' => $row->type,
            'status' => $row->status,
            'details' => $row->remarks ?? '-',
            'date' => Helpers::formatAdminDateTime($row->created_at),
            'sort_at' => $row->created_at ?? Carbon::now(),
            'link' => route('admin.sale.wallet-transactions', ['search' => $row->id]),
        ];
    }

    private function normalizeCommissionLedger(SaleCommissionLedger $row): array
    {
        return [
            'key' => 'commission-' . $row->id,
            'category' => 'Commission Ledger',
            'reference' => '#' . $row->id,
            'party' => $row->sale->name ?? ($row->brand->username ?? 'N/A'),
            'amount' => $row->commission_amount,
            'amount_label' => 'Commission',
            'flow' => $row->reference_type ?? '-',
            'status' => $row->status,
            'details' => ($row->campaign->title ?? 'N/A') . ' / ' . ($row->brand->username ?? 'N/A'),
            'date' => Helpers::formatAdminDateTime($row->created_at),
            'sort_at' => $row->created_at ?? Carbon::now(),
            'link' => route('admin.sale.ledger-transactions', ['search' => $row->id]),
        ];
    }

    private function normalizeCampaign(CampaignTransaction $row): array
    {
        return [
            'key' => 'campaign-' . $row->id,
            'category' => 'Campaign Participation',
            'reference' => $row->unique_code ?: ('#' . $row->id),
            'party' => $row->user->name ?? 'N/A',
            'amount' => $row->earning,
            'amount_label' => 'Coins',
            'flow' => $row->shared_on ?? '-',
            'status' => $row->status,
            'details' => ($row->campaign->title ?? 'N/A') . ' / ' . ($row->campaign->brand->username ?? 'N/A'),
            'date' => Helpers::formatAdminDateTime($row->created_at),
            'sort_at' => $row->created_at ?? Carbon::now(),
            'link' => route('admin.campaigns-transactions.list', ['search' => $row->unique_code, 'user_id' => $row->user_id]),
        ];
    }
}
