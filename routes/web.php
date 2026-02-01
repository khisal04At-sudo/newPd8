<?php

use App\Http\Controllers\Auth\OrganizationRegisterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VolunteerRegisterController;
use App\Http\Controllers\Auth\OtpVerificationController;


Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [\App\Http\Controllers\Dashboard\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Public Opportunity Routes
Route::get('/opportunities', [\App\Http\Controllers\PublicOpportunityController::class, 'index'])->name('opportunities.index');
Route::get('/opportunities/{opportunity}', [\App\Http\Controllers\PublicOpportunityController::class, 'show'])->name('opportunities.show');

// Public Profile Routes
Route::get('/users/{user}/profile', [\App\Http\Controllers\ProfileController::class, 'showUser'])->name('users.profile');
Route::get('/organizations/{organization}/profile', [\App\Http\Controllers\ProfileController::class, 'showOrganization'])->name('organizations.profile');

Route::middleware('auth')->group(function () {
    Route::post('/opportunities/{opportunity}/apply', [\App\Http\Controllers\OpportunityActionController::class, 'apply'])->name('opportunities.apply');
    Route::post('/opportunities/{opportunity}/save', [\App\Http\Controllers\OpportunityActionController::class, 'save'])->name('opportunities.save');
    Route::post('/opportunities/{opportunity}/share', [\App\Http\Controllers\OpportunityActionController::class, 'share'])->name('opportunities.share');
    
    // Volunteer Dashboard Routes
    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\VolunteerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/applications', [\App\Http\Controllers\VolunteerDashboardController::class, 'applications'])->name('applications');
        Route::get('/saved', [\App\Http\Controllers\VolunteerDashboardController::class, 'saved'])->name('saved');
        Route::post('/opportunities/{opportunity}/save', [\App\Http\Controllers\VolunteerDashboardController::class, 'saveOpportunity'])->name('opportunities.save');
        Route::delete('/opportunities/{opportunity}/unsave', [\App\Http\Controllers\VolunteerDashboardController::class, 'unsaveOpportunity'])->name('opportunities.unsave');
    });
    
    // Application Routes
    Route::get('/opportunities/{opportunity}/apply-form', [\App\Http\Controllers\ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/opportunities/{opportunity}/submit-application', [\App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.store');
    Route::delete('/applications/{application}/withdraw', [\App\Http\Controllers\ApplicationController::class, 'withdraw'])->name('applications.withdraw');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('/auth/register-volunteer', [VolunteerRegisterController::class, 'create'])
    ->name('register.volunteer');



Route::get('/auth/register-organization', [OrganizationRegisterController::class, 'createOrganization'])
    ->name('register.organization');

Route::post('/auth/register-organization', [OrganizationRegisterController::class, 'storeOrganization'])
    ->name('register.organization.store');


Route::post('/auth/register-volunteer', [VolunteerRegisterController::class, 'store'])
    ->name('register.volunteer.store');


    //////////////////////////////////////
Route::get('/choose-account-type', function () {
    return view('auth.choose-account-type');
})->name('choose.account.type');

Route::get('/register/{type}', function ($type) {
    if(!in_array($type, ['user','organization'])) {
        abort(404);
    }

    return redirect()->route($type == 'user' ? 'register.volunteer' : 'register.organization');
})->name('register.type');

// OTP Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])
        ->name('verify-otp');
    
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])
        ->name('verify-otp.submit');
    
    Route::post('/verify-otp/resend', [OtpVerificationController::class, 'resend'])
        ->name('verify-otp.resend');
        
    Route::get('/profile/complete', [\App\Http\Controllers\ProfileCompletionController::class, 'show'])
        ->name('profile.complete');
    Route::post('/profile/complete', [\App\Http\Controllers\ProfileCompletionController::class, 'store'])
        ->name('profile.complete.store');

    // Organization Verification
    Route::get('/organization/verify-documents', [\App\Http\Controllers\Auth\OrganizationVerificationController::class, 'showUploadForm'])
        ->name('organization.verify.documents');
    Route::post('/organization/verify-documents', [\App\Http\Controllers\Auth\OrganizationVerificationController::class, 'storeDocuments'])
        ->name('organization.verify.documents.store');

    // Dashboard Sections
    Route::get('/dashboard/profile', [\App\Http\Controllers\Dashboard\UserProfileController::class, 'show'])
        ->name('dashboard.profile');
    
    // Organization Specific Routes
    Route::middleware(['auth'])->prefix('organization')->name('organization.')->group(function () {
        // 1. Institution Profile
        Route::get('/profile', [\App\Http\Controllers\Dashboard\OrganizationProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [\App\Http\Controllers\Dashboard\OrganizationProfileController::class, 'update'])->name('profile.update');

        // 2. Manage Opportunities
        Route::resource('opportunities', \App\Http\Controllers\Dashboard\OpportunityManagementController::class);
        Route::post('/opportunities/{opportunity}/start', [\App\Http\Controllers\Dashboard\OpportunityManagementController::class, 'startExecution'])->name('opportunities.start');
        Route::post('/opportunities/{opportunity}/complete', [\App\Http\Controllers\Dashboard\OpportunityManagementController::class, 'completeExecution'])->name('opportunities.complete');
        Route::post('/opportunities/{opportunity}/cancel', [\App\Http\Controllers\Dashboard\OpportunityManagementController::class, 'cancelOpportunity'])->name('opportunities.cancel');
        Route::get('/opportunities/{opportunity}/tracking', [\App\Http\Controllers\Dashboard\OpportunityManagementController::class, 'tracking'])->name('opportunities.tracking');

        // 3. Volunteers / Applications
        Route::get('/applications', [\App\Http\Controllers\Dashboard\ApplicationManagementController::class, 'index'])->name('applications.index');
        Route::post('/applications/{application}/status', [\App\Http\Controllers\Dashboard\ApplicationManagementController::class, 'updateStatus'])->name('applications.updateStatus');
        Route::post('/applications/{application}/tracking', [\App\Http\Controllers\Dashboard\ApplicationManagementController::class, 'updateTracking'])->name('applications.updateTracking');

        // 4. Certificates
        Route::get('/certificates', [\App\Http\Controllers\Dashboard\CertificateManagementController::class, 'index'])->name('certificates.index');
        Route::post('/certificates/issue', [\App\Http\Controllers\Dashboard\CertificateManagementController::class, 'issue'])->name('certificates.issue');
    });

    Route::get('/dashboard/opportunities', [\App\Http\Controllers\Dashboard\MyOpportunitiesController::class, 'index'])
        ->name('dashboard.opportunities');
    Route::get('/dashboard/notifications', [\App\Http\Controllers\Dashboard\NotificationController::class, 'index'])
        ->name('dashboard.notifications');
    Route::get('/dashboard/achievements', [\App\Http\Controllers\Dashboard\AchievementController::class, 'index'])
        ->name('dashboard.achievements');
    Route::get('/dashboard/messages', [\App\Http\Controllers\Dashboard\MessageController::class, 'index'])
        ->name('dashboard.messages');

    // Admin Routes
    Route::prefix('admin')->group(function () {
        // Redirect 'admin.login' named route to the main login for consistency
        Route::get('/login', fn () => redirect()->route('login'))->name('admin.login');
        
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
            
            // Organization Approval
            Route::get('/organizations', [\App\Http\Controllers\Admin\OrganizationApprovalController::class, 'index'])->name('admin.organizations.index');
            Route::get('/organizations/{organization}', [\App\Http\Controllers\Admin\OrganizationApprovalController::class, 'show'])->name('admin.organizations.show');
            Route::post('/organizations/{organization}/approve', [\App\Http\Controllers\Admin\OrganizationApprovalController::class, 'approve'])->name('admin.organizations.approve');
            Route::post('/organizations/{organization}/reject', [\App\Http\Controllers\Admin\OrganizationApprovalController::class, 'reject'])->name('admin.organizations.reject');
            Route::post('/organizations/{organization}/request-documents', [\App\Http\Controllers\Admin\OrganizationApprovalController::class, 'requestDocuments'])->name('admin.organizations.request-documents');
            Route::post('/organizations/{organization}/toggle-auto-publish', [\App\Http\Controllers\Admin\OrganizationApprovalController::class, 'toggleAutoPublish'])->name('admin.organizations.toggle-auto-publish');

            // إدارة الفرص (المراجعة)
            Route::get('/opportunities-review', [\App\Http\Controllers\Admin\AdminOpportunityReviewController::class, 'index'])->name('admin.opportunities.index');
            Route::get('/opportunities/{opportunity}/review', [\App\Http\Controllers\Admin\AdminOpportunityReviewController::class, 'show'])->name('admin.opportunities.show');
            Route::post('/opportunities/{opportunity}/publish', [\App\Http\Controllers\Admin\AdminOpportunityReviewController::class, 'publish'])->name('admin.opportunities.publish');
            Route::post('/opportunities/{opportunity}/request-changes', [\App\Http\Controllers\Admin\AdminOpportunityReviewController::class, 'requestChanges'])->name('admin.opportunities.request-changes');
            Route::post('/opportunities/{opportunity}/reject', [\App\Http\Controllers\Admin\AdminOpportunityReviewController::class, 'reject'])->name('admin.opportunities.reject');
            
            // User Management
            Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
            Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('admin.users.show');
            Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserManagementController::class, 'ban'])->name('admin.users.ban');
            Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UserManagementController::class, 'unban'])->name('admin.users.unban');
            Route::post('/users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleActive'])->name('admin.users.toggle-active');
            Route::post('/users/{user}/rating', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRating'])->name('admin.users.rating');
            
            Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
        });
    });
});



require __DIR__.'/auth.php';
