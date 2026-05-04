<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomRoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\FeedbackQuestionController;
use App\Http\Controllers\VoucherBrandController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Admin\PaymentSplitController;
use App\Http\Controllers\Admin\BrandCategoryController;

Route::get('/', [LoginController::class, 'login'])->name('admin.login');
Route::post('/auth-login', [LoginController::class, 'submit'])->name('admin.auth.login');

// Legacy image upload endpoint used by shared image-process partials.
Route::post('/image-upload', function () {
    return response()->json([
        'status' => false,
        'message' => 'Image crop upload endpoint is not configured.',
    ], 501);
})->name('image-upload');

// Admin Dashboard
Route::group(['prefix' => 'admin', 'middleware' => ['admin:auth']], function() {

        // User Level Management
        Route::group(['prefix' => 'user-levels'], function() {
            Route::get('/', [\App\Http\Controllers\Admin\UserLevelController::class, 'index'])->name('admin.user-level.index');
            Route::get('/create', [\App\Http\Controllers\Admin\UserLevelController::class, 'create'])->name('admin.user-level.create');
            Route::post('/store', [\App\Http\Controllers\Admin\UserLevelController::class, 'store'])->name('admin.user-level.store');
            Route::get('/edit/{id}', [\App\Http\Controllers\Admin\UserLevelController::class, 'edit'])->name('admin.user-level.edit');
            Route::post('/update/{id}', [\App\Http\Controllers\Admin\UserLevelController::class, 'update'])->name('admin.user-level.update');
            Route::post('/delete/{id}', [\App\Http\Controllers\Admin\UserLevelController::class, 'destroy'])->name('admin.user-level.delete');
        });
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('admin.profile');
    Route::post('/update-profile/{id}', [DashboardController::class, 'updateProfile'])->name('admin.profile.update');
    Route::get('/users', [DashboardController::class, 'users'])->name('admin.user');
    Route::get('/user-view/{id}', [DashboardController::class, 'viewUser'])->name('admin.user.view');
    Route::get('/user-edit/{id}', [DashboardController::class, 'editUser'])->name('admin.user.edit');
    Route::get('/user-activity-logs/{id}', [DashboardController::class, 'userActivityLogs'])->name('admin.user.activity.logs');
    Route::post('/user-update/{id}', [DashboardController::class, 'updateUser'])->name('admin.user.update');
    Route::get('/delete-user/{id}', [DashboardController::class, 'deleteUser'])->name('admin.user.delete');
    Route::get('/user-wallet', [DashboardController::class, 'userWallet'])->name('admin.user.wallet');
    Route::get('/user-wallet-transactions', [DashboardController::class, 'userWalletTransactions'])->name('admin.user-wallet-transactions');
    Route::get('/feedback-questions', [FeedbackQuestionController::class, 'index'])->name('admin.feedback-questions.index');
    Route::post('/feedback-questions', [FeedbackQuestionController::class, 'store'])->name('admin.feedback-questions.store');
    Route::post('/feedback-questions/update/{id}', [FeedbackQuestionController::class, 'update'])->name('admin.feedback-questions.update');
    Route::post('/feedback-questions/toggle-status/{id}', [FeedbackQuestionController::class, 'toggleStatus'])->name('admin.feedback-questions.toggle-status');
    Route::post('/feedback-questions/delete/{id}', [FeedbackQuestionController::class, 'destroy'])->name('admin.feedback-questions.delete');
    Route::get('/feedback', [FeedbackQuestionController::class, 'feedbackList'])->name('admin.feedback.list');
    Route::get('/brands', [DashboardController::class, 'brands'])->name('admin.brand');
    Route::get('/brands/{id}', [DashboardController::class, 'showBrand'])->name('admin.brand.view');
    Route::post('/brands/{id}', [DashboardController::class, 'updateBrand'])->name('admin.brand.updateStatus');
    Route::get('/roles-permissions', [DashboardController::class, 'rolesNdPermission'])->name('admin.roles-nd-permissions');
    Route::post('/roles-permissions/store', [DashboardController::class, 'storeRolesNdPermission'])->name('admin.roles-nd-permissions.store');

    // Reports
    Route::get('reports/brand-report', [ReportController::class, 'index'])->name('admin.brand.reports');
    Route::get('reports/campaign-report', [ReportController::class, 'campaignReport'])->name('admin.campaign.reports');
    Route::get('reports/post-report', [ReportController::class, 'postReport'])->name('admin.post.reports');
    Route::get('activity-logs', [ReportController::class, 'activityLogs'])->name('admin.activity.logs');
    
    // BannerController
    Route::group(['prefix' => 'banners'], function() {
        Route::get('/', [BannerController::class, 'list'])->name('admin.banner.list');
        Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('admin.banner.edit');
        Route::post('/store', [BannerController::class, 'store'])->name('admin.banner.store');
        Route::post('/update/{id}', [BannerController::class, 'update'])->name('admin.banner.update');
        Route::post('/status', [BannerController::class, 'status'])->name('admin.banner.status');
        Route::post('/delete', [BannerController::class, 'delete'])->name('admin.banner.delete');
    });

    // CampaignController
    Route::group(['prefix' => 'campaigns'], function() {
        Route::get('/', [CampaignController::class, 'list'])->name('admin.campaign.list');
        Route::get('/create', [CampaignController::class, 'create'])->name('admin.campaign.add');
        Route::get('/show/{id}', [CampaignController::class, 'show'])->name('admin.campaign.show');
        Route::get('/edit/{id}', [CampaignController::class, 'edit'])->name('admin.campaign.edit');
        Route::post('/store', [CampaignController::class, 'store'])->name('admin.campaign.store');
        Route::post('/update/{id}', [CampaignController::class, 'update'])->name('admin.campaign.update');
        Route::post('/status', [CampaignController::class, 'status'])->name('admin.campaign.status');
        Route::post('/delete', [CampaignController::class, 'delete'])->name('admin.campaign.delete');
    });
    
    // CampaignController
    Route::group(['prefix' => 'sales'], function() {
        Route::get('/', [SaleController::class, 'list'])->name('admin.sale.list');
        Route::get('/create', [SaleController::class, 'create'])->name('admin.sale.add');
        Route::get('/show/{id}', [SaleController::class, 'show'])->name('admin.sale.show');
        Route::get('/edit/{id}', [SaleController::class, 'edit'])->name('admin.sale.edit');
        Route::post('/store', [SaleController::class, 'store'])->name('admin.sale.store');
        Route::post('/update/{id}', [SaleController::class, 'update'])->name('admin.sale.update');
        Route::post('/status', [SaleController::class, 'status'])->name('admin.sale.status');
        Route::post('/delete', [SaleController::class, 'delete'])->name('admin.sale.delete');
        Route::get('/wallet-transactions', [SaleController::class, 'walletTransactions'])->name('admin.sale.wallet-transactions');
        
        Route::get('/ledger-transactions', [SaleController::class, 'ledgerTransactions'])->name('admin.sale.ledger-transactions');
        Route::post('/update-ledger-transactions-status', [SaleController::class, 'updateLedgerTransactionStatus'])->name('admin.sale.update-ledger-transactions-status');
        
    });

    Route::group(['prefix' => 'campaigns-transactions'], function() {
        Route::get('/', [CampaignController::class, 'campaignTransctions'])->name('admin.campaigns-transactions.list');
    });

    Route::group(['prefix' => 'voucher-brands', 'as' => 'admin.voucher-brand.'], function() {
        Route::get('/', [VoucherBrandController::class, 'index'])->name('index');
        Route::get('/create', [VoucherBrandController::class, 'create'])->name('create');
        Route::post('/store', [VoucherBrandController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [VoucherBrandController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [VoucherBrandController::class, 'update'])->name('update');
        Route::post('/status', [VoucherBrandController::class, 'status'])->name('status');
        Route::post('/delete', [VoucherBrandController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'vouchers', 'as' => 'admin.voucher.'], function() {
        Route::get('/', [VoucherController::class, 'index'])->name('index');
        Route::get('/create', [VoucherController::class, 'create'])->name('create');
        Route::post('/store', [VoucherController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [VoucherController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [VoucherController::class, 'update'])->name('update');
        Route::post('/active-status', [VoucherController::class, 'activeStatus'])->name('active-status');
        Route::post('/delete', [VoucherController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'brand-categories', 'as' => 'admin.brand-category.'], function() {
        Route::get('/', [BrandCategoryController::class, 'index'])->name('index');
        Route::get('/create', [BrandCategoryController::class, 'create'])->name('create');
        Route::post('/store', [BrandCategoryController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [BrandCategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [BrandCategoryController::class, 'update'])->name('update');
        Route::post('/status', [BrandCategoryController::class, 'status'])->name('status');
        Route::post('/delete', [BrandCategoryController::class, 'delete'])->name('delete');
    });
    
    Route::get('/settings', [DashboardController::class, 'settings'])->name('admin.settings');
    Route::post('/update-wallet-status', [DashboardController::class, 'updateUserWalletStatus'])->name('admin.user.update-wallet-status');
    Route::post('/approve-withdrawal', [DashboardController::class, 'approveWithdrawal'])->name('admin.user.approve-withdrawal');
    Route::post('/update-wallet-withdrawal-freeze', [DashboardController::class, 'updateUserWalletWithdrawalFreeze'])->name('admin.user.update-wallet-withdrawal-freeze');
    Route::post('/update-user-status', [DashboardController::class, 'updateUserStatus'])->name('admin.user.update-user-status');

    Route::post('/update-seller-status', [DashboardController::class, 'updateSellerStatus'])->name('admin.seller.update-account-status');
    // update-wallet-status

    // Route::get('/static-pages', [DashboardController::class, 'staticPages'])->name('admin.static-pages');

    Route::get('/website-info/terms_condition', [DashboardController::class, 'terms_condition'])->name('admin.business-settings.terms-condition');
    Route::post('/website-info/update_terms_condition', [DashboardController::class, 'updateTermsCondition'])->name('admin.business-settings.update-terms');
    Route::get('/website-info/privacy_policy', [DashboardController::class, 'privacy_policy'])->name('admin.business-settings.privacy-policy');
    Route::post('/website-info/update_privacy_policy', [DashboardController::class, 'privacy_policy_update'])->name('admin.business-settings.privacy-policy-update');
    
    Route::get('/website-info/brand-terms_condition', [DashboardController::class, 'brand_terms_condition'])->name('admin.business-settings.brand-terms-condition');
    Route::post('/website-info/brand-update_terms_condition', [DashboardController::class, 'brand_updateTermsCondition'])->name('admin.business-settings.brand-update-terms');
    Route::get('/website-info/brand-privacy_policy', [DashboardController::class, 'brand_privacy_policy'])->name('admin.business-settings.brand-privacy-policy');
    Route::post('/website-info/brand-update_privacy_policy', [DashboardController::class, 'brand_privacy_policy_update'])->name('admin.business-settings.brand-privacy-policy-update');
    Route::get('/brand-management/campaign-guideline', [DashboardController::class, 'campaign_guideline'])->name('admin.business-settings.campaign-guideline');
    Route::post('/brand-management/campaign-guideline', [DashboardController::class, 'campaign_guideline_update'])->name('admin.business-settings.campaign-guideline-update');
    
    // Sales Terms & Conditions & Privacy Policy
    Route::get('/website-info/sales-terms_condition', [DashboardController::class, 'sales_terms_condition'])->name('admin.business-settings.sales-terms-condition');
    Route::post('/website-info/sales-update_terms_condition', [DashboardController::class, 'sales_updateTermsCondition'])->name('admin.business-settings.sales-update-terms');
    Route::get('/website-info/sales-privacy_policy', [DashboardController::class, 'sales_privacy_policy'])->name('admin.business-settings.sales-privacy-policy');
    Route::post('/website-info/sales-update_privacy_policy', [DashboardController::class, 'sales_privacy_policy_update'])->name('admin.business-settings.sales-privacy-policy-update');

    Route::post('/update-website-info', [DashboardController::class, 'updateInfo'])->name('admin.business-settings.updateInfo');

    // Popup Banner Routes
    Route::get('/business-settings/popup-banner', [DashboardController::class, 'popupBanner'])->name('admin.business-settings.popup-banner');
    Route::post('/business-settings/popup-banner-update', [DashboardController::class, 'popupBannerUpdate'])->name('admin.business-settings.popup-banner-update');

    // Notification & task system
    Route::get('/dashboard/notification-counts', [DashboardController::class, 'notificationCounts'])->name('admin.dashboard.notification-counts');
    Route::get('/notifications/feed', [DashboardController::class, 'notificationFeed'])->name('admin.notifications.feed');
    Route::post('/notifications/mark-read', [DashboardController::class, 'markNotificationsRead'])->name('admin.notifications.mark-read');

    Route::get('/logout', [DashboardController::class, 'logout'])->name('admin.auth.logout');

    Route::group(['prefix' => 'custom-role', 'as' => 'admin.custom-role.'], function () {
        Route::get('create', [CustomRoleController::class, 'create'])->name('create');
        Route::post('create', [CustomRoleController::class, 'store'])->name('store');
        Route::get('update/{id}', [CustomRoleController::class, 'edit'])->name('update');
        Route::post('update/{id}', [CustomRoleController::class, 'update']);
        Route::post('employee-role-status', [CustomRoleController::class, 'employee_role_status_update'])->name('employee-role-status');
        Route::get('export', [CustomRoleController::class, 'export'])->name('export');
        Route::post('delete', [CustomRoleController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'employee', 'as' => 'admin.employee.'], function () {
        Route::get('add-new', [EmployeeController::class, 'add_new'])->name('add-new');
        Route::post('add-new', [EmployeeController::class, 'store']);
        Route::get('list', [EmployeeController::class, 'list'])->name('list');
        Route::get('update/{id}', [EmployeeController::class, 'edit'])->name('update');
        Route::post('update/{id}', [EmployeeController::class, 'update']);
        Route::post('status', [EmployeeController::class, 'status'])->name('status');
    });

    Route::group(['prefix' => 'notification', 'as' => 'admin.notification.'], function () {
        Route::get('add-new', [NotificationController::class, 'index'])->name('add-new');
        Route::post('store', [NotificationController::class, 'store'])->name('store');
        Route::get('edit/{id}', [NotificationController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [NotificationController::class, 'update'])->name('update');
        Route::post('status', [NotificationController::class, 'status'])->name('status');
        Route::post('resend-notification', [NotificationController::class, 'resendNotification'])->name('resend-notification');
        Route::post('delete', [NotificationController::class, 'delete'])->name('delete');
    }); 
  
    // Support-ticket
    Route::get('/support-ticket/view', [SupportTicketController::class, 'index'])
    ->name('admin.support-ticket.view-support');

    Route::get('/support-ticket/{id}', [SupportTicketController::class, 'view'])
    ->name('admin.support-ticket.singleTicket');

    Route::post('/admin/support-ticket/reply/{id}', [SupportTicketController::class, 'reply'])
    ->name('admin.support-ticket.replay');

    // Payment Split Settings
    Route::get('business-settings/payment-split', [\App\Http\Controllers\Admin\PaymentSplitController::class, 'edit'])->name('admin.payment-split.edit');
    Route::post('business-settings/payment-split', [\App\Http\Controllers\Admin\PaymentSplitController::class, 'update'])->name('admin.payment-split.update');
});

Route::post('/support-ticket/close/{id}', [SupportTicketController::class, 'close'])
    ->name('admin.support-ticket.close');
 
// SocialAuthController
Route::get('auth/{service}', [SocialAuthController::class, 'redirectToProvider'])->name('service-login');
Route::get('auth/{service}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('service-callback');

