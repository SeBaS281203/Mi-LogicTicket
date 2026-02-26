<?php

use App\Http\Controllers\Cuenta\CuentaDashboardController;
use App\Http\Controllers\Cuenta\CuentaTicketController;
use App\Http\Controllers\Cuenta\PerfilController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cuenta Routes (Client Panel)
|--------------------------------------------------------------------------
| Panel Mi Cuenta. Disponible para clientes, organizadores y admins.
*/
Route::middleware('auth')->group(function () {
    Route::get('mis-ordenes', [OrderController::class, 'index'])->name('orders.index');

    Route::prefix('cuenta')->name('cuenta.')->group(function () {
        Route::get('/', [CuentaDashboardController::class, 'index'])->name('dashboard');
        Route::get('tickets', [CuentaTicketController::class, 'index'])->name('tickets.index');
        Route::get('tickets/{code}/qr', [CuentaTicketController::class, 'getQr'])->name('tickets.qr');
        Route::get('orders/{order}/tickets/pdf', [CuentaTicketController::class, 'downloadPdf'])->name('tickets.download');
        Route::get('perfil', [PerfilController::class, 'edit'])->name('perfil.edit');
        Route::put('perfil', [PerfilController::class, 'update'])->name('perfil.update');
    });
});
