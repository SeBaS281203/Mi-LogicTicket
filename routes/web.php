<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Organizer\OrganizerDashboardController;
use App\Http\Controllers\Organizer\OrganizerEventController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/eventos', [EventController::class, 'index'])->name('events.index');
Route::get('/eventos/create', [EventController::class, 'create'])->name('events.create')->middleware(['auth', 'organizer']);
Route::post('/eventos', [EventController::class, 'store'])->name('events.store')->middleware(['auth', 'organizer']);
Route::get('/eventos/{event}/edit', [EventController::class, 'edit'])->name('events.edit')->middleware(['auth', 'organizer']);
Route::get('/eventos/{slug}', [EventController::class, 'show'])->name('events.show')->where('slug', '[a-z0-9\-]+');
Route::put('/eventos/{event}', [EventController::class, 'update'])->name('events.update')->middleware(['auth', 'organizer']);
Route::delete('/eventos/{event}', [EventController::class, 'destroy'])->name('events.destroy')->middleware(['auth', 'organizer']);

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart', [CartController::class, 'add'])->name('cart.add');
Route::put('cart', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/{ticketType}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');

Route::get('mercadopago/success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
Route::get('mercadopago/failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failure');
Route::get('mercadopago/pending', [MercadoPagoController::class, 'pending'])->name('mercadopago.pending');

Route::get('orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');

Route::middleware('auth')->group(function () {
    Route::get('mis-ordenes', [OrderController::class, 'index'])->name('orders.index');
});

// Libro de Reclamaciones Virtual (INDECOPI) - público, rate limited
Route::middleware('throttle:10,1')->prefix('libro-reclamaciones')->name('libro-reclamaciones.')->group(function () {
    Route::get('/', [\App\Http\Controllers\LibroReclamacionController::class, 'create'])->name('create');
    Route::post('/', [\App\Http\Controllers\LibroReclamacionController::class, 'store'])->name('store');
    Route::get('/gracias', [\App\Http\Controllers\LibroReclamacionController::class, 'thanks'])->name('thanks');
    Route::get('/constancia/{codigo}', [\App\Http\Controllers\LibroReclamacionController::class, 'downloadConstancia'])->name('download');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', \App\Http\Controllers\Admin\AdminEventController::class)->only('index', 'show');
    Route::post('events/{event}/approve', [\App\Http\Controllers\Admin\AdminEventController::class, 'approve'])->name('events.approve');
    Route::post('events/{event}/reject', [\App\Http\Controllers\Admin\AdminEventController::class, 'reject'])->name('events.reject');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('cities', \App\Http\Controllers\Admin\CityController::class)->except('show');
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/orders/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportOrdersExcel'])->name('reports.orders.excel');
    Route::get('reports/orders/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportOrdersPdf'])->name('reports.orders.pdf');
    Route::get('reports/events/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportEventsExcel'])->name('reports.events.excel');
    Route::resource('users', UserController::class)->only('index', 'edit', 'update');

    // Libro de Reclamaciones (solo admin)
    Route::get('libro-reclamaciones/dashboard', [\App\Http\Controllers\Admin\LibroReclamacionAdminController::class, 'dashboard'])->name('libro-reclamaciones.dashboard');
    Route::get('libro-reclamaciones/export/excel', [\App\Http\Controllers\Admin\LibroReclamacionAdminController::class, 'exportExcel'])->name('libro-reclamaciones.export.excel');
    Route::get('libro-reclamaciones/export/pdf', [\App\Http\Controllers\Admin\LibroReclamacionAdminController::class, 'exportPdf'])->name('libro-reclamaciones.export.pdf');
    Route::post('libro-reclamaciones/{libro_reclamacion}/respond', [\App\Http\Controllers\Admin\LibroReclamacionAdminController::class, 'respond'])->name('libro-reclamaciones.respond');
    Route::post('libro-reclamaciones/{libro_reclamacion}/estado', [\App\Http\Controllers\Admin\LibroReclamacionAdminController::class, 'updateEstado'])->name('libro-reclamaciones.update-estado');
    Route::resource('libro-reclamaciones', \App\Http\Controllers\Admin\LibroReclamacionAdminController::class)->only('index', 'show')->parameters(['libro_reclamaciones' => 'libro_reclamacion']);
});

Route::middleware(['auth', 'organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/', [OrganizerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('events', OrganizerEventController::class)->except('show');
});
