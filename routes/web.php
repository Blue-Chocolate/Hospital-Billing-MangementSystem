<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillController;
use App\Services\GroqGeneralService;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/bills/{bill}/receipt', [BillController::class, 'receipt'])->name('bill.receipt');
Route::get('/explain-fast-models', function (GroqGeneralService $service) {
    return $service->explainFastLanguageModels();
});

