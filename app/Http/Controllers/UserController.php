<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\PurchasedGame;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Semua user (admin)
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Detail user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Buat user baru (register)
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|boolean',
            'photo' => 'nullable|string'
        ]);

        $data['password'] = Hash::make($data['password']); // enkripsi password

        $user = User::create($data);

        return response()->json($user, 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'username' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|boolean',
            'photo' => 'nullable|string'
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
   public function home()
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        return view('user.home');
    }

    // Data untuk home page user
    public function homeData()
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Game yang baru ditambahkan
        $newGames = Game::orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Game populer berdasarkan rating
        $popularGames = Game::where('rating', '>=', 4.0)
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        // Game yang sedang diskon
        $saleGames = Game::where('price', '<', 100000)
            ->orderBy('price', 'asc')
            ->take(6)
            ->get();

        // Game yang sudah dibeli user
        $purchasedGames = PurchasedGame::with('game')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Statistik user
        $stats = [
            'total_games' => PurchasedGame::where('user_id', $userId)->count(),
            'total_spent' => Transactions::where('user_id', $userId)->sum('total_price') ?? 0,
            'wishlist_count' => 0,
        ];

        return response()->json([
            'new_games' => $newGames,
            'popular_games' => $popularGames,
            'sale_games' => $saleGames,
            'recent_purchases' => $purchasedGames,
            'user_stats' => $stats,
            'user' => [
                'username' => Session::get('username'),
                'email' => Session::get('email')
            ]
        ]);
    }
}
