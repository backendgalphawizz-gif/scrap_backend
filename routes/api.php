<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AdminProfileController;
use App\Http\Controllers\Api\UserManagementController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\User\DashboardController as UserDashboardController;
use App\Http\Controllers\Api\User\UserAuthController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\User\SocialAuthController;
use App\Http\Controllers\Api\User\UserCampaignActivityLogController;
use App\Http\Controllers\Api\User\VoucherController as UserVoucherController;
use App\Http\Controllers\Api\User\UserLevelController as UserLevelController;
use App\Http\Controllers\Api\Seller\SellerAuthController;
use App\Http\Controllers\Api\Seller\SellerWalletController;
use App\Http\Controllers\Api\Seller\SellerDashboardController;
use App\Http\Controllers\Api\Seller\FeedBackQuestionController as FeedbackQuestionController;

use App\Http\Controllers\Api\Sale\AuthController as SaleAuthController;
use App\Http\Controllers\Api\Sale\DashboardController as SaleDashboardController;
use App\Http\Controllers\Api\AdminSupportTicketController;
use App\Http\Controllers\Api\User\SupportTicketController as UserSupportTicketController;
use App\Http\Controllers\Api\Seller\SupportTicketController as BrandSupportTicketController;
use App\Http\Controllers\Api\Seller\SellerSocialVerificationController;
use App\Http\Controllers\Api\CampaignDayStatusController;
use Illuminate\Support\Facades\Artisan;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/optimize-clear', function () {

    // Optional: simple security key check
    if (request()->header('X-SECRET-KEY') !== 'my_secure_key_123') {
        return response()->json([
            'status' => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return response()->json([
        'status' => true,
        'message' => 'Optimize clear executed successfully',
        'output' => Artisan::output()
    ]);
});

Route::get('/campaign/sync-post-day-status', [CampaignDayStatusController::class, 'syncBulk']);
Route::post('/campaign/sync-post-day-status', [CampaignDayStatusController::class, 'syncBulk']);

Route::get('/campaign/run-process-results', function () {
    Artisan::call('campaign:process-results');

    return response()->json([
        'status' => true,
        'message' => 'campaign:process-results executed successfully',
        'output' => Artisan::output(),
    ]);
});
Route::get('categories', [UserAuthController::class, 'categories']);
Route::get('main-categories', [UserAuthController::class, 'mainCategories']);
Route::get('professions', [UserAuthController::class, 'professions']);
Route::get('banners', [UserAuthController::class, 'banners']);
Route::get('popup-banner', [UserAuthController::class, 'popupBanner']);
Route::get('config', [UserAuthController::class, 'config']);
Route::post('auth/send-otp', [UserAuthController::class, 'sendOtp']);
Route::post('auth/verify-otp', [UserAuthController::class, 'verifyOtp']);
Route::post('auth/resend-otp', [UserAuthController::class, 'resendOtp']);
Route::post('auth/register', [UserAuthController::class, 'register']);
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect']);
Route::get('/auth/{provider}/social_login', [SocialAuthController::class, 'social_login']);

Route::prefix('landing-page')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\LandingPageController::class, 'index']);
    Route::get('/{section}', [\App\Http\Controllers\Api\LandingPageController::class, 'section']);
});

