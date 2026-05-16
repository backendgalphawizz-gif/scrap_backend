<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Models\CampaignTransaction;
use Illuminate\Support\Str;

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

    public function updateKyc(Request $request)
    {
        $user = $request->user();
        
        if ($request->has('pan_number') && $user->pan_status !== 'Verified') {
            // Verify PAN with third-party API before accepting it
            $panVerification = $this->verifyPanNumber($request->pan_number);

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

            $user->pan_number = $request->pan_number;
            $user->pan_status = 'Submitted';
            if ($request->hasFile('pan_image')) {
                $user->pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'));
            }
        }

        if ($request->has('aadhar_number') && $user->aadhar_status !== 'Verified') {
            $user->aadhar_number = $request->aadhar_number;
            $user->aadhar_status = 'Submitted';
            $aadhar_images = [];
            if ($request->hasFile('aadhar_image')) {
                foreach ($request->file('aadhar_image') as $img) {
                    $aadhar_images[] = ImageManager::upload('profile/', 'png', $img, $user->image);
                }
                $user->aadhar_image = implode(',', $aadhar_images);
            }
        }

        if ($request->has('upi_id') && $user->upi_status !== 'Verified') {
            $user->upi_id = $request->upi_id;
            $user->upi_status = 'Submitted';
        }
        $bankFields = ['bank_name', 'ifsc_code', 'account_number', 'branch_name'];
        $hasBankField = collect($bankFields)->some(fn ($f) => $request->has($f));
        if ($hasBankField && $user->bank_status !== 'Verified') {
            $decoded = json_decode($user->bank_detail ?? '{}', true);
            $existing = is_array($decoded) ? $decoded : [];
            $merged = array_merge($existing, array_filter(
                $request->only($bankFields),
                fn ($v) => $v !== null && $v !== ''
            ));
            $user->bank_detail = json_encode($merged);
            $hasAnyValue = collect($merged)->some(fn ($v) => trim((string) $v) !== '');
            $user->bank_status = $hasAnyValue ? 'Submitted' : 'Not Submitted';
        }

        $kycDirty = $user->isDirty();
        if ($kycDirty) {
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => $kycDirty
                ? 'User KYC updated successfully'
                : 'No KYC fields were updated (verified items cannot be changed).',
            'data' => new CommonResource($user)
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

    public function debitWalletCoin(Request $request)
    {
        try {
            $user = $request->user();

            $rules = [
                'coins' => 'required|numeric|min:1',
                'type' => 'required',
                'value' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => Helpers::single_error_processor($validator)
                ], 422);
            }

            $wallet = CoinWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            if ($wallet->withdrawal_frozen) {
                return response()->json([
                    'status' => false,
                    'message' => 'Withdrawals are disabled for this wallet. Contact support if you need help.',
                ], 403);
            }

            if ($wallet->balance < $request->coins) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient balance'
                ], 422);
            }

            $wallet->balance -= $request->coins;
            $wallet->save();

            $transaction = $wallet->transactions()->create([
                'coin' => $request->coins ?? 0,
                'amount' => $request->amount ?? 0,
                'tds' => $request->tds ?? 0,
                'convertion_rate' => Helpers::get_business_settings('upi_value'),
                'campaign_id' => 0,
                'transaction_id' => time(),
                'type' => 'debit',
                'status' => 'pending',
                'transaction_type' => $request->type,
                'value' => $request->value,
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Debit request created successfully',
                'data' => new CommonResource($transaction)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function referrers(Request $request)
    {
        $user = $request->user();
        // $user->referrers;

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => $user->referrers
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

            $wallet->transactions()->create([
                'coin' => $feedbackCoin,
                'amount' => 0,
                'tds' => 0,
                'convertion_rate' => Helpers::get_business_settings('upi_value') ?? 0,
                'campaign_id' => $campaign->id,
                'transaction_id' => time() . rand(100, 999),
                'type' => 'credit',
                'status' => 'completed',
                'transaction_type' => 'campaign_feedback',
                'value' => 'Campaign Feedback',
                'description' => 'Coins earned for submitting feedback on campaign: ' . $campaign->title,
            ]);
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
            'platform' => 'required|in:instagram,facebook',
            'username' => 'required|string|max:100',
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

        // Cancel any existing pending transaction for this platform
        SocialVerificationTransaction::where('user_id', $user->id)
            ->where('platform', $platform)
            ->where('status', SocialVerificationTransaction::STATUS_PENDING)
            ->update(['status' => SocialVerificationTransaction::STATUS_NOT_VERIFIED]);

        $transaction = SocialVerificationTransaction::create([
            'user_id'      => $user->id,
            'platform'     => $platform,
            'username'     => $request->username,
            'unique_code'  => $request->unique_code,
            'status'       => SocialVerificationTransaction::STATUS_PENDING,
            'submitted_at' => now(),
            'end_date'     => now()->addDays(7)->toDateString(),
        ]);

        $user->$statusField = SocialVerificationTransaction::STATUS_PENDING;
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Verification initiated. Post this unique code on your ' . ucfirst($platform) . ' account.',
            'data'    => [
                'unique_code' => $request->unique_code,
                'platform'    => $platform,
                'username'    => $request->username,
                'end_date'    => $transaction->end_date,
            ],
        ]);
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

        return response()->json([
            'status'  => true,
            'message' => 'Social verification status retrieved successfully',
            'data'    => [
                'instagram' => [
                    'status'           => $user->instagram_status,
                    'username'         => $user->instagram_username,
                ],
                'facebook' => [
                    'status'           => $user->facebook_status,
                    'username'         => $user->facebook_username,
                ],
                'level'             => $level ? $level->name : null,
                'max_posts_per_day' => $maxPostsPerDay,
                'todays_post_count' => $todaysPostCount,
                'can_post_more'     => $canPostMore,
            ],
        ]);
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
            ->orderBy('id', 'DESC')
            ->paginate($request->input('limit', 10));

        return response()->json([
            'status'       => true,
            'message'      => 'Interest-based campaigns retrieved successfully',
            'data'         => CommonResource::collection($campaigns),
        ]);
    }

    /**
     * Verify a PAN number against the Nerofy third-party API.
     *
     * Returns an array with keys:
     *   'valid'    (bool)        – true if PAN IS VALID
     *   'status'   (string|null) – raw pan_status from the API
     *   'name'     (string|null) – registered_name from the API
     *   'error'    (string|null) – human-readable error when API call fails
     */
    private function verifyPanNumber(string $panNumber): array
    {
        $token = env('NEROFY_API_TOKEN');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.nerofy.in/api/v1/service/pancard/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode(['panNumber' => strtoupper(trim($panNumber))]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
        ]);

        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => 'PAN verification service unreachable: ' . $curlError];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded['data']['pan_status'])) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => 'Invalid response from PAN verification service.'];
        }

        $panStatus = strtoupper(trim($decoded['data']['pan_status'] ?? ''));
        $isValid   = $panStatus === 'PAN IS VALID';

        return [
            'valid'  => $isValid,
            'status' => $decoded['data']['pan_status'] ?? null,
            'name'   => $decoded['data']['registered_name'] ?? null,
            'error'  => null,
        ];
    }

    }