<?php

use App\Http\Controllers\Admin\SaveNewTitleController;
use App\Http\Controllers\Public\FetchTitlesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('/save-new-title', [SaveNewTitleController::class, 'store']);
    Route::get('/fetch-articles', [SaveNewTitleController::class, 'index']);
});

Route::get('/fetch/titles', [FetchTitlesController::class, 'index']);