// Auth 
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::prefix('admin')->middleware(['auth:api'])->group(function () {
        
        Route::get('/dashboard-count', [DashboardController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);

        //profile update api 
        Route::post('/profile/update', [AdminProfileController::class, 'updateProfile']);

        // user management route 
        Route::get('/users', [UserManagementController::class, 'index']);
        Route::post('/users', [UserManagementController::class, 'store']);
        Route::get('/users/{id}', [UserManagementController::class, 'show']);
        Route::post('/users/{id}', [UserManagementController::class, 'update']);
        Route::delete('/users/{id}', [UserManagementController::class, 'destroy']);
        Route::post('/users/{id}/status', [UserManagementController::class, 'updateStatus']);

        Route::get('/support-tickets', [AdminSupportTicketController::class, 'index']);
        Route::get('/support-tickets/{id}', [AdminSupportTicketController::class, 'show']);
        Route::delete('/support-tickets/{id}', [AdminSupportTicketController::class, 'destroy']);
        Route::post('/support-tickets/{id}/messages', [AdminSupportTicketController::class, 'sendMessage']);

        // Route::middleware('admin:create_user')
        //     ->post('/users', [UserController::class, 'store']);
    
        // Route::middleware('admin:view_users')
        //     ->get('/users', [UserController::class, 'index']);
    
        // Route::middleware('admin:create_role')
        //     ->post('/roles', [RoleController::class, 'store']);
    });
    Route::get('user/user-levels', [UserLevelController::class, 'index']);   
Route::prefix('user')->middleware(['auth:api', 'check.account.status:user'])->group(function () {

    // User profile related routes
    Route::get('profile', [UserProfileController::class, 'index']);
    Route::get('referrers', [UserProfileController::class, 'referrers']);
    Route::post('update-profile', [UserProfileController::class, 'update']); // na
    Route::post('update-kyc', [UserProfileController::class, 'updateKyc']);  // na

    // User wallet related routes
    Route::get('wallet', [UserProfileController::class, 'coinWallet']);
    Route::get('wallet-transactions', [UserProfileController::class, 'walletTransctions']);
    Route::post('wallet-withdrawl', [UserProfileController::class, 'debitWalletCoin']);

    // User Voucher related routes
    Route::get('voucher-brands', [UserVoucherController::class, 'brands']);
    Route::get('voucher-brands/{brandId}/vouchers', [UserVoucherController::class, 'byBrand']);
    Route::get('vouchers', [UserVoucherController::class, 'index']);
    Route::get('vouchers/purchased', [UserVoucherController::class, 'purchasedVouchers']);
    Route::get('vouchers/purchase-transactions', [UserVoucherController::class, 'purchaseTransactions']);
    Route::post('vouchers/purchase', [UserVoucherController::class, 'purchase']);
    Route::get('vouchers/{id}', [UserVoucherController::class, 'show']);

    // User Campaign related routes
    Route::post('campaigns', [UserDashboardController::class, 'index']);
    Route::get('local_for_vocal', [UserDashboardController::class, 'localForVocal']);
    Route::post('campaign/detail/{id}', [UserDashboardController::class, 'show']);
    Route::post('campaign/shared', [UserDashboardController::class, 'myCampaigns']);
    Route::post('share-campaign/{id}', [UserDashboardController::class, 'shareCampaign']);
    Route::post('campaign/skip', [UserDashboardController::class, 'skipCampaign']);
    Route::post('campaign/updateScrappedPosts', [UserDashboardController::class, 'updateScrappedPosts']);
    Route::post('campaign/activity-log', [UserCampaignActivityLogController::class, 'store']);
    

    // User Feedback related routes
    Route::post('submit-feedback', [UserProfileController::class, 'submitCampaignFeedback']);
    Route::get('list-feedbacks', [UserProfileController::class, 'listCampaignFeedback']);
    Route::get('get-feedbacks-questions/{id}', [UserProfileController::class, 'getBrandFeedbackQuestion']);

    // User Notifications related routes
    Route::get('notifications', [UserProfileController::class, 'notifications']);
    Route::post('verify-social', [UserProfileController::class, 'verifySocial']);
    Route::get('social-verification-status', [UserProfileController::class, 'socialVerificationStatus']);
    Route::get('delete-account', [UserProfileController::class, 'deleteAccount']);
    Route::get('support-tickets', [UserSupportTicketController::class, 'index']);
    Route::post('support-tickets', [UserSupportTicketController::class, 'store']);
    Route::get('support-tickets/{id}', [UserSupportTicketController::class, 'show']);
    Route::delete('support-tickets/{id}', [UserSupportTicketController::class, 'destroy']);
    Route::post('support-tickets/{id}/messages', [UserSupportTicketController::class, 'sendMessage']);

    // get user level and benefits

    // Update user interests
    Route::post('update-interest', [UserProfileController::class, 'updateInterest']);

    // Personalised campaigns based on user interests + demographics
    Route::get('interest-campaigns', [UserProfileController::class, 'interestCampaigns']);
    
});

Route::group(['prefix' => 'brand'], function () {
    // Public brand routes (no auth required)
    Route::get('brand-category-list', [SellerAuthController::class, 'brandCategoryList']);
    Route::get('campaign-guideline', [SellerAuthController::class, 'campaignGuideline']);
    Route::post('auth/send-otp', [SellerAuthController::class, 'sendOtp']);
    Route::post('auth/verify-otp', [SellerAuthController::class, 'verifyOtp']);
    Route::post('auth/resend-otp', [SellerAuthController::class, 'resendOtp']);
    Route::post('auth/register', [SellerAuthController::class, 'register']);
    Route::post('auth/notification', [SellerAuthController::class, 'sendNotification']);

    // Protected brand routes (requires active account)
    Route::middleware('check.account.status:seller')->group(function () {
        Route::get('profile', [SellerDashboardController::class, 'index']);
        Route::get('statistics', [SellerDashboardController::class, 'statistics']);
        Route::get('campaign-wise-report/{campaignId}', [SellerDashboardController::class, 'getCampaignWiseChartData']);
        Route::post('update-profile', [SellerDashboardController::class, 'update']);
        Route::post('update-socials', [SellerDashboardController::class, 'updateSocials']);
        Route::post('update-kyc', [SellerDashboardController::class, 'updateKyc']);
        Route::post('campaign/create', [SellerDashboardController::class, 'createCampaign']);
        Route::post('campaign/update/{id}', [SellerDashboardController::class, 'updateCampaign']);
        Route::post('campaign/update-status/{id}', [SellerDashboardController::class, 'updateCampaignStatus']);
        Route::get('campaign/detail/{id}', [SellerDashboardController::class, 'detailCampaign']);
        Route::get('campaign/list', [SellerDashboardController::class, 'listCampaign']);
        Route::get('campaign/delete/{id}', [SellerDashboardController::class, 'deleteCampaign']);
        Route::post('campaign-transaction/{id}/report-violation', [SellerDashboardController::class, 'reportViolation']);

        // Seller Wallet
        Route::get('/wallet', [SellerWalletController::class, 'index']);
        Route::post('/wallet/create', [SellerWalletController::class, 'createWalletTransaction']);
        Route::get('/wallet/transactions', [SellerWalletController::class, 'walletTransactionList']);

        // Feedback Question Crud
        Route::post('/feedback-questions', [FeedbackQuestionController::class, 'store']);
        Route::get('/feedback-questions', [FeedbackQuestionController::class, 'index']);
        Route::get('/feedback-questions/{id}', [FeedbackQuestionController::class, 'show']);
        Route::put('/feedback-questions/{id}', [FeedbackQuestionController::class, 'update']);
        Route::delete('/feedback-questions/{id}', [FeedbackQuestionController::class, 'destroy']);

        Route::get('delete-account', [SellerDashboardController::class, 'deleteAccount']);

        Route::get('notifications', [SellerDashboardController::class, 'notifications']);

        Route::post('verify-social', [SellerSocialVerificationController::class, 'verifySocial']);
        Route::get('social-verification-status', [SellerSocialVerificationController::class, 'socialVerificationStatus']);

        Route::get('support-tickets', [BrandSupportTicketController::class, 'index']);
        Route::post('support-tickets', [BrandSupportTicketController::class, 'store']);
        Route::get('support-tickets/{id}', [BrandSupportTicketController::class, 'show']);
        Route::delete('support-tickets/{id}', [BrandSupportTicketController::class, 'destroy']);
        Route::post('support-tickets/{id}/messages', [BrandSupportTicketController::class, 'sendMessage']);

        Route::get('campaign/has-campaign-last-100-days', [SellerDashboardController::class, 'hasCampaignInLast100Days']);
        Route::get('refunds', [SellerDashboardController::class, 'listRefunds']);
    });
});

Route::group(['prefix' => 'sale'], function () {
    // Public sale routes (no auth required)
    Route::post('auth/send-otp', [SaleAuthController::class, 'sendOtp']);
    Route::post('auth/login', [SaleAuthController::class, 'login']);
    Route::post('auth/forgot-password', [SaleAuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [SaleAuthController::class, 'resetPassword']);

    // Protected sale routes (requires active account)
    Route::middleware('check.account.status:sale')->group(function () {
        Route::get('profile', [SaleDashboardController::class, 'index']);
        Route::post('update-profile', [SaleDashboardController::class, 'update']);
        Route::post('update-kyc', [SaleDashboardController::class, 'updatekyc']);
        Route::post('create-withdrawl-request', [SaleDashboardController::class, 'createWithdrawl']);

        Route::post('campaign/create', [SaleDashboardController::class, 'createCampaign']);
        Route::post('campaign/update/{id}', [SaleDashboardController::class, 'updateCampaign']);
        Route::get('campaign/detail/{id}', [SaleDashboardController::class, 'detailCampaign']);
        Route::get('campaign/list', [SaleDashboardController::class, 'listCampaign']);
        Route::get('campaign/delete/{id}', [SaleDashboardController::class, 'deleteCampaign']);

        Route::post('brand/create', [SaleDashboardController::class, 'registerBrand']);
        Route::get('brand/list', [SaleDashboardController::class, 'listBrand']);
        Route::get('brand/detail/{id}', [SaleDashboardController::class, 'detailBrand']);

        Route::get('wallet/transactions', [SaleDashboardController::class, 'walletTransactions']);

        Route::get('notifications', [SaleDashboardController::class, 'notifications']);
        Route::get('ledger-commission-transactions', [SaleDashboardController::class, 'ledgerTransactions']);

        Route::get('sales-terms-and-conditions', [SaleDashboardController::class, 'salesTermsAndConditions']);
        Route::get('sales-privacy-policy', [SaleDashboardController::class, 'salesPrivacyPolicy']);
    });
});

