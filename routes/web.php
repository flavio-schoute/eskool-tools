<?php

use App\Http\Controllers\DebtorManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleOverviewController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('sales-overview', SaleOverviewController::class)->only(['index', 'store', 'show']);
    Route::resource('debtor-management', DebtorManagementController::class)->only(['index', 'store', 'show']);

    Route::post('/debtor-management', [DebtorManagementController::class, 'TransferDebtorToCollectionAgency'])
        ->name('debtor-management.transfer-debtor-to-collection-agency');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
