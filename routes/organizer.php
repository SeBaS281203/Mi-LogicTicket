<?php

use App\Http\Controllers\Organizer\OrganizerDashboardController;
use App\Http\Controllers\Organizer\OrganizerEventController;
use App\Http\Controllers\Organizer\OrganizerSalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Organizer Routes
|--------------------------------------------------------------------------
| Panel de Organizadores. Requiere middleware ['auth', 'organizer'].
*/
Route::middleware(['auth', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/', [OrganizerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', OrganizerEventController::class)->except('show');
    Route::get('sales', [OrganizerSalesController::class, 'index'])->name('sales.index');
    Route::get('buyers', [OrganizerSalesController::class, 'buyers'])->name('buyers.index');
    Route::get('reports', [OrganizerSalesController::class, 'report'])->name('reports.index');
    Route::get('reports/export', [OrganizerSalesController::class, 'exportReport'])->name('reports.export');
    Route::get('reports/export/pdf', [OrganizerSalesController::class, 'exportReportPdf'])->name('reports.export.pdf');
});
