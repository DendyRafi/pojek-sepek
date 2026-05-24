<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\WelcomeInputController;
use App\Models\Criteria;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'criterias' => Criteria::orderBy('id')->get(['id', 'name']),
        'savedWelcomeInputs' => session('welcome_inputs', ['alternatives' => []]),
    ]);
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'admin'])->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/admin/password', [PasswordController::class, 'edit'])->name('admin.password.edit');
    Route::put('/admin/password', [PasswordController::class, 'update'])->name('admin.password.update');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class, 'store'])->name('pengaturan.store');
    Route::post('/pengaturan/reset', [PengaturanController::class, 'reset'])->name('pengaturan.reset');
    Route::put('/pengaturan/{criteria}', [PengaturanController::class, 'update'])->name('pengaturan.update');
    Route::delete('/pengaturan/{criteria}', [PengaturanController::class, 'destroy'])->name('pengaturan.destroy');
    Route::get('/custom-background', [PengaturanController::class, 'customBackground'])->name('custom-background');
    Route::post('/custom-background/upload', [PengaturanController::class, 'storeCustomBackground'])->name('custom-background.store');
    Route::post('/custom-background/reset', [PengaturanController::class, 'destroyCustomBackground'])->name('custom-background.destroy');
    Route::post('/custom-background', [PengaturanController::class, 'storeCustomBackground']);
    Route::delete('/custom-background', [PengaturanController::class, 'destroyCustomBackground']);
});

Route::post('/skin-inputs', [WelcomeInputController::class, 'store'])->name('welcome-inputs.store');
Route::delete('/skin-inputs', [WelcomeInputController::class, 'destroy'])->name('welcome-inputs.destroy');
