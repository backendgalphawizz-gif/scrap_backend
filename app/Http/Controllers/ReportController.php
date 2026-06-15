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
use App\Models\CampaignCreditNote;
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

        $mapCampaignRow = function ($campaign, array $slabRates = []) {
            $baseAmount    = (float) ($campaign->total_campaign_budget ?? 0);
            $amountWithGst = (float) ($campaign->compign_budget_with_gst ?? $baseAmount);

            // Actual voucher discount stored on the campaign (default 0 for legacy campaigns)
            $discountAmount = (float) ($campaign->discount_amount ?? 0);
            $netTaxable     = $baseAmount - $discountAmount;
            $gstAmount      = max(0, $amountWithGst - $netTaxable);

            $userPercentage        = (float) ($campaign->user_percentage ?? 0);
            $feedbackPercentage    = (float) ($campaign->feedback_percentage ?? 0);
            $repeatBrandPercentage = (float) ($campaign->repeat_brand_percentage ?? 0);
            $referralPercentage    = (float) ($campaign->user_referral_percentage ?? 0);

            $adjusted          = $this->computeSlabAdjustedRates($campaign, $slabRates);
            $actualSalesPct    = $adjusted['actual_sales_pct'];
            $effectiveAdminPct = $adjusted['effective_admin_pct'];

            // Buckets are on the BASE amount; discount is deducted from the SALES bucket only
            $grossSalesBucket = ($baseAmount * $actualSalesPct)     / 100;
            $netSalesBucket   = max(0, $grossSalesBucket - $discountAmount);

            return [
                'brand_id'                         => $campaign->brand_id,
                'campaign_id'                      => $campaign->id,
                'brand'                            => $campaign->brand->username ?? '-',
                'campaign'                         => $campaign->unique_code ?? ('RXC_' . str_pad((string) $campaign->id, 5, '0', STR_PAD_LEFT)),
                'discount_code'                    => $campaign->discount_code ?? '',
                'amount_with_gst'                  => $amountWithGst,
                'gst'                              => $gstAmount,
                'amount_without_gst'               => $netTaxable,
                'amount_without_gst_with_discount' => $baseAmount,
                'discount'                         => $discountAmount,
                'users'                            => ($baseAmount * $userPercentage)        / 100,
                'sales'                            => $netSalesBucket,
                'feedback'                         => ($baseAmount * $feedbackPercentage)    / 100,
                'repeat_brand'                     => ($baseAmount * $repeatBrandPercentage) / 100,
                'referral'                         => ($baseAmount * $referralPercentage)    / 100,
                'admin'                            => ($baseAmount * $effectiveAdminPct)     / 100,
                'slab_saving_pct'                  => $adjusted['slab_saving_pct'],
            ];
        };

        $allCampaigns = (clone $campaignQuery)->get();
        $slabRates    = $this->loadSlabRates($allCampaigns->pluck('id')->all());
        $allRows      = $allCampaigns->map(fn ($c) => $mapCampaignRow($c, $slabRates));
        $totals = [
            'amount_with_gst'                  => $allRows->sum('amount_with_gst'),
            'gst'                              => $allRows->sum('gst'),
            'amount_without_gst'               => $allRows->sum('amount_without_gst'),
            'amount_without_gst_with_discount' => $allRows->sum('amount_without_gst_with_discount'),
            'discount'                         => $allRows->sum('discount'),
            'users'                            => $allRows->sum('users'),
            'sales'                            => $allRows->sum('sales'),
            'feedback'                         => $allRows->sum('feedback'),
            'repeat_brand'                     => $allRows->sum('repeat_brand'),
            'referral'                         => $allRows->sum('referral'),
            'admin'                            => $allRows->sum('admin'),
        ];

        $brands = $campaignQuery
            ->orderByDesc('id')
            ->paginate($limit)
            ->through(fn ($c) => $mapCampaignRow($c, $slabRates))
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

        $slabRates = $this->loadSlabRates($campaigns->pluck('id')->all());

        $rows = $campaigns->map(function ($campaign) use ($slabRates) {
            $baseAmount    = (float) ($campaign->total_campaign_budget ?? 0);
            $amountWithGst = (float) ($campaign->compign_budget_with_gst ?? $baseAmount);

            $discountAmount = (float) ($campaign->discount_amount ?? 0);
            $netTaxable     = $baseAmount - $discountAmount;
            $gstAmount      = max(0, $amountWithGst - $netTaxable);

            $userPercentage        = (float) ($campaign->user_percentage ?? 0);
            $feedbackPercentage    = (float) ($campaign->feedback_percentage ?? 0);
            $repeatBrandPercentage = (float) ($campaign->repeat_brand_percentage ?? 0);
            $referralPercentage    = (float) ($campaign->user_referral_percentage ?? 0);

            $adjusted          = $this->computeSlabAdjustedRates($campaign, $slabRates);
            $actualSalesPct    = $adjusted['actual_sales_pct'];
            $effectiveAdminPct = $adjusted['effective_admin_pct'];

            $grossSalesBucket = ($baseAmount * $actualSalesPct) / 100;
            $netSalesBucket   = max(0, $grossSalesBucket - $discountAmount);

            return [
                'Brand'                               => $campaign->brand->username ?? '-',
                'Campaign'                            => $campaign->unique_code ?? ('RXC_' . str_pad((string) $campaign->id, 5, '0', STR_PAD_LEFT)),
                'Discount Code'                       => $campaign->discount_code ?? '',
                'Total Amount with GST'               => number_format($amountWithGst, 2, '.', ''),
                'GST'                                 => number_format($gstAmount, 2, '.', ''),
                'Total Amount without GST'            => number_format($netTaxable, 2, '.', ''),
                'Total Amount without GST + Discount' => number_format($baseAmount, 2, '.', ''),
                'Discount'                            => number_format($discountAmount, 2, '.', ''),
                'Users'                               => number_format(($baseAmount * $userPercentage)        / 100, 2, '.', ''),
                'Sales (Net)'                         => number_format($netSalesBucket, 2, '.', ''),
                'Sales Slab % Used'                   => number_format($actualSalesPct, 2, '.', ''),
                'Slab Saving %'                       => number_format($adjusted['slab_saving_pct'], 2, '.', ''),
                'Feedback'                            => number_format(($baseAmount * $feedbackPercentage)    / 100, 2, '.', ''),
                'Repeat Brand'                        => number_format(($baseAmount * $repeatBrandPercentage) / 100, 2, '.', ''),
                'Referral'                            => number_format(($baseAmount * $referralPercentage)    / 100, 2, '.', ''),
                'Admin (Effective)'                   => number_format(($baseAmount * $effectiveAdminPct)  / 100, 2, '.', ''),
                'Effective Admin %'                   => number_format($effectiveAdminPct, 2, '.', ''),
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
                    \App\CPU\Helpers::formatAdminDateTime($post->created_at),
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

    public function financialReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);
        $limit = (int) ($request->get('limit') ?? 10);

        $campaignQuery = Campaign::with(['brand:id,username'])
            ->whereBetween('created_at', [$from, $to])
            ->withCount([
                'campaign_transactions as posts_completed_total' => function ($q) {
                    $q->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES);
                },
                'campaign_transactions as posts_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_APPROVED,
                        CampaignTransaction::STATUS_COMPLETED,
                    ]);
                },
                'campaign_transactions as posts_not_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_PENDING,
                        CampaignTransaction::STATUS_ACTIVE,
                        CampaignTransaction::STATUS_FLAGGED,
                    ]);
                },
                'campaign_transactions as posts_referred_total' => function ($q) {
                    $q->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES)
                      ->whereHas('user', fn ($uq) => $uq->whereNotNull('friends_code')->where('friends_code', '<>', ''));
                },
                'campaign_transactions as posts_referred_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_APPROVED,
                        CampaignTransaction::STATUS_COMPLETED,
                    ])->whereHas('user', fn ($uq) => $uq->whereNotNull('friends_code')->where('friends_code', '<>', ''));
                },
                'campaign_transactions as posts_referred_not_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_PENDING,
                        CampaignTransaction::STATUS_ACTIVE,
                        CampaignTransaction::STATUS_FLAGGED,
                    ])->whereHas('user', fn ($uq) => $uq->whereNotNull('friends_code')->where('friends_code', '<>', ''));
                },
            ]);

        $allCampaigns  = (clone $campaignQuery)->get();
        $slabRates     = $this->loadSlabRates($allCampaigns->pluck('id')->all());
        $allRows       = $allCampaigns->map(fn ($c) => $this->mapFinancialReportRow($c, $slabRates));
        $totals        = $this->aggregateFinancialReportTotals($allRows);

        $rows = $campaignQuery
            ->orderByDesc('id')
            ->paginate($limit)
            ->through(fn ($c) => $this->mapFinancialReportRow($c, $slabRates))
            ->withQueryString();

        return view('admin-views.report._financial-report', compact('rows', 'totals', 'date_type', 'from', 'to'));
    }

    public function exportFinancialReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);

        $campaigns = Campaign::with(['brand:id,username'])
            ->whereBetween('created_at', [$from, $to])
            ->withCount([
                'campaign_transactions as posts_completed_total' => function ($q) {
                    $q->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES);
                },
                'campaign_transactions as posts_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_APPROVED,
                        CampaignTransaction::STATUS_COMPLETED,
                    ]);
                },
                'campaign_transactions as posts_not_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_PENDING,
                        CampaignTransaction::STATUS_ACTIVE,
                        CampaignTransaction::STATUS_FLAGGED,
                    ]);
                },
                'campaign_transactions as posts_referred_total' => function ($q) {
                    $q->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES)
                      ->whereHas('user', fn ($uq) => $uq->whereNotNull('friends_code')->where('friends_code', '<>', ''));
                },
                'campaign_transactions as posts_referred_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_APPROVED,
                        CampaignTransaction::STATUS_COMPLETED,
                    ])->whereHas('user', fn ($uq) => $uq->whereNotNull('friends_code')->where('friends_code', '<>', ''));
                },
                'campaign_transactions as posts_referred_not_verified' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_PENDING,
                        CampaignTransaction::STATUS_ACTIVE,
                        CampaignTransaction::STATUS_FLAGGED,
                    ])->whereHas('user', fn ($uq) => $uq->whereNotNull('friends_code')->where('friends_code', '<>', ''));
                },
            ])
            ->orderByDesc('id')
            ->get();

        $slabRates  = $this->loadSlabRates($campaigns->pluck('id')->all());
        $campaignRows = $campaigns->map(fn ($c) => $this->mapFinancialReportRow($c, $slabRates));
        $totals = $this->aggregateFinancialReportTotals($campaignRows);
        $filename = 'financial-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($campaignRows, $totals) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Brand', 'Campaign', 'Start Date', 'End Date',
                'Total Amount with GST', 'Total Amount without GST', 'Per Post Cost', 'Total Post Required', 'Discount',
                'Post Completed', '', '',
                'Already Spent', '', '',
                'To Users', '', '',
                'To Sales (Actual)', '', '',
                'For referral', '', '',
                'Admin (Effective)', '', '',
            ]);
            fputcsv($handle, [
                '', '', '', '', '', '', '', '', '',
                'Total', 'Verified', 'Not Verified',
                'Total', 'Verified', 'Not Verified',
                'Total', 'Verified', 'Not Verified',
                'Total', 'Verified', 'Not Verified',
                'Total', 'Verified', 'Not Verified',
                'Total', 'Verified', 'Not Verified',
            ]);

            fputcsv($handle, $this->financialReportCsvRow($totals, 'Total'));

            foreach ($campaignRows as $row) {
                fputcsv($handle, $this->financialReportCsvRow($row, $row['brand'], $row['campaign'], $row['start_date'], $row['end_date']));
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function resolveReportDateRange(Request $request): array
    {
        $date_type = $request->get('date_type', 'this_year');
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
                $from = $request->get('from', date('Y-m-d'));
                $to = $request->get('to', date('Y-m-d'));
                break;
            default:
                $from = now()->startOfYear();
                $to = now()->endOfYear();
        }

        return [$date_type, $from, $to];
    }

    private function mapFinancialReportRow(Campaign $campaign, array $slabRates = []): array
    {
        $baseAmount         = (float) ($campaign->total_campaign_budget ?? 0);
        $amountWithGst      = (float) ($campaign->compign_budget_with_gst ?? $baseAmount);
        $userPercentage     = (float) ($campaign->user_percentage ?? 0);
        $referralPercentage = (float) ($campaign->user_referral_percentage ?? 0);

        $adjusted          = $this->computeSlabAdjustedRates($campaign, $slabRates);
        $actualSalesPct    = $adjusted['actual_sales_pct'];
        $effectiveAdminPct = $adjusted['effective_admin_pct'];

        // Actual voucher discount (0 for legacy campaigns)
        $discountAmount     = (float) ($campaign->discount_amount ?? 0);
        $netTaxable         = $baseAmount - $discountAmount;
        $gstAmount          = max(0, $amountWithGst - $netTaxable);

        $totalPostRequired  = (int) ($campaign->total_user_required ?? 0);
        $perPostCost        = $totalPostRequired > 0 ? ($baseAmount / $totalPostRequired) : 0;

        $postsCompletedTotal  = (int) ($campaign->posts_completed_total ?? 0);
        $postsVerified        = (int) ($campaign->posts_verified ?? 0);
        $postsNotVerified     = (int) ($campaign->posts_not_verified ?? 0);

        $postsReferredTotal       = (int) ($campaign->posts_referred_total ?? 0);
        $postsReferredVerified    = (int) ($campaign->posts_referred_verified ?? 0);
        $postsReferredNotVerified = (int) ($campaign->posts_referred_not_verified ?? 0);

        // Buckets on base; discount deducted from sales bucket only
        $grossSalesBucket = ($baseAmount * $actualSalesPct) / 100;
        $netSalesBucket   = max(0, $grossSalesBucket - $discountAmount);
        $salesPctEffective = $baseAmount > 0 ? ($netSalesBucket / $baseAmount * 100) : 0;

        $splitAmount = function (float $percentage, int $postCount) use ($perPostCost) {
            return ($perPostCost * $percentage / 100) * $postCount;
        };

        return [
            'brand'                      => $campaign->brand->username ?? '-',
            'campaign'                   => $campaign->unique_code ?? ('RXC_' . str_pad((string) $campaign->id, 5, '0', STR_PAD_LEFT)),
            'discount_code'              => $campaign->discount_code ?? '',
            'start_date'                 => Helpers::formatAdminDate($campaign->start_date),
            'end_date'                   => Helpers::formatAdminDate($campaign->end_date),
            'amount_with_gst'            => $amountWithGst,
            'amount_without_gst'         => $netTaxable,
            'per_post_cost'              => $perPostCost,
            'total_post_required'        => $totalPostRequired,
            'discount'                   => $discountAmount,
            'posts_completed_total'      => $postsCompletedTotal,
            'posts_verified'             => $postsVerified,
            'posts_not_verified'         => $postsNotVerified,
            'already_spent_total'        => $perPostCost * $postsCompletedTotal,
            'already_spent_verified'     => $perPostCost * $postsVerified,
            'already_spent_not_verified' => $perPostCost * $postsNotVerified,
            'users_total'                => $splitAmount($userPercentage,     $postsCompletedTotal),
            'users_verified'             => $splitAmount($userPercentage,     $postsVerified),
            'users_not_verified'         => $splitAmount($userPercentage,     $postsNotVerified),
            'sales_total'                => $splitAmount($salesPctEffective,  $postsCompletedTotal),
            'sales_verified'             => $splitAmount($salesPctEffective,  $postsVerified),
            'sales_not_verified'         => $splitAmount($salesPctEffective,  $postsNotVerified),
            'referral_total'             => $splitAmount($referralPercentage, $postsReferredTotal),
            'referral_verified'          => $splitAmount($referralPercentage, $postsReferredVerified),
            'referral_not_verified'      => $splitAmount($referralPercentage, $postsReferredNotVerified),
            'admin_total'                => $splitAmount($effectiveAdminPct,  $postsCompletedTotal),
            'admin_verified'             => $splitAmount($effectiveAdminPct,  $postsVerified),
            'admin_not_verified'         => $splitAmount($effectiveAdminPct,  $postsNotVerified),
            'slab_saving_pct'            => $adjusted['slab_saving_pct'],
        ];
    }

    private function aggregateFinancialReportTotals($rows): array
    {
        $keys = [
            'amount_with_gst', 'amount_without_gst', 'per_post_cost', 'total_post_required', 'discount',
            'posts_completed_total', 'posts_verified', 'posts_not_verified',
            'already_spent_total', 'already_spent_verified', 'already_spent_not_verified',
            'users_total', 'users_verified', 'users_not_verified',
            'sales_total', 'sales_verified', 'sales_not_verified',
            'referral_total', 'referral_verified', 'referral_not_verified',
            'admin_total', 'admin_verified', 'admin_not_verified',
        ];

        $totals = array_fill_keys($keys, 0);

        foreach ($rows as $row) {
            foreach ($keys as $key) {
                $totals[$key] += (float) ($row[$key] ?? 0);
            }
        }

        return $totals;
    }

    private function financialReportCsvRow(array $row, string $col1 = '', string $col2 = '', ?string $startDate = null, ?string $endDate = null): array
    {
        $fmt = fn ($value) => rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');

        return [
            $col1,
            $col2,
            $startDate ?? '',
            $endDate ?? '',
            $fmt($row['amount_with_gst']),
            $fmt($row['amount_without_gst']),
            $fmt($row['per_post_cost']),
            $fmt($row['total_post_required']),
            $fmt($row['discount']),
            $fmt($row['posts_completed_total']),
            $fmt($row['posts_verified']),
            $fmt($row['posts_not_verified']),
            $fmt($row['already_spent_total']),
            $fmt($row['already_spent_verified']),
            $fmt($row['already_spent_not_verified']),
            $fmt($row['users_total']),
            $fmt($row['users_verified']),
            $fmt($row['users_not_verified']),
            $fmt($row['sales_total']),
            $fmt($row['sales_verified']),
            $fmt($row['sales_not_verified']),
            $fmt($row['referral_total']),
            $fmt($row['referral_verified']),
            $fmt($row['referral_not_verified']),
            $fmt($row['admin_total']),
            $fmt($row['admin_verified']),
            $fmt($row['admin_not_verified']),
        ];
    }

    // -------------------------------------------------------------------------
    // Admin Earning Report
    // -------------------------------------------------------------------------

    public function adminEarningReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);
        $limit = (int) ($request->get('limit') ?? 10);

        $campaignQuery = Campaign::with(['brand:id,username'])
            ->whereBetween('created_at', [$from, $to])
            ->withCount([
                'campaign_transactions as completed_posts' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_APPROVED,
                        CampaignTransaction::STATUS_COMPLETED,
                    ]);
                },
                'campaign_transactions as total_participants' => function ($q) {
                    $q->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES);
                },
            ]);

        $allCampaigns = (clone $campaignQuery)->get();
        $slabRates    = $this->loadSlabRates($allCampaigns->pluck('id')->all());
        $allRows      = $allCampaigns->map(fn ($c) => $this->mapAdminEarningRow($c, $slabRates));
        $summary      = $this->aggregateAdminEarningSummary($allRows);

        $rows = $campaignQuery
            ->orderByDesc('id')
            ->paginate($limit)
            ->through(fn ($c) => $this->mapAdminEarningRow($c, $slabRates))
            ->withQueryString();

        return view('admin-views.report._admin-earning-report', compact(
            'rows', 'summary', 'date_type', 'from', 'to'
        ));
    }

    public function exportAdminEarningReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);

        $campaigns = Campaign::with(['brand:id,username'])
            ->whereBetween('created_at', [$from, $to])
            ->withCount([
                'campaign_transactions as completed_posts' => function ($q) {
                    $q->whereIn('status', [
                        CampaignTransaction::STATUS_APPROVED,
                        CampaignTransaction::STATUS_COMPLETED,
                    ]);
                },
                'campaign_transactions as total_participants' => function ($q) {
                    $q->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES);
                },
            ])
            ->orderByDesc('id')
            ->get();

        $slabRates   = $this->loadSlabRates($campaigns->pluck('id')->all());
        $campaignRows = $campaigns->map(fn ($c) => $this->mapAdminEarningRow($c, $slabRates));
        $summary     = $this->aggregateAdminEarningSummary($campaignRows);
        $filename    = 'admin-earning-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $fmt = fn ($v) => number_format((float) $v, 2, '.', '');

        $callback = function () use ($campaignRows, $summary, $fmt) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Campaign', 'Brand', 'Status',
                'Campaign Budget (₹)', 'Snapshotted Admin %', 'Effective Admin %', 'Slab Saving %',
                'Per-Post Cost (₹)',
                'Total Posts Required', 'Completed Posts',
                'Projected Admin Earnings (₹)',
                'Slab Saving Earnings (₹)',
                'Actual Admin Earnings (₹)',
                'Utilisation %',
            ]);

            // Totals row
            fputcsv($handle, [
                'TOTAL', '', '',
                $fmt($summary['total_budget']),
                '', '', '',
                '',
                $summary['total_posts_required'],
                $summary['total_completed_posts'],
                $fmt($summary['total_projected_earnings']),
                $fmt($summary['total_slab_saving_earnings']),
                $fmt($summary['total_actual_earnings']),
                '',
            ]);

            foreach ($campaignRows as $row) {
                fputcsv($handle, [
                    $row['campaign'],
                    $row['brand'],
                    ucwords($row['status']),
                    $fmt($row['campaign_budget']),
                    $row['admin_percentage']    . '%',
                    $row['effective_admin_pct'] . '%',
                    $row['slab_saving_pct']     . '%',
                    $fmt($row['per_post_cost']),
                    $row['posts_required'],
                    $row['completed_posts'],
                    $fmt($row['projected_earnings']),
                    $fmt($row['slab_saving_earnings']),
                    $fmt($row['actual_earnings']),
                    $row['utilisation_pct'] . '%',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function mapAdminEarningRow(Campaign $campaign, array $slabRates = []): array
    {
        $budget         = (float) ($campaign->total_campaign_budget ?? 0);
        $adminPct       = (float) ($campaign->admin_percentage ?? 0);
        $postsRequired  = (int)   ($campaign->total_user_required ?? 0);
        $completedPosts = (int)   ($campaign->completed_posts ?? 0);
        $perPostCost    = $postsRequired > 0 ? ($budget / $postsRequired) : 0;

        $adjusted          = $this->computeSlabAdjustedRates($campaign, $slabRates);
        $effectiveAdminPct = $adjusted['effective_admin_pct'];
        $slabSavingPct     = $adjusted['slab_saving_pct'];

        // Projected uses the snapshotted admin % (baseline expectation)
        $projectedEarnings  = ($budget * $adminPct) / 100;

        // Actual uses the effective admin % which includes any slab saving
        $actualEarnings     = ($perPostCost * $effectiveAdminPct / 100) * $completedPosts;
        $slabSavingEarnings = ($perPostCost * $slabSavingPct    / 100) * $completedPosts;

        $utilisationPct = $postsRequired > 0 ? round(($completedPosts / $postsRequired) * 100, 1) : 0;

        return [
            'campaign_id'           => $campaign->id,
            'campaign'              => $campaign->unique_code ?? ('RXC_' . str_pad((string) $campaign->id, 5, '0', STR_PAD_LEFT)),
            'brand'                 => $campaign->brand->username ?? '-',
            'status'                => $campaign->status,
            'campaign_budget'       => $budget,
            'admin_percentage'      => $adminPct,
            'effective_admin_pct'   => $effectiveAdminPct,
            'actual_sales_pct'      => $adjusted['actual_sales_pct'],
            'slab_saving_pct'       => $slabSavingPct,
            'slab_saving_earnings'  => $slabSavingEarnings,
            'per_post_cost'         => $perPostCost,
            'posts_required'        => $postsRequired,
            'completed_posts'       => $completedPosts,
            'total_participants'    => (int) ($campaign->total_participants ?? 0),
            'projected_earnings'    => $projectedEarnings,
            'actual_earnings'       => $actualEarnings,
            'utilisation_pct'       => $utilisationPct,
            'created_at'            => Helpers::formatAdminDate($campaign->created_at),
        ];
    }

    private function aggregateAdminEarningSummary($rows): array
    {
        $summary = [
            'total_budget'                => 0,
            'total_posts_required'        => 0,
            'total_completed_posts'       => 0,
            'total_projected_earnings'    => 0,
            'total_actual_earnings'       => 0,
            'total_slab_saving_earnings'  => 0,
        ];

        foreach ($rows as $row) {
            $summary['total_budget']               += $row['campaign_budget'];
            $summary['total_posts_required']       += $row['posts_required'];
            $summary['total_completed_posts']      += $row['completed_posts'];
            $summary['total_projected_earnings']   += $row['projected_earnings'];
            $summary['total_actual_earnings']      += $row['actual_earnings'];
            $summary['total_slab_saving_earnings'] += $row['slab_saving_earnings'] ?? 0;
        }

        return $summary;
    }

    // -------------------------------------------------------------------------
    // GST Report
    // -------------------------------------------------------------------------

    public function gstReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);
        $limit = (int) ($request->get('limit') ?? 10);
        $tab   = $request->get('tab', 'invoices');

        $gstPercentage = (float) Helpers::get_business_settings('campaign_gst_percentage');
        if ($gstPercentage <= 0) {
            $gstPercentage = 18.0;
        }
        $platformState = strtolower(trim((string) (Helpers::get_business_settings('company_state') ?? '')));

        $campaignQuery = Campaign::with(['brand:id,username,gst_number,state'])
            ->whereBetween('created_at', [$from, $to]);

        $allCampaigns = (clone $campaignQuery)->get();

        $totals = [
            'total_taxable'    => 0,
            'total_gst'        => 0,
            'total_with_gst'   => 0,
            'gst_campaigns'    => 0,
            'normal_campaigns' => 0,
        ];
        foreach ($allCampaigns as $c) {
            $base  = (float) ($c->total_campaign_budget ?? 0);
            $disc  = (float) ($c->discount_amount ?? 0);
            $net   = $base - $disc;
            $withG = (float) ($c->compign_budget_with_gst ?? $net);
            $gst   = max(0, $withG - $net);
            $totals['total_taxable']  += $net;
            $totals['total_gst']      += $gst;
            $totals['total_with_gst'] += $withG;
            $c->generate_gst_invoice ? $totals['gst_campaigns']++ : $totals['normal_campaigns']++;
        }

        $creditNoteQuery = CampaignCreditNote::with(['brand:id,username,gst_number', 'campaign:id,unique_code'])
            ->whereBetween('created_at', [$from, $to]);

        $cnRow = (clone $creditNoteQuery)
            ->selectRaw('COUNT(*) as cnt, SUM(taxable_reversal_amount) as tax_rev, SUM(gst_reversal_amount) as gst_rev')
            ->first();

        $totals['credit_notes_count']   = $cnRow->cnt     ?? 0;
        $totals['credit_notes_tax_rev'] = $cnRow->tax_rev ?? 0;
        $totals['credit_notes_gst_rev'] = $cnRow->gst_rev ?? 0;

        $rows = $campaignQuery
            ->orderByDesc('id')
            ->paginate($limit, ['*'], 'page')
            ->through(fn ($c) => $this->mapGstReportRow($c, $gstPercentage, $platformState))
            ->withQueryString();

        $creditNotes = $creditNoteQuery
            ->orderByDesc('id')
            ->paginate($limit, ['*'], 'cn_page')
            ->withQueryString();

        return view('admin-views.report._gst-report', compact(
            'rows', 'creditNotes', 'totals', 'date_type', 'from', 'to', 'tab', 'gstPercentage'
        ));
    }

    public function exportGstReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);
        $tab = $request->get('tab', 'invoices');

        $gstPercentage = (float) Helpers::get_business_settings('campaign_gst_percentage');
        if ($gstPercentage <= 0) {
            $gstPercentage = 18.0;
        }
        $platformState = strtolower(trim((string) (Helpers::get_business_settings('company_state') ?? '')));

        $streamHeaders = [
            'Content-Type'  => 'text/csv',
            'Pragma'        => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'       => '0',
        ];

        if ($tab === 'credit_notes') {
            $notes = CampaignCreditNote::with(['brand:id,username,gst_number', 'campaign:id,unique_code'])
                ->whereBetween('created_at', [$from, $to])
                ->orderByDesc('id')
                ->get();

            $streamHeaders['Content-Disposition'] = 'attachment; filename="gst-credit-notes-' . now()->format('Y-m-d') . '.csv"';

            return response()->stream(function () use ($notes) {
                $h = fopen('php://output', 'w');
                fputcsv($h, ['Credit Note No.', 'Original Invoice No.', 'Brand', 'Brand GST No.', 'Campaign', 'Date', 'Taxable Reversal (Rs)', 'GST Reversal (Rs)', 'CGST (Rs)', 'SGST (Rs)', 'Reason', 'Status']);
                foreach ($notes as $n) {
                    fputcsv($h, [
                        $n->credit_note_no,
                        $n->original_invoice_no,
                        $n->brand->username ?? '-',
                        $n->brand->gst_number ?? '-',
                        $n->campaign->unique_code ?? ('RXC_' . str_pad((string) $n->campaign_id, 5, '0', STR_PAD_LEFT)),
                        $n->credit_note_date?->format('d/m/Y') ?? '-',
                        number_format((float) $n->taxable_reversal_amount, 2, '.', ''),
                        number_format((float) $n->gst_reversal_amount, 2, '.', ''),
                        number_format((float) $n->cgst_reversal, 2, '.', ''),
                        number_format((float) $n->sgst_reversal, 2, '.', ''),
                        $n->reason ?? '',
                        ucfirst($n->status ?? ''),
                    ]);
                }
                fclose($h);
            }, 200, $streamHeaders);
        }

        $campaigns = Campaign::with(['brand:id,username,gst_number,state'])
            ->whereBetween('created_at', [$from, $to])
            ->orderByDesc('id')
            ->get();

        $streamHeaders['Content-Disposition'] = 'attachment; filename="gst-report-' . now()->format('Y-m-d') . '.csv"';

        return response()->stream(function () use ($campaigns, $gstPercentage, $platformState) {
            $h = fopen('php://output', 'w');
            fputcsv($h, [
                'Invoice No.', 'Invoice Type', 'Brand', 'Brand GST No.', 'Campaign', 'Invoice Date',
                'Taxable (Rs)', 'Discount (Rs)', 'Net Taxable (Rs)',
                'GST %', 'GST Amount (Rs)',
                'CGST Rate %', 'CGST (Rs)', 'SGST Rate %', 'SGST (Rs)',
                'IGST Rate %', 'IGST (Rs)',
                'Total (Rs)', 'Status',
            ]);
            foreach ($campaigns as $campaign) {
                $row = $this->mapGstReportRow($campaign, $gstPercentage, $platformState);
                fputcsv($h, [
                    $row['invoice_no'],
                    $row['invoice_type'],
                    $row['brand'],
                    $row['brand_gst'],
                    $row['campaign'],
                    $row['invoice_date'],
                    number_format($row['taxable'], 2, '.', ''),
                    number_format($row['discount'], 2, '.', ''),
                    number_format($row['net_taxable'], 2, '.', ''),
                    $row['gst_pct'] . '%',
                    number_format($row['gst_amount'], 2, '.', ''),
                    $row['is_intra_state'] ? $row['cgst_rate'] . '%' : '0%',
                    number_format($row['cgst'], 2, '.', ''),
                    $row['is_intra_state'] ? $row['sgst_rate'] . '%' : '0%',
                    number_format($row['sgst'], 2, '.', ''),
                    !$row['is_intra_state'] ? $row['igst_rate'] . '%' : '0%',
                    number_format($row['igst'], 2, '.', ''),
                    number_format($row['total'], 2, '.', ''),
                    ucfirst($row['status']),
                ]);
            }
            fclose($h);
        }, 200, $streamHeaders);
    }

    private function mapGstReportRow(Campaign $campaign, float $gstPercentage, string $platformState): array
    {
        $base      = (float) ($campaign->total_campaign_budget ?? 0);
        $disc      = (float) ($campaign->discount_amount ?? 0);
        $netTax    = round($base - $disc, 2);
        $withGst   = (float) ($campaign->compign_budget_with_gst ?? $netTax);
        $gstAmount = round(max(0, $withGst - $netTax), 2);

        $brandState   = strtolower(trim((string) ($campaign->brand->state ?? '')));
        $isIntraState = $platformState !== '' && $brandState !== '' && $platformState === $brandState;

        $halfRate = $gstPercentage / 2;
        $cgst     = $isIntraState ? round($gstAmount / 2, 2) : 0.0;
        $sgst     = $isIntraState ? round($gstAmount - $cgst, 2) : 0.0;
        $igst     = $isIntraState ? 0.0 : $gstAmount;

        $isGst     = (bool) $campaign->generate_gst_invoice;
        $prefix    = $isGst ? 'INV-GST-CAM-' : 'INV-CAM-';
        $invoiceNo = $prefix . str_pad((string) $campaign->id, 6, '0', STR_PAD_LEFT);
        $invDate   = ($campaign->updated_at ?? $campaign->created_at)->format('d/m/Y');

        return [
            'campaign_id'    => $campaign->id,
            'invoice_no'     => $invoiceNo,
            'invoice_type'   => $isGst ? 'GST' : 'Normal',
            'brand'          => $campaign->brand->username ?? '-',
            'brand_gst'      => $campaign->brand->gst_number ?? '-',
            'campaign'       => $campaign->unique_code ?? ('RXC_' . str_pad((string) $campaign->id, 5, '0', STR_PAD_LEFT)),
            'invoice_date'   => $invDate,
            'taxable'        => $base,
            'discount'       => $disc,
            'net_taxable'    => $netTax,
            'gst_pct'        => $gstPercentage,
            'gst_amount'     => $gstAmount,
            'cgst_rate'      => $halfRate,
            'sgst_rate'      => $halfRate,
            'igst_rate'      => $gstPercentage,
            'cgst'           => $cgst,
            'sgst'           => $sgst,
            'igst'           => $igst,
            'total'          => $withGst,
            'is_intra_state' => $isIntraState,
            'status'         => $campaign->status,
        ];
    }

    // -------------------------------------------------------------------------
    // TDS Report
    // -------------------------------------------------------------------------

    public function tdsReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);
        $limit  = (int) ($request->get('limit') ?? 10);
        $status = $request->get('status', 'all');

        $query = CoinTransaction::with(['wallet:id,user_id', 'wallet.user:id,name,pan_number,pan_status'])
            ->where('type', 'debit')
            ->whereBetween('created_at', [$from, $to]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $totals = [
            'count'       => (clone $query)->count(),
            'total_coins' => (clone $query)->sum('coin'),
            'total_gross' => (clone $query)->sum('amount'),
            'total_tds'   => (clone $query)->sum('tds'),
            'total_net'   => (clone $query)->sum('net_amount'),
        ];

        $rows = $query->orderByDesc('id')->paginate($limit)->withQueryString();

        return view('admin-views.report._tds-report', compact(
            'rows', 'totals', 'date_type', 'from', 'to', 'status'
        ));
    }

    public function exportTdsReport(Request $request)
    {
        [$date_type, $from, $to] = $this->resolveReportDateRange($request);
        $status = $request->get('status', 'all');

        $query = CoinTransaction::with(['wallet:id,user_id', 'wallet.user:id,name,pan_number,pan_status'])
            ->where('type', 'debit')
            ->whereBetween('created_at', [$from, $to]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $rows     = $query->orderByDesc('id')->get();
        $filename = 'tds-report-' . now()->format('Y-m-d') . '.csv';

        $streamHeaders = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($rows) {
            $h = fopen('php://output', 'w');
            fputcsv($h, [
                '#', 'User Name', 'PAN No.', 'PAN Status at Withdrawal',
                'Coins', 'Gross Amount (Rs)', 'TDS Rate %', 'TDS Amount (Rs)',
                'Net Payout (Rs)', 'TDS Section', 'UPI / Bank', 'Status', 'Date',
            ]);
            foreach ($rows as $i => $tx) {
                $user = $tx->wallet->user ?? null;
                fputcsv($h, [
                    $i + 1,
                    $user->name ?? '-',
                    $user->pan_number ?? '-',
                    $tx->pan_status_at_withdrawal ?? '-',
                    number_format((float) $tx->coin, 2, '.', ''),
                    number_format((float) $tx->amount, 2, '.', ''),
                    number_format((float) ($tx->tds_rate ?? 0), 2, '.', '') . '%',
                    number_format((float) $tx->tds, 2, '.', ''),
                    number_format((float) $tx->net_amount, 2, '.', ''),
                    $tx->tds_section ?? '194C',
                    $tx->value ?? '-',
                    ucfirst($tx->status),
                    Helpers::formatAdminDateTime($tx->created_at),
                ]);
            }
            fclose($h);
        }, 200, $streamHeaders);
    }

    // -------------------------------------------------------------------------
    // Slab-rate helpers
    // -------------------------------------------------------------------------

    /**
     * Load the actual commission_rate used at settlement for a set of campaigns.
     * Returns a campaign_id → commission_rate map (only for settled campaigns).
     *
     * @param  int[] $campaignIds
     * @return array<int, float>
     */
    private function loadSlabRates(array $campaignIds): array
    {
        if (empty($campaignIds)) {
            return [];
        }

        return \App\Models\SaleCommissionLedger::whereIn('campaign_id', $campaignIds)
            ->where('reference_type', 'campaign_reward')
            ->pluck('commission_rate', 'campaign_id')
            ->all();
    }

    /**
     * Given a campaign and the preloaded slab-rate lookup, return the effective
     * rates to use for sales and admin in reports.
     *
     * Logic:
     *   - actualSalesPct   = slab rate actually used at settlement (from ledger),
     *                        or the campaign's snapshotted sales_percentage if
     *                        the campaign has not been settled yet.
     *   - slabSavingPct    = max(0, snapshotSalesPct − actualSalesPct)
     *                        (the saving from using a lower slab; goes to admin)
     *   - effectiveAdminPct = snapshotAdminPct + slabSavingPct
     *
     * @param  \App\Models\Campaign $campaign
     * @param  array<int, float>    $slabRates  campaign_id → actual commission_rate
     * @return array{actual_sales_pct: float, slab_saving_pct: float, effective_admin_pct: float}
     */
    private function computeSlabAdjustedRates(\App\Models\Campaign $campaign, array $slabRates): array
    {
        $snapshotSalesPct = (float) ($campaign->sales_percentage ?? 0);
        $snapshotAdminPct = (float) ($campaign->admin_percentage ?? 0);

        // No salesperson assigned — full sales percentage goes to admin
        if (!$campaign->sale_id) {
            return [
                'actual_sales_pct'    => 0.0,
                'slab_saving_pct'     => 0.0,
                'effective_admin_pct' => $snapshotAdminPct + $snapshotSalesPct,
            ];
        }

        $actualSalesPct = array_key_exists($campaign->id, $slabRates)
            ? (float) $slabRates[$campaign->id]
            : $snapshotSalesPct;

        $slabSavingPct    = max(0.0, $snapshotSalesPct - $actualSalesPct);
        $effectiveAdminPct = $snapshotAdminPct + $slabSavingPct;

        return [
            'actual_sales_pct'    => $actualSalesPct,
            'slab_saving_pct'     => $slabSavingPct,
            'effective_admin_pct' => $effectiveAdminPct,
        ];
    }

}
