<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PrintController;

/*
|--------------------------------------------------------------------------
| Web Routes - 11 No Auliapur Union Parishad
|--------------------------------------------------------------------------
*/

// =======================
// ১. পাবলিক সাইট ও আবেদন রাউটস
// =======================
Route::get('/', [PublicController::class, 'index'])->name('home');

// সার্বজনীন ট্র্যাকিং ও যাচাই সার্চ (মোবাইল / NID / নাম দিয়ে এক ক্লিকে সকল রেকর্ড)
Route::post('/track-application', [PublicController::class, 'trackApplication'])->name('track.application');

// আবেদন দাখিল রাউটস
Route::post('/apply/citizenship', [PublicController::class, 'submitCitizenship'])->name('apply.citizenship');
Route::post('/apply/trade-license', [PublicController::class, 'submitTradeLicense'])->name('apply.trade');
Route::post('/apply/trade-renewal', [PublicController::class, 'submitTradeRenewal'])->name('apply.trade.renewal');
Route::post('/apply/family', [PublicController::class, 'submitFamily'])->name('apply.family');
Route::post('/apply/warishan', [PublicController::class, 'submitWarishan'])->name('apply.warishan');
Route::post('/apply/general', [PublicController::class, 'submitGeneral'])->name('apply.general');

// =======================
// ২. সরাসরি A4 সনদ ও আবেদন কপি প্রিন্ট রাউটস
// =======================
Route::prefix('print')->name('print.')->group(function () {
    Route::get('/citizenship/{appId}', [PrintController::class, 'printCitizenship'])->name('citizenship');
    Route::get('/trade/{appId}', [PrintController::class, 'printTradeLicense'])->name('trade');
    Route::get('/family/{appId}', [PrintController::class, 'printFamily'])->name('family');
    Route::get('/warishan/{appId}', [PrintController::class, 'printWarishan'])->name('warishan');
    Route::get('/general/{appId}', [PrintController::class, 'printGeneral'])->name('general');
    
    // আবেদন কপি প্রিন্ট (Pending অবস্থায়)
    Route::get('/application-copy/{appId}', [PrintController::class, 'printAppCopy'])->name('app.copy');
});

// =======================
// ৩. প্রশাসনিক (Admin) লগইন ও প্যানেল রাউটস
// =======================
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// সুরক্ষিত এডমিন কন্ট্রোল API (AJAX / Live)
Route::prefix('admin-api')->group(function () {
    Route::get('/dashboard-stats', [AdminController::class, 'getDashboardStats']);
    Route::post('/update-status', [AdminController::class, 'updateStatus']);
    Route::post('/delete-record', [AdminController::class, 'deleteRecord']);
    
    // প্রতিটি মডিউলের তালিকা
    Route::get('/citizenship-list', [AdminController::class, 'getCitizenshipList']);
    Route::get('/trade-list', [AdminController::class, 'getTradeList']);
    Route::get('/family-list', [AdminController::class, 'getFamilyList']);
    Route::get('/warishan-list', [AdminController::class, 'getWarishanList']);
    Route::get('/general-list', [AdminController::class, 'getGeneralList']);
    Route::get('/tax-list', [AdminController::class, 'getTaxList']);
    
    // তথ্য সংশোধন (Edit Data)
    Route::get('/get-details/{type}/{appId}', [AdminController::class, 'getDetailsForEdit']);
    Route::post('/save-edit/{type}', [AdminController::class, 'saveEditedData']);
    
    // ইউপি সেটিংস (চেয়ারম্যান ও সচিব)
    Route::get('/up-settings', [AdminController::class, 'getUPSettings']);
    Route::post('/save-up-settings', [AdminController::class, 'saveUPSettings']);
    
    // এডমিন পারমিশন ও ইউজার ম্যানেজমেন্ট
    Route::get('/users-list', [AdminController::class, 'getAdminUsers']);
    Route::post('/save-user', [AdminController::class, 'saveAdminUser']);
    Route::post('/delete-user', [AdminController::class, 'deleteAdminUser']);
});
