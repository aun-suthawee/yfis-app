<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisasterReportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FormSelectionController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\SchoolController as ApiSchoolController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// Dashboard & Public Exports
Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('dashboard.index');
});

Route::controller(ExportController::class)->prefix('dashboard/export')->name('dashboard.export.')->group(function () {
    Route::get('pdf', 'dashboardPdf')->name('pdf');
    Route::get('excel', 'dashboardExcel')->name('excel');
});

Route::get('/disaster-reports/dataset', [DisasterReportController::class, 'dataset'])->name('disaster.dataset');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// API Routes
Route::get('/api/schools/search', [ApiSchoolController::class, 'search'])->name('api.schools.search');

// Forms Selection
Route::get('/forms', [FormSelectionController::class, 'index'])->name('forms.index');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    
    // Disaster Reports
    Route::controller(DisasterReportController::class)->prefix('disaster-reports')->name('disaster.')->group(function () {
        Route::match(['get', 'post'], '/filter', 'filter')->name('filter');
        
        // Exports
        Route::prefix('export')->name('export.')->group(function () { // disaster.export.
            Route::get('excel', 'exportExcel')->name('excel'); // disaster.export.excel (Wait, original was export.excel)
            // The original names were 'export.excel', 'export.csv', 'export.json'. 
            // I should be careful not to break route names if they are used in views.
            // Let's keep original names for safety or update them if I can check usages.
            // Original: name('export.excel') -> route('export.excel')
            // If I nest them, it becomes disaster.export.excel.
            // I will keep them as they were or alias them.
            // Let's stick to the original names for these specific export routes to avoid breaking views.
        });
        
        Route::get('/confirmation/{disaster_report}', 'confirmation')->name('confirmation');
        Route::post('/{disaster_report}/publish', 'publish')->name('publish');
        Route::post('/{disaster_report}/unpublish', 'unpublish')->name('unpublish');
        Route::post('/bulk-publish', 'bulkPublish')->name('bulk-publish');
    });

    // Fix for export route names to match original
    Route::get('/disaster-reports/export/excel', [DisasterReportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/disaster-reports/export/csv', [DisasterReportController::class, 'exportCsv'])->name('export.csv');
    Route::get('/disaster-reports/export/json', [DisasterReportController::class, 'exportJson'])->name('export.json');
    
    Route::get('/disaster-reports/export/{type}', [ExportController::class, 'disasterReports'])
        ->whereIn('type', ['excel', 'csv', 'json']);

    // Disaster Report Resource
    Route::get('/form/flood-report', [DisasterReportController::class, 'create'])->name('disaster.create');
    Route::resource('disaster-reports', DisasterReportController::class)->except(['create'])->names([
        'index' => 'disaster.index',
        'store' => 'disaster.store',
        'show' => 'disaster.show',
        'edit' => 'disaster.edit',
        'update' => 'disaster.update',
        'destroy' => 'disaster.destroy',
    ]);

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Shelters
    Route::get('/form/shelters', [ShelterController::class, 'create'])->name('shelters.create');
    Route::resource('shelters', ShelterController::class)->except(['create']);
});
