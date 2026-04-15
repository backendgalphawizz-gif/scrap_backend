<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use App\Models\BusinessSetting;
use App\Models\Campaign;
use App\Models\CoinTransaction;
use App\Models\CampaignTransaction;
use App\Models\Seller;
use App\Models\User;
use App\Http\Resources\CommonResource;
use DB;
use App\CPU\ImageManager;
use Spatie\Activitylog\Models\Activity;

class ReportController extends Controller
{
    public function index(Request $request) {
        $date_type = request('date_type') ?? 'this_year';
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        switch ($date_type) {
            case 'today':
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                break;

            case 'this_week':
                $from = now()->startOfWeek();
                $to = now()->endOfWeek();
                break;

            case 'this_month':
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
                break;

            case 'this_year':
                $from = now()->startOfYear();
                $to = now()->endOfYear();
                break;

            case 'custom_date':
                $from = request('from');
                $to = request('to');
                break;

            default:
                $from = now()->startOfYear();
                $to = now()->endOfYear();
        }
        
        $limit = request('limit') ?? 10;
        $brands = Seller::whereBetween('created_at', [$from, $to])->paginate($limit);
        $brandsCount = Seller::whereBetween('created_at', [$from, $to])->count();
        $campaignCount = Campaign::whereBetween('created_at', [$from, $to])->count();

        $data['rejected_product'] = Campaign::whereBetween('created_at', [$from, $to])
            ->where('status', 'inactive')->count();
        $data['pending_product'] = Campaign::whereBetween('created_at', [$from, $to])
            ->where('status', 'pending')->count();
        $data['active_product'] = Campaign::whereBetween('created_at', [$from, $to])
            ->where('status', 'active')->count();

        return view('admin-views.report._seller-earning',compact('data', 'campaignCount', 'brandsCount', 'brands', 'date_type','from', 'to'));
    }

    public function campaignReport(Request $request) {
        $date_type = request('date_type') ?? 'this_year';
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        $limit = request('limit') ?? 10;
        switch ($date_type) {
            case 'today':
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                break;

            case 'this_week':
                $from = now()->startOfWeek();
                $to = now()->endOfWeek();
                break;

            case 'this_month':
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
                break;

            case 'this_year':
                $from = now()->startOfYear();
                $to = now()->endOfYear();
                break;

            case 'custom_date':
                $from = request('from');
                $to = request('to');
                break;

            default:
                $from = now()->startOfYear();
                $to = now()->endOfYear();
        }

        $campaigns = Campaign::whereBetween('created_at', [$from, $to])
            ->withCount([
                'campaign_transactions as participants',
                'campaign_transactions as approved_posts' => function($q){
                    $q->where('status','approved');
                },
                'campaign_transactions as rejected_posts' => function($q){
                    $q->where('status','rejected');
                }
            ])
            ->paginate($limit);

        $data = [
            'totalCampaigns' => Campaign::whereBetween('created_at', [$from, $to])->count(),
            'liveCampaigns' => Campaign::whereBetween('created_at', [$from, $to])->where('status','active')->count(),
            'completedCampaigns' => Campaign::whereBetween('created_at', [$from, $to])->where('status','completed')->count(),
            'totalParticipants' => CampaignTransaction::whereBetween('created_at', [$from, $to])->count(),
            'approvedPosts' => CampaignTransaction::whereBetween('created_at', [$from, $to])->where('status','approved')->count(),
            'rejectedPosts' => CampaignTransaction::whereBetween('created_at', [$from, $to])->where('status','rejected')->count()
        ];

        return view('admin-views.report._campaign-reports', compact('data', 'campaigns', 'date_type', 'from', 'to'));
    }

    public function postReport(Request $request) {
        $date_type = request('date_type') ?? 'this_year';
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        $limit = request('limit') ?? 10;
        switch ($date_type) {
            case 'today':
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                break;

            case 'this_week':
                $from = now()->startOfWeek();
                $to = now()->endOfWeek();
                break;

            case 'this_month':
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
                break;

            case 'this_year':
                $from = now()->startOfYear();
                $to = now()->endOfYear();
                break;

            case 'custom_date':
                $from = request('from');
                $to = request('to');
                break;

            default:
                $from = now()->startOfYear();
                $to = now()->endOfYear();
        }
        
        // Total Engagements
        $data = [
            'total_posts' => CampaignTransaction::count(),
            'approved_posts' => CampaignTransaction::where('status','approved')->count(),
            'rejected_posts' => CampaignTransaction::where('status','rejected')->count(),
            'pending_posts' => CampaignTransaction::where('status','pending')->count(),
            'instagram_posts' => CampaignTransaction::where('shared_on','instagram')->count(),
            'facebook_posts' => CampaignTransaction::where('shared_on','facebook')->count()
        ];

        $posts = CampaignTransaction::with(['campaign','user'])
            ->select(
                'campaign_id',
                'user_id',
                'post_url',
                'status',
                'shared_on',
                'likes',
                'comments',
                'created_at'
            )
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        return view('admin-views.report._post-reports', compact('data', 'posts', 'date_type', 'from', 'to'));
    }

    public function activityLogs(Request $request) {

        $date_type = request('date_type') ?? 'this_year';
        $from = date('Y-m-d');
        $to = date('Y-m-d');
        $limit = request('limit') ?? 20;
        $search = request('search') ?? '';

        switch ($date_type) {
            case 'today':
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                break;

            case 'this_week':
                $from = now()->startOfWeek();
                $to = now()->endOfWeek();
                break;

            case 'this_month':
                $from = now()->startOfMonth();
                $to = now()->endOfMonth();
                break;

            case 'this_year':
                $from = now()->startOfYear();
                $to = now()->endOfYear();
                break;

            case 'custom_date':
                $from = request('from');
                $to = request('to');
                break;

            default:
                $from = now()->startOfYear();
                $to = now()->endOfYear();
        }

        $logs = Activity::with('causer')
            ->whereBetween('created_at', [$from, $to])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                        ->orWhere('log_name', 'like', "%{$search}%")
                        ->orWhereHas('causer', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        return view('admin-views.report._activity-logs', compact('logs', 'date_type', 'from', 'to', 'search', 'limit'));
    }

}
