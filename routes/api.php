<?php

use App\Http\Controllers\SkinRecommendationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/hitung-rekomendasi', function () {
    if (request()->expectsJson()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Gunakan metode POST untuk endpoint ini.',
        ], 405)->header('Allow', 'POST');
    }

    return redirect('/');
});

Route::post('/hitung-rekomendasi', [SkinRecommendationController::class, 'hitungRekomendasi']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
