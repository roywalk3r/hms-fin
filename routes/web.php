<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoicePdfController;


Route::get('/', function () {
    return view('welcome');
});
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/invoices/{invoice}/download', [InvoicePdfController::class, 'download'])
    ->name('invoice.download');

require __DIR__.'/auth.php';
