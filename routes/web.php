<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TopupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Publik (Tidak perlu login)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/player-search', [HomeController::class, 'search'])->name('player.search');
Route::get('/leaderboard', [HomeController::class, 'leaderboard'])->name('leaderboard');
Route::view('/about', 'about')->name('about');

// Rute Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rute yang memerlukan login (akan dicek manual di controller)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/topup', [TopupController::class, 'index'])->name('topup.index');
Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');

// Tambahkan ke routes/web.php untuk testing
Route::get('/debug-session', function() {
    return [
        'session_data' => [
            'has_token' => session()->has('api_token'),
            'has_user' => session()->has('user'),
            'token' => session('api_token') ? 'EXISTS' : 'NULL',
            'user' => session('user'),
            'is_logged_in' => session('is_logged_in'),
        ],
        'helper_functions' => [
            'is_user_logged_in' => function_exists('is_user_logged_in') ? is_user_logged_in() : 'Function not found',
            'session_check' => function_exists('session_check') ? session_check() : 'Function not found',
            'session_user_name' => function_exists('session_user_name') ? session_user_name() : 'Function not found',
        ],
        'all_session' => session()->all()
    ];
})->name('debug.session');
