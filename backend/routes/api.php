<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAllowUrbanLegend;
use App\Http\Controllers\Api\UrbanLegendController;

Route::middleware([IsAllowUrbanLegend::class])->group(function () {
    Route::get('/legends', [UrbanLegendController::class, 'show']);
    Route::post('/legend', [UrbanLegendController::class, 'store']);
    Route::put('/legend/{uuid}', [UrbanLegendController::class, 'update']);
    Route::delete('/legend/{uuid}', [UrbanLegendController::class, 'destroy']);
});
