<?php

use Illuminate\Support\Facades\Route;

//New Route
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\admin\{UserListController, ClientListController};
use App\Http\Controllers\client\{ClientAuthController, SlotBookingController, TrainingController};

//End New Route

use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\tables\Basic as TablesBasic;

// Main Page Route
//Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
Route::get('/', function () {
    return redirect()->route('auth-login-basic');
});


// User Interface
//Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');

//admin
Route::prefix('admin')->group(function () {
    //authentication
    // Show pages
    Route::get('/login', [LoginBasic::class, 'index'])->name('auth-login-basic');
    Route::get('/register', [RegisterBasic::class, 'index'])->name('auth-register-basic');
    Route::get('/forgot-password', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

    // Form submission
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    //for reset password
    Route::post('/forgot-password', [AdminAuthController::class, 'resetPassword'])->name('admin.reset-password');

    // Protected admin dashboard
    Route::middleware('admin', 'prevent-back-history')->group(function () {
        Route::get('/dashboard', [Analytics::class, 'index'])->name('admin.dashboard');
        Route::get('/profile', [AdminAuthController::class, 'profile'])->name('admin.profile');
        Route::post('/update-profile', [AdminAuthController::class, 'updateProfile'])->name('admin.profile.update');
      
        Route::prefix('master-module')->group(function(){
        //master module/client
            Route::prefix('clients')->group(function() {
                Route::get('/', [ClientListController::Class, 'index'])->name('admin.client.list');
                Route::post('/store', [ClientListController::class, 'store'])->name('admin.client.store');
                Route::get('/edit/{id}', [ClientListController::class, 'edit'])->name('admin.client.edit');
                Route::post('/update', [ClientListController::class, 'update'])->name('admin.client.update');
                Route::get('/status/{id}', [ClientListController::class, 'status'])->name('admin.client.status');
                Route::post('/delete', [ClientListController::class, 'delete'])->name('admin.client.delete'); 
                Route::get('/training-status/{id}', [ClientListController::class, 'trainingStatus'])->name('admin.client.trainingStatus');
                Route::get('/slot-date/{id}', [ClientListController::class, 'getSlotdate'])->name('admin.client.getSlotdate');
                Route::post('/slot-date/save', [ClientListController::class, 'saveSlotdate'])->name('admin.client.saveSlotdate');
            });

            //master module/distributor, site ready, remarks
            Route::get('/distributor-list', [ClientListController::class, 'distributorList'])->name('admin.slot-booking.distributorList');
            Route::get('/site-ready/{id}', [ClientListController::class, 'siteReady'])->name('admin.client.siteReady');
            Route::post('/save-remarks-site-ready', [ClientListController::class, 'savesiteReadyRemarks'])->name('admin.client.savesiteReadyRemarks');

            //master module/distributor, training, remarks
            Route::get('training-done/{id}', [ClientListController::class, 'trainingDone'])->name('admin.client.trainingDone');
            Route::post('/save-remarks-training', [ClientListController::class, 'savetrainingRemarks'])->name('admin.client.savetrainingRemarks');

            Route::get('/export', [ClientListController::class, 'exportDistList'])->name('admin.client.exportDistList');

            //site readiness form
            Route::get('/site-readiness-form/{id}', [ClientListController::class, 'siteReadinessForm'])->name('admin.client.siteReadinessForm');
            Route::post('/site-readiness-form-submit', [ClientListController::class, 'storeSiteReadiness'])->name('admin.client.storeSiteReadiness');
            Route::get('/site-status/{slot_booking_id}/{field}', [ClientListController::class, 'siteStatus'])->name('admin.client.siteStatus');
        });
    });
});


//client
Route::get('/login',[ClientAuthController::class, 'showLoginForm'])->name('client.login');
Route::post('/login', [ClientAuthController::class, 'login'])->name('client.login.submit');
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
Route::get('/forgot-password', [ClientAuthController::class, 'passwordForm'])->name('client.reset-password-basic');
Route::post('/forgot-password', [ClientAuthController::class, 'resetPassword'])->name('client.reset-password');

Route::middleware(['client', 'prevent-back-history'])->prefix('user')->group(function(){
    Route::get('/dashboard', function() {
        return view('client.dashboard');
    })->name('client.dashboard');

    Route::prefix('master-module')->group(function() {
        Route::get('/slot-booking-form', [SlotBookingController::class, 'index'])->name('client.slot-booking.index');
        Route::post('/form/check-slot', [SlotBookingController::class, 'checkSlot'])->name('client.slot-booking.checkSlot');
        Route::post('/form/store', [SlotBookingController::class, 'store'])->name('client.slot-booking.store');
        Route::get('/form/available-date', [SlotBookingController::class, 'getAvailableDates'])->name('client.slot-booking.getAvailableDates');
        Route::get('/distributor-list', [SlotBookingController::class, 'distributorList'])->name('client.slot-booking.distributorList');

        Route::get('training-done/{id}', [TrainingController::class, 'trainingDone'])->name('client.trainingDone');
        Route::post('/save-remarks-training', [TrainingController::class, 'savetrainingRemarks'])->name('client.savetrainingRemarks');
    });
});
