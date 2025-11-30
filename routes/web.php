<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;

// =====================
// AUTH ROUTES
// =====================
Route::get('/login', function () {
    if (session()->has('user_id')) {
        $role = session('role');
        if ($role == 1) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/home');
        }
    }
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    if (session()->has('user_id')) {
        $role = session('role');
        if ($role == 1) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/home');
        }
    }
    return view('auth.register');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// =====================
// PUBLIC API ROUTES (Available without auth)
// =====================
Route::prefix('api')->group(function () {
    Route::get('/public/games', [GameController::class, 'index']);
    Route::get('/public/games/{id}', [GameController::class, 'show']);
});

// =====================
// ADMIN ROUTES (Protected by admin middleware)
// =====================
Route::prefix('admin')->middleware(['auth.session', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/chart-data', [AdminController::class, 'getChartData'])->name('admin.chart.data');

    // CRUD / Pages
    Route::get('/games', function () {
        return view('admin.games');
    });

    Route::get('/users', function () {
        return view('admin.users');
    });

    Route::get('/analytics', function () {
        return view('admin.analytics');
    });

    Route::get('/profile', function () {
        return view('admin.profile');
    });
});

// =====================
// ADMIN API ROUTES
// =====================
Route::prefix('api')->middleware(['auth.session', 'admin'])->group(function () {
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{id}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/transactions', function () {
        $transactions = App\Models\Transactions::with(['user', 'details.game'])->get();
        return response()->json($transactions);
    });
});
// =====================
// USER ROUTES (Protected by auth.session middleware)
// =====================
Route::middleware(['auth.session'])->group(function () {
    Route::get('/home', [UserController::class, 'home'])->name('home');
    Route::get('/api/home-data', [UserController::class, 'homeData']);
    
    Route::get('/games', function () {
        return view('user.games');
    });
    
    // Tambahkan route untuk game detail
    Route::get('/games/{id}', [GameController::class, 'showDetail'])->name('game.detail');
    
    Route::get('/store', function () {
        return view('user.store');
    });
    
    Route::get('/purchased', function () {
        return view('user.purchased');
    });
    
    Route::get('/downloads', function () {
        return view('user.downloads');
    });
    
    Route::get('/profile', function () {
        return view('user.profile');
    });

    // Protected user APIs
    Route::prefix('api')->group(function () {
        Route::get('/user/games', [GameController::class, 'index']);
        Route::get('/user/games/{id}', [GameController::class, 'show']);
        // Route untuk purchase game
        Route::post('/user/games/{id}/purchase', [GameController::class, 'purchase']);
    });
});
// Redirect root
Route::get('/', function () {
    if (session()->has('user_id')) {
        $role = session('role');
        if ($role == 1) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/home');
        }
    }
    return redirect('/login');
});