<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ParticipantAuthController;
use App\Http\Controllers\WorkInstructionController;
use App\Http\Controllers\ParticipantVideoController;
use App\Http\Controllers\Admin\WorkInstructionAdminController;
use App\Http\Controllers\Admin\WiVideoAdminController;
use App\Http\Controllers\Admin\WiVideoEventAdminController;

Auth::routes(['register' => false]);
Route::get('/', [ParticipantAuthController::class, 'showNikForm'])->name('nik.form');
Route::post('/nik', [ParticipantAuthController::class, 'submitNik'])->name('nik.submit');
Route::get('/register-participant', [ParticipantAuthController::class, 'showRegisterForm'])->name('participant.register.form');
Route::post('/register-participant', [ParticipantAuthController::class, 'submitRegister'])->name('participant.register.submit');
Route::post('/participant-logout', [ParticipantAuthController::class, 'logout'])->name('participant.logout');

Route::middleware(['participant'])->group(function () {
    Route::get('/wi', [WorkInstructionController::class, 'index'])->name('wi.index');
    Route::get('/wi/video/{video}', [WorkInstructionController::class, 'playVideo'])->name('wi.video.play');
    Route::get('/wi/video/{video}/events', [ParticipantVideoController::class, 'getEvent'])->name('wi.video.events');
    Route::post('/wi/video/{video}/progress', [ParticipantVideoController::class, 'saveProgress'])->name('wi.video.progress');
    Route::post('/wi/video/{video}/events/{event}/attempt',[ParticipantVideoController::class, 'submitAttempt'])->name('wi.video.events.attempt');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/videos/player', [WiVideoAdminController::class, 'player'])
        ->name('admin.wi.videos.player');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {return redirect()->route('admin.wi.index');})->name('dashboard');
    Route::get('/wi', [WorkInstructionAdminController::class, 'index'])->name('wi.index');
    Route::post('/wi', [WorkInstructionAdminController::class, 'store'])->name('wi.store');
    Route::put('/wi/{wi}', [WorkInstructionAdminController::class, 'update'])->name('wi.update');
    Route::delete('/wi/{wi}', [WorkInstructionAdminController::class, 'destroy'])->name('wi.destroy');
    Route::get('/results', [ResultAdminController::class, 'index'])->name('results.index');
    Route::get('/results/{participant}', [ResultAdminController::class, 'show'])->name('results.show');

    Route::prefix('wi/{wi}')->scopeBindings()->group(function () {
        Route::get('/videos', [WiVideoAdminController::class, 'index'])->name('wi.videos.index');
        Route::post('/videos', [WiVideoAdminController::class, 'store'])->name('wi.videos.store');
        Route::put('/videos/{video}', [WiVideoAdminController::class, 'update'])->name('wi.videos.update');
        Route::delete('/videos/{video}', [WiVideoAdminController::class, 'destroy'])->name('wi.videos.destroy');
        Route::get('/videos/{video}/events', [WiVideoEventAdminController::class, 'index'])->name('wi.videos.events.index');
        Route::post('/videos/{video}/events', [WiVideoEventAdminController::class, 'store'])->name('wi.videos.events.store');
        Route::put('/videos/{video}/events/{event}', [WiVideoEventAdminController::class, 'update'])->name('wi.videos.events.update');
        Route::delete('/videos/{video}/events/{event}', [WiVideoEventAdminController::class, 'destroy'])->name('wi.videos.events.destroy');
    });
});
