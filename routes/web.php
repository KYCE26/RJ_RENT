<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/password', [ProfileController::class, 'change_Password'])->name('profile.password');


});

Route::middleware(['auth'])->resource('/car', CarController::class);
Route::middleware(['auth'])->resource('/user', UserController::class);
Route::middleware(['auth'])->resource('/transaction', TransactionController::class);

Route::post('transaction/{transaction:id}/status', [TransactionController::class, 'status'])->name('transaction.status');

Route::post('/profile/change', [ProfileController::class, 'change_profile'])->name('profile.change');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::get('/dashboard', DashboardController::class)->name('dashboard.index');


Route::get('/transactions/{transaction}/payment-proof', [TransactionController::class, 'showPaymentProof'])->name('transactions.show-payment-proof');
Route::get('/transactions/{transaction}/create-payment-proof', [TransactionController::class, 'createPaymentProof'])
    ->name('transaction.createPaymentProof');
Route::post('/transactions/store-payment-proof', [TransactionController::class, 'storePaymentProof'])
    ->name('transaction.storePaymentProof');

require __DIR__.'/auth.php';
