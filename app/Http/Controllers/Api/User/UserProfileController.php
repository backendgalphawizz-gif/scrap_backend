<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonResource;
use App\Models\BrandFeedbackQuestion;
use App\Models\Campaign;
use App\Models\CoinWallet;
use App\Models\Feedback;
use App\CPU\ImageManager;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Seller;
use App\Models\Notification;
use App\Models\SocialVerificationTransaction;
use App\Models\UserLevel;
use App\Models\BrandCategory;
use App\Services\FraudDetectionService;
use App\Services\FraudScoreService;
use App\Models\CampaignTransaction;
use App\Services\PanValidationService;
use App\Services\TdsCalculationService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserProfileController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $user->referrers;

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => new CommonResource($user)
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'mobile' => 'sometimes|digits:10|unique:users,mobile,' . $user->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator)
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'mobile', 'dob', 'gender', 'profession', 'address', 'city', 'state', 'native_state', 'native_city', 'instagram_username', 'facebook_username']));

        if ($request->hasFile('image')) {
            $user->image = ImageManager::upload('profile/', 'png', $request->file('image'), $user->image);
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile updated successfully',
            'data' => new CommonResource($user)
        ]);
    }

    public function updateKycPan(Request $request)
    {
        $user = $request->user();

        if ($user->pan_status === 'Verified') {
            return response()->json([
                'status'  => true,
                'message' => 'PAN is already verified and cannot be changed.',
                'data'    => new CommonResource($user),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'pan_number' => 'required|string|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $panVerification = app(PanValidationService::class)->verifyPanNumber($request->pan_number);

        if ($panVerification['error'] !== null) {
            return response()->json([
                'status'  => false,
                'message' => $panVerification['error'],
            ], 502);
        }

        if (!$panVerification['valid']) {
            return response()->json([
                'status'  => false,
                'message' => 'PAN number is invalid. Please enter a valid PAN.',
                'data'    => [
                    'pan_number' => $request->pan_number,
                    'pan_status' => $panVerification['status'],
                ],
            ], 422);
        }

        $panValidation = app(PanValidationService::class);
        $assignError = $panValidation->validateAssignment(
            $request->pan_number,
            (string) $user->name,
            $panVerification['name'] ?? null,
            $user->id
        );
        if ($assignError !== null) {
            return response()->json([
                'status'  => false,
                'message' => $assignError,
            ], 422);
        }

        $user->pan_number = $panValidation->normalizePan($request->pan_number);
        $user->pan_status = 'Submitted';

        // Auto-verify Aadhaar linkage via PAN 360 response
        if ($panVerification['aadhaar_linked'] && $panVerification['masked_aadhaar'] !== null) {
            $user->aadhar_number = $panVerification['masked_aadhaar'];
            $user->aadhar_status = 'Verified';
        }

        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'PAN details submitted successfully.',
            'data'    => new CommonResource($user),
        ]);
    }

    public function updateKycUpi(Request $request)
    {
        $user = $request->user();

        if ($user->upi_status === 'Verified') {
            return response()->json([
                'status'  => true,
                'message' => 'UPI is already verified and cannot be changed.',
                'data'    => new CommonResource($user),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'upi_id' => 'required|string|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $user->upi_id = $request->upi_id;
        $user->upi_status = 'Submitted';
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'UPI details submitted successfully.',
            'data'    => new CommonResource($user),
        ]);
    }

    public function updateKycBank(Request $request)
    {
        $user = $request->user();

        if ($user->bank_status === 'Verified') {
            return response()->json([
                'status'  => true,
                'message' => 'Bank details are already verified and cannot be changed.',
                'data'    => new CommonResource($user),
            ]);
        }

        $validator = Validator::make($request->all(), [
            'bank_name'      => 'required|string|max:100',
            'branch_name'    => 'required|string|max:100',
            'account_number' => 'required|string|max:20',
            'ifsc_code'      => 'required|string|size:11',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $bankFields = ['bank_name', 'ifsc_code', 'account_number', 'branch_name'];
        $decoded = json_decode($user->bank_detail ?? '{}', true);
        $existing = is_array($decoded) ? $decoded : [];
        $merged = array_merge($existing, array_filter(
            $request->only($bankFields),
            fn ($v) => $v !== null && $v !== ''
        ));
        $user->bank_detail = json_encode($merged);
        $user->bank_status = 'Submitted';
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Bank details submitted successfully.',
            'data'    => new CommonResource($user),
        ]);
    }

    public function coinWallet(Request $request)
    {
        try {
            //code...
            $user = $request->user();

            CoinWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            $wallet = $user->coinWallet;
            return response()->json([
                'status' => true,
                'message' => 'Coin wallet retrieved successfully',
                'data' => new CommonResource($wallet)
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function walletTransctions(Request $request)
    {
        try {
            //code...
            $limit = $request->limit ?? 25;
            $status = $request->status ?? '';

            $user = $request->user();
            $transactions = $user->coinWallet->transactions()->when($status != '', function ($q) use ($status) {
                $q->where('status', $status);
            })->latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Wallet transactions retrieved successfully',
                'data' => CommonResource::collection($transactions)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
            //throw $th;
        }
    }

    public function withdrawalPreview(Request $request, TdsCalculationService $tdsService)
    {
        $validator = Validator::make($request->all(), [
            'coins' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        try {
            $breakdown = $tdsService->computeWithdrawal($request->user(), (float) $request->coins);

            return response()->json([
                'status' => true,
                'message' => 'Withdrawal preview calculated successfully',
                'data' => [
                    'coins' => (string) $breakdown['coins'],
                    'gross_amount' => number_format($breakdown['gross_amount'], 2, '.', ''),
                    'tds_amount' => number_format($breakdown['tds_amount'], 2, '.', ''),
                    'tds_rate' => (string) $breakdown['tds_rate'],
                    'tds_section' => $breakdown['tds_section'],
                    'net_payout' => number_format($breakdown['net_amount'], 2, '.', ''),
                    'upi_value' => (string) $breakdown['conversion_rate'],
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed',
                'data' => [],
            ], 422);
        }
    }

    public function debitWalletCoin(Request $request, TdsCalculationService $tdsService)
    {
        try {
            $user = $request->user();

            $rules = [
                'coins' => 'required|numeric|min:1',
                'type' => 'required',
                'value' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => Helpers::single_error_processor($validator),
                ], 422);
            }

            $breakdown = $tdsService->computeWithdrawal($user, (float) $request->coins);

            $wallet = CoinWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            // Fraud check — duplicate UPI/bank account
            $upiId = $request->value;
            $fraudDetected = app(FraudDetectionService::class)->checkDuplicateUpi($user, $upiId);
            if ($fraudDetected) {
                app(FraudScoreService::class)->recalculate($user);
                return response()->json([
                    'status'  => false,
                    'message' => 'Withdrawal could not be processed. Please contact support.',
                ], 422);
            }

            if ($wallet->withdrawal_frozen) {
                return response()->json([
                    'status' => false,
                    'message' => 'Withdrawals are disabled for this wallet. Contact support if you need help.',
                ], 403);
            }

            if ($wallet->balance < $request->coins) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient balance',
                ], 422);
            }

            $wallet->balance -= $request->coins;
            $wallet->save();

            $transaction = $wallet->transactions()->create([
                'coin' => $breakdown['coins'],
                'amount' => $breakdown['gross_amount'],
                'tds' => $breakdown['tds_amount'],
                'net_amount' => $breakdown['net_amount'],
                'tds_rate' => $breakdown['tds_rate'],
                'tds_section' => $breakdown['tds_section'],
                'pan_status_at_withdrawal' => $breakdown['pan_status_at_withdrawal'],
                'convertion_rate' => $breakdown['conversion_rate'],
                'campaign_id' => 0,
                'transaction_id' => (string) time(),
                'type' => 'debit',
                'status' => 'pending',
                'transaction_type' => $request->type,
                'value' => $request->value,
                'description' => $request->input('description'),
            ]);

            Helpers::logUserWalletTransaction('created', $transaction, $user, 'Wallet withdrawal request');

            $resource = new CommonResource($transaction);
            $payload = $resource->toArray($request);
            $payload['gross_amount'] = number_format($breakdown['gross_amount'], 2, '.', '');
            $payload['tds_amount'] = number_format($breakdown['tds_amount'], 2, '.', '');
            $payload['tds_rate'] = (string) $breakdown['tds_rate'];
            $payload['net_payout'] = number_format($breakdown['net_amount'], 2, '.', '');

            return response()->json([
                'status' => true,
                'message' => 'Debit request created successfully',
                'data' => $payload,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'Validation failed',
                'data' => [],
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function referrers(Request $request)
    {
        $user = $request->user();
        $referredUsers = $user->referrers()->get();

        if ($referredUsers->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'User profile retrieved successfully',
                'data' => []
            ]);
        }

        $walletId = optional($user->coinWallet)->id;
        $coinsPerUser = collect();

        if ($walletId) {
            $referredUserIds = $referredUsers->pluck('id');

            // Join referral coin_transactions back to campaign_transactions to identify
            // which referred user triggered each referral bonus.
            // transaction_id on the referral credit = 'REF-' + campaign_transactions.id
            $coinsPerUser = DB::table('coin_transactions as rt')
                ->join('campaign_transactions as ct', DB::raw("CONCAT('REF-', ct.id)"), '=', 'rt.transaction_id')
                ->where('rt.coin_wallet_id', $walletId)
                ->where('rt.type', 'credit')
                ->where('rt.transaction_type', 'referral_reward')
                ->where(function ($q) {
                    $q->where('rt.status', 'completed')->orWhereNull('rt.status');
                })
                ->whereIn('ct.user_id', $referredUserIds)
                ->groupBy('ct.user_id')
                ->select('ct.user_id', DB::raw('SUM(rt.coin) as referral_coins'))
                ->pluck('referral_coins', 'user_id');
        }

        $result = $referredUsers->map(function ($referred) use ($coinsPerUser) {
            $referred->referral_coins = (float) ($coinsPerUser->get($referred->id) ?? 0);
            return $referred;
        });

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => $result
        ]);
    }

    public function getBrandFeedbackQuestion(Request $request, $id)
    {
        $brandId = (int) $id;
        $brandExists = Seller::withTrashed()->where('id', $brandId)->exists();

        $questions = BrandFeedbackQuestion::query()
            ->where('brand_id', $brandId)
            ->where('status', 1)
            ->orderBy('id', 'ASC')
            ->get()
            ->map(function ($item) {
                $options = is_array($item->options) ? array_values(array_filter($item->options, fn ($opt) => trim((string) $opt) !== '')) : [];

                return [
                    'id' => (int) $item->id,
                    'brand_id' => (int) $item->brand_id,
                    'question' => $item->question,
                    'question_type' => $item->question_type ?: 'multiple_choice',
                    'options' => $options,
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'message' => $brandExists ? 'Brand questions retrieved successfully' : 'Questions retrieved by brand reference id',
            'data' => [
                'brand_id' => $brandId,
                'brand_exists' => $brandExists,
                'total_questions' => $questions->count(),
                'questions' => $questions,
            ]
        ]);
    }

    public function submitCampaignFeedback(Request $request)
    {
        $user = $request->user();

        // Support Postman payloads where `questions` may arrive as JSON string in form-data.
        $incomingQuestions = $request->input('questions');
        if (is_string($incomingQuestions) && trim($incomingQuestions) !== '') {
            $decoded = json_decode($incomingQuestions, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $request->merge(['questions' => $decoded]);
            }
        }

        $rules = [
            'campaign_id' => 'required|exists:campaigns,id',
            'rating' => 'required|numeric|min:1|max:5',
            'user_feedback' => 'nullable|string|max:1000',
            'feedback' => 'nullable|string|max:1000',
            'questions' => 'nullable|array',
            'questions.*.question_id' => 'required|integer',
            'questions.*.answer' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
                'errors' => $validator->errors(),
            ], 422);
        }

        $campaign = Campaign::findOrFail($request->campaign_id);
        $brand = Seller::find($campaign->brand_id);

        if (! $brand) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found for this campaign'
            ], 422);
        }

        $allowedQuestions = BrandFeedbackQuestion::query()
            ->where('brand_id', $brand->id)
            ->where('status', 1)
            ->get()
            ->keyBy('id');

        $submittedQuestions = collect($request->input('questions', []));
        $normalizedAnswers = [];
        $validQuestionIds = $allowedQuestions->keys()->map(fn ($key) => (int) $key)->values()->all();

        foreach ($submittedQuestions as $row) {
            $questionId = (int) ($row['question_id'] ?? 0);
            $answer = $row['answer'] ?? null;

            if (! $allowedQuestions->has($questionId)) {
                return response()->json([
                    'status' => false,
                    'message' => "Invalid question_id: {$questionId}",
                    'data' => [
                        'campaign_id' => (int) $campaign->id,
                        'brand_id' => (int) $campaign->brand_id,
                        'submitted_question_id' => $questionId,
                        'valid_question_ids' => $validQuestionIds,
                        'hint' => 'Use only question_id values returned by GET user/get-feedbacks-questions/{brandId} for this campaign brand.',
                    ]
                ], 422);
            }

            $question = $allowedQuestions->get($questionId);
            $questionType = $question->question_type ?: 'multiple_choice';
            $options = is_array($question->options) ? array_values(array_filter($question->options, fn ($opt) => trim((string) $opt) !== '')) : [];
            $normalizedAnswer = is_string($answer) ? trim($answer) : $answer;

            if ($questionType === 'multiple_choice') {
                if (! is_string($normalizedAnswer) || $normalizedAnswer === '') {
                    return response()->json([
                        'status' => false,
                        'message' => "Answer is required for question_id: {$questionId}"
                    ], 422);
                }

                $normalizedOptions = array_map(fn ($opt) => trim((string) $opt), $options);
                $optionMatched = in_array($normalizedAnswer, $normalizedOptions, true);

                if (! $optionMatched) {
                    // Fallback: case-insensitive match to reduce client-side formatting issues.
                    $lowerAnswer = mb_strtolower($normalizedAnswer);
                    foreach ($normalizedOptions as $opt) {
                        if (mb_strtolower($opt) === $lowerAnswer) {
                            $normalizedAnswer = $opt;
                            $optionMatched = true;
                            break;
                        }
                    }
                }

                if (! $optionMatched) {
                    return response()->json([
                        'status' => false,
                        'message' => "Invalid option selected for question_id: {$questionId}",
                        'data' => [
                            'allowed_options' => $normalizedOptions,
                        ]
                    ], 422);
                }
            }

            if ($questionType === 'input') {
                if (! is_string($normalizedAnswer) || $normalizedAnswer === '') {
                    return response()->json([
                        'status' => false,
                        'message' => "Input answer is required for question_id: {$questionId}"
                    ], 422);
                }

                if (mb_strlen($normalizedAnswer) > 1000) {
                    return response()->json([
                        'status' => false,
                        'message' => "Input answer too long for question_id: {$questionId}"
                    ], 422);
                }
            }

            $normalizedAnswers[] = [
                'question_id' => $questionId,
                'question' => $question->question,
                'question_type' => $questionType,
                'answer' => $normalizedAnswer,
                'options' => $questionType === 'multiple_choice' ? $options : [],
            ];
        }

        $feedbackTxn = CampaignTransaction::where('campaign_id', $campaign->id)
            ->where('user_id', $user->id)
            ->whereNotNull('verified_at')
            ->orderByDesc('verified_at')
            ->first();

        $feedbackBase = Carbon::parse($campaign->end_date)->endOfDay();
        if ($feedbackTxn && $feedbackTxn->verified_at->gt($feedbackBase)) {
            $feedbackBase = $feedbackTxn->verified_at;
        }
        $feedbackDeadline = $feedbackBase->copy()->addDays(3);

        if (Carbon::now()->gt($feedbackDeadline)) {
            return response()->json([
                'status'  => false,
                'message' => 'Feedback period has ended.',
            ], 422);
        }

        $existingFeedback = Feedback::where('campaign_id', $campaign->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingFeedback) {
            return response()->json([
                'status' => false,
                'message' => 'You have already submitted feedback for this campaign.'
            ], 422);
        }

        $feedback = Feedback::create([
            'campaign_id' => $campaign->id,
            'user_id' => $user->id,
            'brand_id' => $campaign->brand_id,
            'ratings' => $request->input('rating'),
            'questions' => json_encode($normalizedAnswers),
            'user_feedback' => $request->input('user_feedback', $request->input('feedback')),
        ]);

        $feedbackCoin = $campaign->feedback_coin ?? 0;
        if ($feedbackCoin > 0) {
            $wallet = CoinWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            $wallet->balance += $feedbackCoin;
            $wallet->save();

            $feedbackTransaction = $wallet->transactions()->create([
                'coin' => $feedbackCoin,
                'amount' => 0,
                'tds' => 0,
                'convertion_rate' => Helpers::get_business_settings('upi_value') ?? 0,
                'campaign_id' => $campaign->id,
                'transaction_id' => 'FDB-' . time() . '-' . $campaign->id,
                'type' => 'credit',
                'status' => 'completed',
                'transaction_type' => 'campaign_feedback',
                'value' => 'Campaign Feedback',
                'description' => 'Coins earned for submitting feedback on campaign: ' . $campaign->title,
            ]);

            Helpers::logUserWalletTransaction('created', $feedbackTransaction, $user, 'Campaign feedback reward');
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign feedback submitted successfully',
            'data' => new CommonResource($feedback)
        ]);
    }

    public function listCampaignFeedback(Request $request)
    {
        $user = $request->user();
        $feedbacks = Feedback::with(['campaign', 'brand', 'user'])->where('user_id', $user->id)->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Campaign feedbacks retrieved successfully',
            'data' => CommonResource::collection($feedbacks)
        ]);
    }

    public function notifications(Request $request)
    {
        $limit = $request->limit ?? 25;
        $notifications = Notification::where(['status' => 1, 'user_type' => 'user'])->orderBy('id', 'DESC')->paginate($limit);
        return response()->json([
            'status' => true,
            'message' => 'Notification retrieved successfully',
            'data' => CommonResource::collection($notifications)
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $id = $request->user()->id;

        $user = User::find($id);
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully',
            'data' => []
        ]);
    }

    public function verifySocial(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'platform'    => 'required|in:instagram,facebook,threads',
            'username'    => 'required|string|max:255',
            'unique_code' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $platform = $request->platform;
        $statusField = $platform . '_status';

        if ($user->$statusField === SocialVerificationTransaction::STATUS_VERIFIED) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($platform) . ' account is already verified.',
            ], 422);
        }

        // For Facebook, normalize any input format (URL, numeric ID, vanity handle)
        // to a canonical identifier so the scraper can build a navigable profile URL.
        $username = $platform === 'facebook'
            ? $this->parseFacebookIdentifier($request->username)
            : $request->username;

        // Cancel any existing pending transaction for this platform
        SocialVerificationTransaction::where('user_id', $user->id)
            ->where('platform', $platform)
            ->where('status', SocialVerificationTransaction::STATUS_PENDING)
            ->update(['status' => SocialVerificationTransaction::STATUS_NOT_VERIFIED]);

        $transaction = SocialVerificationTransaction::create([
            'user_id'      => $user->id,
            'platform'     => $platform,
            'username'     => $username,
            'unique_code'  => $request->unique_code,
            'status'       => SocialVerificationTransaction::STATUS_PENDING,
            'submitted_at' => now(),
            'end_date'     => now()->addDays(1)->toDateString(),
        ]);

        $usernameField = $platform . '_username';
        $user->$usernameField = $username;
        $user->$statusField = SocialVerificationTransaction::STATUS_PENDING;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Verification initiated. Post this unique code on your ' . ucfirst($platform) . ' account.',
            'data'    => [
                'unique_code' => $request->unique_code,
                'platform'    => $platform,
                'username'    => $username,
                'end_date'    => $transaction->end_date,
            ],
        ]);
    }

    /**
     * Normalize any Facebook profile input to a canonical stored identifier.
     *
     * Accepts:
     *   https://www.facebook.com/profile.php?id=61589493652439  →  61589493652439
     *   https://www.facebook.com/swara.kx                       →  swara.kx
     *   https://fb.com/swara.kx                                 →  swara.kx
     *   61589493652439                                           →  61589493652439  (numeric id)
     *   swara.kx                                                 →  swara.kx        (vanity handle)
     *
     * Display names like "Swara Kx" are not resolvable to a URL and are returned
     * as-is so the scraper can skip them gracefully rather than silently failing.
     */
    private function parseFacebookIdentifier(string $raw): string
    {
        $raw = trim($raw);

        // Already a numeric profile ID
        if (preg_match('/^\d+$/', $raw)) {
            return $raw;
        }

        // URL containing profile.php?id=<digits>
        if (preg_match('/profile\.php[^?]*\?(?:[^#]*&)?id=(\d+)/i', $raw, $m)) {
            return $m[1];
        }

        // Full URL with a vanity path: (www.)facebook.com/<handle> or fb.com/<handle>
        if (preg_match('~(?:https?://)?(?:www\.)?(?:facebook|fb)\.com/([^/?#\s]+)~i', $raw, $m)) {
            $handle = rtrim($m[1], '/');
            // Skip known non-profile path segments
            $reserved = ['pages', 'groups', 'events', 'hashtag', 'watch', 'marketplace', 'gaming'];
            if (!in_array(strtolower($handle), $reserved, true)) {
                return $handle;
            }
        }

        // Plain vanity handle (no spaces, no http prefix) — return as-is
        if (!str_contains($raw, ' ') && !str_contains($raw, 'http')) {
            return $raw;
        }

        // Unresolvable (e.g. display name "Swara Kx") — return as-is;
        // scraper will skip and log a warning.
        return $raw;
    }

    public function socialVerificationStatus(Request $request)
    {
        $user = $request->user();

        // Determine user's level based on total coin earnings
        $wallet = CoinWallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );
        $totalEarnings = $wallet->total_coin_earning;

        $level = UserLevel::where('range_min', '<=', $totalEarnings)
            ->where('range_max', '>=', $totalEarnings)
            ->first();

        $maxPostsPerDay = $level ? (int) $level->max_participations_per_day : 0;

        // Count today's posts (excluding deleted and rejected)
        $todaysPostCount = CampaignTransaction::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->whereNotIn('status', [CampaignTransaction::STATUS_DELETED, CampaignTransaction::STATUS_REJECTED])
            ->count();

        $canPostMore = $maxPostsPerDay > 0 && $todaysPostCount < $maxPostsPerDay;

        $instagramTx = SocialVerificationTransaction::where('user_id', $user->id)
            ->where('platform', SocialVerificationTransaction::PLATFORM_INSTAGRAM)
            ->latest()
            ->first();

        $facebookTx = SocialVerificationTransaction::where('user_id', $user->id)
            ->where('platform', SocialVerificationTransaction::PLATFORM_FACEBOOK)
            ->latest()
            ->first();

        $threadsTx = SocialVerificationTransaction::where('user_id', $user->id)
            ->where('platform', SocialVerificationTransaction::PLATFORM_THREADS)
            ->latest()
            ->first();

        return response()->json([
            'status'  => true,
            'message' => 'Social verification status retrieved successfully',
            'data'    => [
                'instagram' => [
                    'status'       => $user->adminDisplaySocialStatus('instagram'),
                    'username'     => $user->adminDisplaySocialUsername('instagram'),
                    'submitted_at' => $instagramTx?->submitted_at,
                ],
                'facebook' => [
                    'status'       => $user->adminDisplaySocialStatus('facebook'),
                    'username'     => $user->adminDisplaySocialUsername('facebook'),
                    'submitted_at' => $facebookTx?->submitted_at,
                ],
                'threads' => [
                    'status'       => $user->adminDisplaySocialStatus('threads'),
                    'username'     => $user->adminDisplaySocialUsername('threads'),
                    'submitted_at' => $threadsTx?->submitted_at,
                ],
                'level'             => $level ? $level->name : null,
                'max_posts_per_day' => $maxPostsPerDay,
                'todays_post_count' => $todaysPostCount,
                'can_post_more'     => $canPostMore,
            ],
        ]);
    }

    public function updateDeviceToken(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $user->fcm_id = $request->token;
        $user->save();

        return response()->json(['status' => true, 'message' => 'Device token updated.']);
    }

    public function updateInterest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'interests'   => 'required|array|min:1',
            'interests.*' => 'required|integer|exists:brand_categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $user = $request->user();
        $user->my_interest = implode(',', $request->interests);
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Interests updated successfully',
            'data'    => [
                'my_interest' => $user->my_interest,
            ],
        ]);
    }

    public function interestCampaigns(Request $request)
    {
        $user = $request->user();

        // Parse user's saved interest category IDs
        $interestIds = [];
        if (!empty($user->my_interest)) {
            // Trim spaces around commas to handle "1, 2" format
            $rawInterest = $user->my_interest;
            $interestIds = array_values(array_filter(array_map('intval', array_map('trim', explode(',', $rawInterest)))));
        }

        if (empty($interestIds)) {
            return response()->json([
                'status'       => true,
                'message'      => 'No interests set. Please update your interests to see personalised campaigns.',
                'my_interests' => [],
                'data'         => [],
            ]);
        }

        $myInterests = BrandCategory::whereIn('id', $interestIds)->get(['id', 'name']);

        // Calculate user age from dob
        $userAge = null;
        if (!empty($user->dob)) {
            try {
                $birthDate = \Carbon\Carbon::parse($user->dob);
                $userAge = $birthDate->isFuture() ? null : $birthDate->age;
            } catch (\Throwable $e) {
                $userAge = null;
            }
        }

        $gender = trim((string) ($user->gender ?? ''));
        $city   = trim((string) ($user->city   ?? ''));
        $state  = trim((string) ($user->state  ?? ''));

        $campaigns = Campaign::with(['brand'])
            ->withCount(['occupiedTransactions as occupied_slots'])
            // Interest match: campaign category must be in user's interests
            ->whereIn('category_id', $interestIds)
            // Only active campaigns
            ->where('status', 'active')
            // Only campaigns that have already started
            ->where('start_date', '<=', now()->toDateString())
            // Only campaigns from visible brands
            ->whereHas('brand', function ($q) {
                $q->where('visibility_status', 'true');
            })
            // Gender: campaign targets user's gender or 'both'
            ->when($gender !== '', function ($q) use ($gender) {
                $q->where(function ($sub) use ($gender) {
                    $sub->where('gender', $gender)
                        ->orWhere('gender', 'both');
                });
            })
            // City: no restriction (null/empty/Any) OR city list contains user's city
            ->when($city !== '', function ($q) use ($city) {
                $q->where(function ($sub) use ($city) {
                    $sub->whereNull('city')
                        ->orWhere('city', '')
                        ->orWhere('city', 'Any')
                        ->orWhere('city', 'any')
                        ->orWhereRaw('FIND_IN_SET(?, city) > 0', [$city]);
                });
            })
            // State: no restriction (null/empty/Any) OR matches user state
            ->when($state !== '', function ($q) use ($state) {
                $q->where(function ($sub) use ($state) {
                    $sub->whereNull('state')
                        ->orWhere('state', '')
                        ->orWhere('state', 'Any')
                        ->orWhere('state', 'any')
                        ->orWhere('state', $state);
                });
            })
            // Age: user age must fall within the campaign's age_range (e.g. "18-35")
            ->when($userAge !== null, function ($q) use ($userAge) {
                $q->whereRaw(
                    'CAST(SUBSTRING_INDEX(REPLACE(age_range, " ", ""), "-", 1) AS UNSIGNED) <= ?
                     AND CAST(SUBSTRING_INDEX(REPLACE(age_range, " ", ""), "-", -1) AS UNSIGNED) >= ?',
                    [$userAge, $userAge]
                );
            })
            ->whereNotIn('id', function ($sub) use ($user) {
                $sub->select('campaign_id')
                    ->from('user_campaign_skips')
                    ->where('user_id', $user->id);
            })
            ->whereNotIn('id', function ($sub) use ($user) {
                $sub->select('campaign_id')
                    ->from('campaign_transactions')
                    ->where('user_id', $user->id);
            })
            ->orderBy('id', 'DESC')
            ->paginate($request->input('limit', 10));

        return response()->json([
            'status'       => true,
            'message'      => 'Interest-based campaigns retrieved successfully',
            'data'         => CommonResource::collection($campaigns),
        ]);
    }

    }