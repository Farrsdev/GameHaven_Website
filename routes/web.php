<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\UserController;

// =====================
// AUTH ROUTES
// =====================
Route::get('/register', function () {
    return view('auth.register');
});
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return view('auth.login');
});
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);

// =====================
// DASHBOARD ROUTE
// =====================
Route::get('/dashboard', function () {
    if (!session()->has('user_id')) {
        return redirect('/login');
    }

    $role = session('role');
    if ($role == 1) {
        // Redirect ke dashboard admin
        return redirect()->route('admin.dashboard');
    } else {
        // Dashboard user
        return view('user.dashboard');
    }
});

// =====================
// ADMIN ROUTES
// =====================
Route::prefix('admin')->group(function () {

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
Route::prefix('api')->group(function () {
    Route::get('/games', [GameController::class, 'index']);
    Route::get('/games/{id}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{id}', [GameController::class, 'update']);
    Route::delete('/games/{id}', [GameController::class, 'destroy']);
});


// API Routes for Users
Route::prefix('api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

Route::prefix('api')->group(function () {
    Route::get('/transactions', function () {
        $transactions = App\Models\Transactions::with(['user', 'details.game'])->get();
        return response()->json($transactions);
    });
    
    Route::get('/games', [App\Http\Controllers\GameController::class, 'index']);
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
});

Route::prefix('api')->group(function () {
    Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update']);
    Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
});