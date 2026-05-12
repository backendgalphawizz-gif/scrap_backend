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
        
        $limit = (int) (request('limit') ?? 10);
        $campaignQuery = Campaign::with(['brand:id,username'])
            ->whereBetween('created_at', [$from, $to], 'and', false);

        $campaignCount = (clone $campaignQuery)->count();
        $brandsCount = (clone $campaignQuery)->distinct('brand_id')->count('brand_id');

        $data['rejected_product'] = (clone $campaignQuery)->where('status', 'inactive')->count();
        $data['pending_product'] = (clone $campaignQuery)->where('status', 'pending')->count();
        $data['active_product'] = (clone $campaignQuery)->where('status', 'active')->count();

        $mapCampaignRow = function ($campaign) {
            $baseAmount = (float) ($campaign->total_campaign_budget ?? 0);
            $amountWithGst = (float) ($campaign->compign_budget_with_gst ?? $baseAmount);
            $gstAmount = max(0, $amountWithGst - $baseAmount);

            $userPercentage = (float) ($campaign->user_percentage ?? 0);
            $salesPercentage = (float) ($campaign->sales_percentage ?? 0);
            $referralPercentage = (float) ($campaign->feedback_percentage ?? 0);
            $adminPercentage = (float) ($campaign->admin_percentage ?? 0);

            $discountPercentage = max(0, 100 - ($userPercentage + $salesPercentage + $referralPercentage + $adminPercentage));
            $discountAmount = ($baseAmount * $discountPercentage) / 100;
            $amountWithoutGst = $baseAmount - $discountAmount;

            return [
                'brand' => $campaign->brand->username ?? '-',
                'campaign' => $campaign->unique_code ?? ('RXC_' . str_pad((string) $campaign->id, 5, '0', STR_PAD_LEFT)),
                'amount_with_gst' => $amountWithGst,
                'gst' => $gstAmount,
                'amount_without_gst' => $amountWithoutGst,
                'amount_without_gst_with_discount' => $baseAmount,
                'discount' => $discountAmount,
                'users' => ($amountWithoutGst * $userPercentage) / 100,
                'sales' => ($amountWithoutGst * $salesPercentage) / 100,
                'referral' => ($amountWithoutGst * $referralPercentage) / 100,
                'admin' => ($amountWithoutGst * $adminPercentage) / 100,
            ];
        };

        $allRows = (clone $campaignQuery)->get()->map($mapCampaignRow);
        $totals = [
            'amount_with_gst' => $allRows->sum('amount_with_gst'),
            'gst' => $allRows->sum('gst'),
            'amount_without_gst' => $allRows->sum('amount_without_gst'),
            'amount_without_gst_with_discount' => $allRows->sum('amount_without_gst_with_discount'),
            'discount' => $allRows->sum('discount'),
            'users' => $allRows->sum('users'),
            'sales' => $allRows->sum('sales'),
            'referral' => $allRows->sum('referral'),
            'admin' => $allRows->sum('admin'),
        ];

        $brands = $campaignQuery
            ->orderByDesc('id')
            ->paginate($limit)
            ->through($mapCampaignRow)
            ->withQueryString();

        return view('admin-views.report._seller-earning',compact('data', 'campaignCount', 'brandsCount', 'brands', 'totals', 'date_type','from', 'to'));
    }

    public function exportBrandReport(Request $request)
    {
        $date_type = $request->get('date_type', 'this_year');
        $from = date('Y-m-d');
        $to   = date('Y-m-d');

        switch ($date_type) {
            case 'this_week':
                $from = now()->startOfWeek();
                $to   = now()->endOfWeek();
                break;
            case 'this_month':
                $from = now()->startOfMonth();
                $to   = now()->endOfMonth();
                break;
            case 'this_year':
                $from = now()->startOfYear();
                $to   = now()->endOfYear();
                break;
            case 'custom_date':
                $from = $request->get('from', date('Y-m-d'));
                $to   = $request->get('to', date('Y-m-d'));
                break;
            default:
                $from = now()->startOfYear();
                $to   = now()->endOfYear();
        }

        $campaigns = Campaign::with(['brand:id,username'])
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('id')
            ->get();

        $rows = $campaigns->map(function ($campaign) {
            $baseAmount   = (float) ($campaign->total_campaign_budget ?? 0);
            $amountWithGst = (float) ($campaign->compign_budget_with_gst ?? $baseAmount);
            $gstAmount    = max(0, $amountWithGst - $baseAmount);

            $userPercentage     = (float) ($campaign->user_percentage ?? 0);
            $salesPercentage    = (float) ($campaign->sales_percentage ?? 0);
            $referralPercentage = (float) ($campaign->feedback_percentage ?? 0);
            $adminPercentage    = (float) ($campaign->admin_percentage ?? 0);

            $discountPercentage = max(0, 100 - ($userPercentage + $salesPercentage + $referralPercentage + $adminPercentage));
            $discountAmount     = ($baseAmount * $discountPercentage) / 100;
            $amountWithoutGst   = $baseAmount - $discountAmount;

            return [
                'Brand'                              => $campaign->brand->username ?? '-',
                'Campaign'                           => $campaign->unique_code ?? ('RXC_' . str_pad((string) $campaign->id, 5, '0', STR_PAD_LEFT)),
                'Total Amount with GST'              => number_format($amountWithGst, 2, '.', ''),
                'GST'                                => number_format($gstAmount, 2, '.', ''),
                'Total Amount without GST'           => number_format($amountWithoutGst, 2, '.', ''),
                'Total Amount without GST + Discount'=> number_format($baseAmount, 2, '.', ''),
                'Discount'                           => number_format($discountAmount, 2, '.', ''),
                'Users'                              => number_format(($amountWithoutGst * $userPercentage) / 100, 2, '.', ''),
                'Sales'                              => number_format(($amountWithoutGst * $salesPercentage) / 100, 2, '.', ''),
                'Referral'                           => number_format(($amountWithoutGst * $referralPercentage) / 100, 2, '.', ''),
                'Admin'                              => number_format(($amountWithoutGst * $adminPercentage) / 100, 2, '.', ''),
            ];
        });

        $filename = 'brand-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');

            if ($rows->isNotEmpty()) {
                fputcsv($handle, array_keys($rows->first()));
            }

            foreach ($rows as $row) {
                fputcsv($handle, array_values($row));
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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
            ->paginate($limit)
            ->withQueryString();

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

    public function exportCampaignReport(Request $request)
    {
        $date_type = $request->get('date_type', 'this_year');
        $from = date('Y-m-d');
        $to   = date('Y-m-d');

        switch ($date_type) {
            case 'this_week':
                $from = now()->startOfWeek();
                $to   = now()->endOfWeek();
                break;
            case 'this_month':
                $from = now()->startOfMonth();
                $to   = now()->endOfMonth();
                break;
            case 'this_year':
                $from = now()->startOfYear();
                $to   = now()->endOfYear();
                break;
            case 'custom_date':
                $from = $request->get('from', date('Y-m-d'));
                $to   = $request->get('to', date('Y-m-d'));
                break;
            default:
                $from = now()->startOfYear();
                $to   = now()->endOfYear();
        }

        $campaigns = Campaign::with(['brand:id,username'])
            ->whereBetween('created_at', [$from, $to])
            ->withCount([
                'campaign_transactions as participants',
                'campaign_transactions as approved_posts' => function ($q) {
                    $q->where('status', 'approved');
                },
                'campaign_transactions as rejected_posts' => function ($q) {
                    $q->where('status', 'rejected');
                },
            ])
            ->orderByDesc('id')
            ->get();

        $filename = 'campaign-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($campaigns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Campaign', 'Brand', 'Budget', 'Participants', 'Approved', 'Rejected', 'Status']);
            foreach ($campaigns as $campaign) {
                fputcsv($handle, [
                    $campaign->title,
                    $campaign->brand->username ?? '-',
                    $campaign->total_campaign_budget,
                    $campaign->participants,
                    $campaign->approved_posts,
                    $campaign->rejected_posts,
                    ucwords($campaign->status),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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
            ->paginate($limit)
            ->withQueryString();

        return view('admin-views.report._post-reports', compact('data', 'posts', 'date_type', 'from', 'to'));
    }

    public function exportPostReport(Request $request)
    {
        $date_type = $request->get('date_type', 'this_year');
        $from = date('Y-m-d');
        $to   = date('Y-m-d');

        switch ($date_type) {
            case 'this_week':
                $from = now()->startOfWeek();
                $to   = now()->endOfWeek();
                break;
            case 'this_month':
                $from = now()->startOfMonth();
                $to   = now()->endOfMonth();
                break;
            case 'this_year':
                $from = now()->startOfYear();
                $to   = now()->endOfYear();
                break;
            case 'custom_date':
                $from = $request->get('from', date('Y-m-d'));
                $to   = $request->get('to', date('Y-m-d'));
                break;
            default:
                $from = now()->startOfYear();
                $to   = now()->endOfYear();
        }

        $posts = CampaignTransaction::with(['campaign.brand'])
            ->select('campaign_id', 'post_url', 'status', 'shared_on', 'likes', 'comments', 'created_at')
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('id')
            ->get();

        $filename = 'post-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($posts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Campaign', 'Brand', 'Status', 'Likes', 'Comments', 'Post URL', 'Posted Date']);
            foreach ($posts as $post) {
                fputcsv($handle, [
                    $post->campaign?->title ?? '-',
                    $post->campaign?->brand->username ?? '-',
                    ucwords($post->status),
                    $post->likes,
                    $post->comments,
                    $post->post_url,
                    date('d M, Y', strtotime($post->created_at)),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
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
                        ->orWhereHasMorph('causer', [
                            \App\Models\Admin::class,
                            \App\Models\Sale::class,
                            \App\Models\User::class,
                        ], function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHasMorph('causer', [
                            \App\Models\Seller::class,
                        ], function ($uq) use ($search) {
                            $uq->where('username', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        return view('admin-views.report._activity-logs', compact('logs', 'date_type', 'from', 'to', 'search', 'limit'));
    }

}
