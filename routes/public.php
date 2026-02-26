<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LibroReclamacionController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicMediaController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Shared Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/eventos', [EventController::class, 'index'])->name('events.index');
Route::get('/eventos/{slug}', [EventController::class, 'show'])->name('events.show')->where('slug', '[a-z0-9\-]+');
Route::get('/media/public/{path}', [PublicMediaController::class, 'show'])->where('path', '.*')->name('media.public');

Route::middleware(['auth', 'organizer'])->group(function () {
    Route::get('/eventos/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/eventos', [EventController::class, 'store'])->name('events.store');
    Route::get('/eventos/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/eventos/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/eventos/{event}', [EventController::class, 'destroy'])->name('events.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->middleware('throttle:5,1');
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::view('reset-password/success', 'auth.passwords.reset-success')->name('password.reset.success');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('can_purchase')->group(function () {
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('cart/summary', [CartController::class, 'summary'])->name('cart.summary');
    Route::get('cart/stock/{ticketType}', [CartController::class, 'stock'])->name('cart.stock');
    Route::post('cart', [CartController::class, 'add'])->name('cart.add');
    Route::put('cart', [CartController::class, 'update'])->name('cart.update.fallback');
    Route::patch('cart/{ticketType}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{ticketType}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1');
    Route::post('checkout/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.create-payment-intent')->middleware('throttle:10,1');
});

Route::get('stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::post('api/stripe/webhook', [StripeController::class, 'webhook'])->name('stripe.webhook');
Route::get('payment/success', [MercadoPagoController::class, 'success'])->name('payment.success');
Route::get('payment/failure', [MercadoPagoController::class, 'failure'])->name('payment.failure');
Route::get('payment/pending', [MercadoPagoController::class, 'pending'])->name('payment.pending');

Route::prefix('api/payments')->group(function () {
    Route::post('/create-preference', [MercadoPagoController::class, 'createPreference'])->middleware(['auth', 'throttle:10,1']);
    Route::post('/webhook', [MercadoPagoController::class, 'webhook'])->name('payment.webhook');
});

Route::get('orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');

Route::get('/ticket/verify/{ticketCode}', [App\Http\Controllers\TicketValidationController::class, 'validateTicket'])->name('ticket.verify');
Route::post('/ticket/verify/{ticketCode}/scan', [App\Http\Controllers\TicketValidationController::class, 'markAsUsed'])->name('validate.scan')->middleware('auth');

Route::middleware('throttle:10,1')->prefix('libro-reclamaciones')->name('libro-reclamaciones.')->group(function () {
    Route::get('/', [LibroReclamacionController::class, 'create'])->name('create');
    Route::post('/', [LibroReclamacionController::class, 'store'])->name('store');
    Route::get('/gracias', [LibroReclamacionController::class, 'thanks'])->name('thanks');
    Route::get('/constancia/{codigo}', [LibroReclamacionController::class, 'downloadConstancia'])->name('download');
});
