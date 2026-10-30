<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DailyScheduleController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;

Route::middleware('guest')->group(function () {
	Route::get('/login', [AuthController::class, 'create'])->name('login');
	Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.attempt');
});

Route::middleware('auth')->group(function () {
	Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
	Route::get('/dashboard', [DashboardController::class, 'index']);

	Route::resource('patients', PatientController::class);
	Route::resource('appointments', AppointmentController::class);
	Route::get('/daily-schedule', [DailyScheduleController::class, 'index'])->name('daily.index');

	Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
	Route::get('/billing/payment', [BillingController::class, 'createPayment'])->name('billing.payment');
	Route::post('/billing/payment', [BillingController::class, 'storePayment'])->name('billing.store');
	Route::get('/billing/{id}/history', [BillingController::class, 'history'])->name('billing.history');
	Route::get('/billing/{id}/edit', [BillingController::class, 'edit'])->name('billing.edit');
	Route::put('/billing/{id}', [BillingController::class, 'update'])->name('billing.update');
});