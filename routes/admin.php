<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\LibroReclamacionAdminController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TendenciaController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Panel de Administración General. Requiere middleware ['auth', 'admin'].
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', AdminEventController::class)->only('index', 'show', 'edit', 'update', 'destroy');
    Route::post('events/{event}/approve', [AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('events/{event}/reject', [AdminEventController::class, 'reject'])->name('events.reject');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('cities', CityController::class)->except('show');
    Route::post('banners/bulk', [BannerController::class, 'bulkStore'])->name('banners.bulk-store');
    Route::resource('banners', BannerController::class)->except('show');
    Route::resource('tendencias', TendenciaController::class);
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/orders/excel', [ReportController::class, 'exportOrdersExcel'])->name('reports.orders.excel');
    Route::get('reports/orders/pdf', [ReportController::class, 'exportOrdersPdf'])->name('reports.orders.pdf');
    Route::get('reports/events/excel', [ReportController::class, 'exportEventsExcel'])->name('reports.events.excel');
    Route::resource('users', UserController::class)->only('index', 'create', 'store', 'edit', 'update', 'destroy');
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

    Route::get('libro-reclamaciones/dashboard', [LibroReclamacionAdminController::class, 'dashboard'])->name('libro-reclamaciones.dashboard');
    Route::get('libro-reclamaciones/export/excel', [LibroReclamacionAdminController::class, 'exportExcel'])->name('libro-reclamaciones.export.excel');
    Route::get('libro-reclamaciones/export/pdf', [LibroReclamacionAdminController::class, 'exportPdf'])->name('libro-reclamaciones.export.pdf');
    Route::post('libro-reclamaciones/{libro_reclamacion}/respond', [LibroReclamacionAdminController::class, 'respond'])->name('libro-reclamaciones.respond');
    Route::post('libro-reclamaciones/{libro_reclamacion}/estado', [LibroReclamacionAdminController::class, 'updateEstado'])->name('libro-reclamaciones.update-estado');
    Route::resource('libro-reclamaciones', LibroReclamacionAdminController::class)->only('index', 'show')->parameters(['libro_reclamaciones' => 'libro_reclamacion']);
});
