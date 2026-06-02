<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\FraudSignal;
use App\Models\User;
use App\Models\CoinWallet;
use App\Services\FraudScoreService;
use Illuminate\Http\Request;

class FraudController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->where('fraud_status', '!=', 'clean')
            ->when($request->filled('fraud_status'), fn($q) => $q->where('fraud_status', $request->fraud_status))
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('last_fraud_check_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('last_fraud_check_at', '<=', $request->date_to))
            ->when($request->filled('search'), fn($q) => $q->where(function ($q2) use ($request) {
                $q2->where('name', 'like', '%' . $request->search . '%')
                   ->orWhere('mobile', 'like', '%' . $request->search . '%')
                   ->orWhere('email', 'like', '%' . $request->search . '%');
            }))
            ->withCount(['fraudSignals as active_signals_count' => fn($q) => $q->unresolved()])
            ->with(['coinWallet:id,user_id,balance,withdrawal_frozen'])
            ->orderByDesc('fraud_score')
            ->paginate(25)
            ->withQueryString();

        return view('admin-views.fraud.index', compact('users'));
    }

    public function show(int $id)
    {
        $user = User::with([
            'fraudSignals' => fn($q) => $q->orderByDesc('created_at'),
            'coinWallet:id,user_id,balance,withdrawal_frozen',
        ])->findOrFail($id);

        return view('admin-views.fraud.show', compact('user'));
    }

    public function resolveSignal(Request $request, int $signalId)
    {
        $signal = FraudSignal::findOrFail($signalId);

        $signal->update([
            'resolved'    => true,
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
        ]);

        app(FraudScoreService::class)->recalculate($signal->user);

        return redirect()->back()->with('success', 'Signal resolved. Fraud score recalculated.');
    }

    public function blockUser(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $user->update(['fraud_status' => 'blocked']);

        CoinWallet::where('user_id', $id)->update(['withdrawal_frozen' => true]);

        FraudSignal::create([
            'user_id'      => $id,
            'signal_type'  => FraudSignal::TYPE_MANUAL_BLOCK,
            'signal_value' => 'admin:' . auth()->id(),
            'severity'     => FraudSignal::SEVERITY_CRITICAL,
            'meta'         => ['blocked_by' => auth()->user()->f_name ?? 'Admin', 'reason' => $request->reason],
        ]);

        app(FraudScoreService::class)->recalculate($user->fresh());

        return redirect()->back()->with('success', 'User blocked and wallet frozen.');
    }

    public function clearUser(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        FraudSignal::where('user_id', $id)
            ->where('resolved', false)
            ->update([
                'resolved'    => true,
                'resolved_at' => now(),
                'resolved_by' => auth()->id(),
            ]);

        $user->update([
            'fraud_score'          => 0,
            'fraud_status'         => 'clean',
            'last_fraud_check_at'  => now(),
        ]);

        CoinWallet::where('user_id', $id)->update(['withdrawal_frozen' => false]);

        return redirect()->back()->with('success', 'User cleared. All signals resolved and wallet unfrozen.');
    }
}
