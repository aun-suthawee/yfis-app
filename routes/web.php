<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisasterReportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormSelectionController;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/export/pdf', [ExportController::class, 'dashboardPdf'])->name('dashboard.export.pdf');
Route::get('/dashboard/export/excel', [ExportController::class, 'dashboardExcel'])->name('dashboard.export.excel');
Route::get('/disaster-reports/dataset', [DisasterReportController::class, 'dataset'])->name('disaster.dataset');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/forms', [\App\Http\Controllers\FormSelectionController::class, 'index'])->name('forms.index');

Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/disaster-reports/filter', [DisasterReportController::class, 'filter'])
        ->name('disaster.filter');

    Route::get('/disaster-reports/export/excel', [DisasterReportController::class, 'exportExcel'])
        ->name('export.excel');
    Route::get('/disaster-reports/export/csv', [DisasterReportController::class, 'exportCsv'])
        ->name('export.csv');
    Route::get('/disaster-reports/export/json', [DisasterReportController::class, 'exportJson'])
        ->name('export.json');

    Route::get('/disaster-reports/export/{type}', [ExportController::class, 'disasterReports'])
        ->whereIn('type', ['excel', 'csv', 'json']);

    Route::get('/disaster-reports/confirmation/{disaster_report}', [DisasterReportController::class, 'confirmation'])
        ->name('disaster.confirmation');

    Route::post('/disaster-reports/{disaster_report}/publish', [DisasterReportController::class, 'publish'])
        ->name('disaster.publish');
    Route::post('/disaster-reports/{disaster_report}/unpublish', [DisasterReportController::class, 'unpublish'])
        ->name('disaster.unpublish');
    Route::post('/disaster-reports/bulk-publish', [DisasterReportController::class, 'bulkPublish'])
        ->name('disaster.bulk-publish');

    Route::resource('disaster-reports', DisasterReportController::class)->names([
        'index' => 'disaster.index',
        'create' => 'disaster.create',
        'store' => 'disaster.store',
        'show' => 'disaster.show',
        'edit' => 'disaster.edit',
        'update' => 'disaster.update',
        'destroy' => 'disaster.destroy',
    ]);

    Route::resource('shelters', \App\Http\Controllers\ShelterController::class);
});
