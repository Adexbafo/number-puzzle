<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\MultiplayerController;
use App\Http\Controllers\RulesController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Wallet Connection
    |--------------------------------------------------------------------------
    */
    Route::post('/connect-wallet', function (Request $request) {
        auth()->user()->profile->update([
            'wallet_address' => $request->wallet
        ]);

        return response()->json(['success' => true]);
    });

    /*
    |--------------------------------------------------------------------------
    | Game (Single Player)
    |--------------------------------------------------------------------------
    */
    Route::prefix('game')->group(function () {
        Route::get('/start', [GameController::class, 'start'])->name('game.start');
        Route::get('/play', [GameController::class, 'play'])->name('game.play');
        Route::post('/submit', [GameController::class, 'submit'])->name('game.submit');
        Route::get('/next', [GameController::class, 'play'])->name('game.next');
    });

    /*
    |--------------------------------------------------------------------------
    | Leaderboard
    |--------------------------------------------------------------------------
    */
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])
        ->name('leaderboard');

    /*
    |--------------------------------------------------------------------------
    | Multiplayer
    |--------------------------------------------------------------------------
    */
    Route::prefix('multiplayer')->group(function () {
        Route::get('/', [MultiplayerController::class, 'index'])->name('mp.index');
        Route::post('/create', [MultiplayerController::class, 'create'])->name('mp.create');
        Route::get('/join/{id}', [MultiplayerController::class, 'join'])->name('mp.join');
        Route::get('/match/{id}', [MultiplayerController::class, 'play'])->name('mp.play');
        Route::post('/submit/{id}', [MultiplayerController::class, 'submit'])->name('mp.submit');
    });

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    */
    Route::get('/rules', [RulesController::class, 'index'])->name('rules');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';