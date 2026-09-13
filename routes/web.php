<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ElectionSettingController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Voter\VoterAuthController;
use App\Http\Controllers\Voter\VotingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    return redirect()->route('voter.login');
});

/*
|--------------------------------------------------------------------------
| Voter Routes
|--------------------------------------------------------------------------
*/
Route::prefix('voter')->group(function () {
    Route::get('/login', [VoterAuthController::class, 'showLoginForm'])->name('voter.login');
    Route::post('/login', [VoterAuthController::class, 'login'])
        ->middleware('throttle:15,1')
        ->name('voter.login.submit');
    Route::post('/logout', [VoterAuthController::class, 'logout'])->name('voter.logout');
});

Route::middleware(['voter.auth', 'election.active'])->group(function () {
    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::post('/voting/submit', [VotingController::class, 'submit'])->name('voting.submit');
});

Route::get('/voting/success', [VotingController::class, 'success'])->name('voting.success');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:6,1')->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Panel
    Route::middleware(['auth', 'admin.auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Positions
        Route::resource('positions', PositionController::class)->except(['show']);
        Route::post('positions/{position}/toggle', [PositionController::class, 'toggleStatus'])->name('positions.toggle');

        // Candidates
        Route::resource('candidates', CandidateController::class)->except(['show']);
        Route::post('candidates/{candidate}/toggle', [CandidateController::class, 'toggleStatus'])->name('candidates.toggle');

        // Tokens
        Route::get('tokens', [TokenController::class, 'index'])->name('tokens.index');
        Route::post('tokens/generate-single', [TokenController::class, 'generateSingle'])->name('tokens.generate-single');
        Route::post('tokens/generate-batch', [TokenController::class, 'generateBatch'])->name('tokens.generate-batch');
        Route::post('tokens/{voter}/toggle', [TokenController::class, 'toggleStatus'])->name('tokens.toggle');
        Route::post('tokens/{voter}/reset', [TokenController::class, 'resetToken'])->name('tokens.reset');
        Route::delete('tokens/{voter}', [TokenController::class, 'destroy'])->name('tokens.destroy');
        Route::get('tokens/export', [TokenController::class, 'export'])->name('tokens.export');

        // Election Settings
        Route::get('settings', [ElectionSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [ElectionSettingController::class, 'update'])->name('settings.update');

        // Results
        Route::get('results', [ResultController::class, 'index'])->name('results.index');
        Route::get('results/print', [ResultController::class, 'print'])->name('results.print');

        // Audit Logs
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});
