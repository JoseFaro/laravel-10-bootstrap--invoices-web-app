<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ExpensesCategoriesController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SitesController;
use App\Http\Controllers\SiteServicesController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\UnitsController;

Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::middleware('auth')->group(function () {
    Route::get('activities/getDataByClient/{client_id?}', [ActivitiesController::class, 'getDataByClient'])->name('activities.getDataByClient');
    Route::get('activities/search', [ActivitiesController::class, 'search'])->name('activities.search');
    Route::resource('activities', ActivitiesController::class);
    
    Route::resource('clients', ClientsController::class);

    Route::get('dashboard/{is_default?}', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
    
    Route::get('expenses/search', [ExpensesController::class, 'search'])->name('expenses.search');
    Route::resource('expenses', ExpensesController::class);

    Route::resource('expenses-categories', ExpensesCategoriesController::class);

    Route::resource('invoices', InvoicesController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('services', ServicesController::class);

    Route::get('site-services/create/{site_id}', [SiteServicesController::class, 'create'])->name('site-services.create-for-site');
    Route::get('site-services/getDataBySite/{site_id?}', [SiteServicesController::class, 'getDataBySite'])->name('site-services.getDataBySite');
    Route::get('site-services/site/{site_id}', [SiteServicesController::class, 'index'])->name('site-services.site');
    Route::resource('site-services', SiteServicesController::class);

    Route::get('sites/getDataByClient/{client_id?}', [SitesController::class, 'getDataByClient'])->name('sites.getDataByClient');
    Route::resource('sites', SitesController::class);

    Route::resource('suppliers', SuppliersController::class);

    Route::resource('units', UnitsController::class);
});

require __DIR__.'/auth.php';
