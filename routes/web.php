<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ParticipantAuthController;
use App\Http\Controllers\WorkInstructionController;
use App\Http\Controllers\ParticipantVideoController;

use App\Http\Controllers\Admin\WorkInstructionAdminController;
use App\Http\Controllers\Admin\WiVideoAdminController;
use App\Http\Controllers\Admin\WiVideoEventAdminController;
use App\Http\Controllers\Admin\ResultAdminController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Auth::routes(['register' => false]);

/*
|--------------------------------------------------------------------------
| Participant Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [ParticipantAuthController::class, 'showNikForm'])->name('nik.form');
Route::post('/nik', [ParticipantAuthController::class, 'submitNik'])->name('nik.submit');

Route::get('/register-participant', [ParticipantAuthController::class, 'showRegisterForm'])
    ->name('participant.register.form');
Route::post('/register-participant', [ParticipantAuthController::class, 'submitRegister'])
    ->name('participant.register.submit');

Route::post('/participant-logout', [ParticipantAuthController::class, 'logout'])
    ->name('participant.logout');

Route::middleware(['participant'])->group(function () {
    Route::get('/wi', [WorkInstructionController::class, 'index'])
        ->name('wi.index');

    Route::get('/wi/video/{video}', [WorkInstructionController::class, 'playVideo'])
        ->name('wi.video.play');

    Route::get('/wi/video/{video}/events', [ParticipantVideoController::class, 'getEvent'])
        ->name('wi.video.events');

    Route::post('/wi/video/{video}/progress', [ParticipantVideoController::class, 'saveProgress'])
        ->name('wi.video.progress');

    Route::post('/wi/video/{video}/events/{event}/attempt',
        [ParticipantVideoController::class, 'submitAttempt'])
        ->name('wi.video.events.attempt');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return redirect()->route('admin.wi.index');
        })->name('dashboard');

        // Work Instructions
        Route::get('/wi', [WorkInstructionAdminController::class, 'index'])
            ->name('wi.index');

        Route::post('/wi', [WorkInstructionAdminController::class, 'store'])
            ->name('wi.store');

        Route::put('/wi/{wi}', [WorkInstructionAdminController::class, 'update'])
            ->name('wi.update');

        Route::delete('/wi/{wi}', [WorkInstructionAdminController::class, 'destroy'])
            ->name('wi.destroy');

        // Results
        Route::get('/results', [ResultAdminController::class, 'index'])
            ->name('results.index');

        Route::get('/results/{participant}', [ResultAdminController::class, 'show'])
            ->name('results.show');

        /*
        |--------------------------------------------------------------------------
        | WI Videos & Events (TANPA scopeBindings)
        |--------------------------------------------------------------------------
        */
        Route::prefix('wi/{wi}')->group(function () {

            // Videos
            Route::get('/videos', [WiVideoAdminController::class, 'index'])
                ->name('wi.videos.index');

            Route::post('/videos', [WiVideoAdminController::class, 'store'])
                ->name('wi.videos.store');

            Route::put('/videos/{video}', [WiVideoAdminController::class, 'update'])
                ->name('wi.videos.update');

            Route::delete('/videos/{video}', [WiVideoAdminController::class, 'destroy'])
                ->name('wi.videos.destroy');

            // Video Events (QUIZ)
            Route::get('/videos/{video}/events',
                [WiVideoEventAdminController::class, 'index'])
                ->name('wi.videos.events.index');

            Route::post('/videos/{video}/events',
                [WiVideoEventAdminController::class, 'store'])
                ->name('wi.videos.events.store');

            Route::put('/videos/{video}/events/{event}',
                [WiVideoEventAdminController::class, 'update'])
                ->name('wi.videos.events.update');

            Route::delete('/videos/{video}/events/{event}',
                [WiVideoEventAdminController::class, 'destroy'])
                ->name('wi.videos.events.destroy');
        });
    });
