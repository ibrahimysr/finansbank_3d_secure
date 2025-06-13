<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/payment');

Route::prefix('payment')->group(function () {
    Route::get('/', [PaymentController::class, 'form'])->name('payment.form');
    Route::post('/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::any('/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::any('/failure', [PaymentController::class, 'failure'])->name('payment.failure');
});
