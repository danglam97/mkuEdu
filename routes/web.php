<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\post_new\PostNewController;

//web
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/danh-muc', 'category')->name('danh-muc');

    Route::controller(PostNewController::class)->group(function () {
        Route::get('/tin-tuc', 'postNew')->name('post_new.index');
        Route::get('/tin-tuc/{slug}', 'detailPostNew')->name('detail.post_new');
    });

    Route::get('/su-kien', 'postEvent')->name('post_event');
    Route::get('/su-kien/{slug}', 'detailPostEvent')->name('detail.post_event');
});


/// admin

Route::get('/admin/patients/{patient}/undo', function (Patient $patient) {
    $originalData = Cache::get("patient_undo_{$patient->id}");

    if (!$originalData) {
        return redirect()->route('filament.admin.resources.patients.index')
            ->with('error', 'Undo is no longer available.');
    }

    $patient->update($originalData);
    Cache::forget("patient_undo_{$patient->id}");

    return redirect()->route('filament.admin.resources.patients.index')
        ->with('success', 'Changes have been undone successfully.');
})->name('admin.patients.undo')->middleware(['auth', 'web']);
