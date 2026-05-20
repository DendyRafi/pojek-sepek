<?php

use App\Http\Controllers\CriteriaController;
use App\Models\Criteria;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'criterias' => Criteria::orderBy('id')->get(['id', 'name']),
    ]);
});

Route::get('/kriteria', [CriteriaController::class, 'index'])->name('kriteria.index');
Route::post('/kriteria', [CriteriaController::class, 'store'])->name('kriteria.store');
Route::put('/kriteria/{criteria}', [CriteriaController::class, 'update'])->name('kriteria.update');
Route::delete('/kriteria/{criteria}', [CriteriaController::class, 'destroy'])->name('kriteria.destroy');
