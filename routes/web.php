<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ConversionFeeController;
use App\Http\Controllers\HomeController;

Auth::routes();

// Grupo de rotas que requer autenticação
Route::middleware(['auth'])->group(function () {
    Route::get('/', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/convert', [QuotationController::class, 'convert'])->name('quotations.convert');
    Route::post('/quotations/email', [QuotationController::class, 'sendEmail'])->name('quotations.sendEmail');
    Route::get('/history', [QuotationController::class, 'history'])->name('quotations.history');
    Route::get('/fees/edit', [ConversionFeeController::class, 'edit'])->name('conversion_fees.edit');
    Route::post('/fees/update', [ConversionFeeController::class, 'update'])->name('conversion_fees.update');
});
