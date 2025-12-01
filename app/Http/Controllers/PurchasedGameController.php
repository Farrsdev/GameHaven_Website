<?php

namespace App\Http\Controllers;

use App\Models\PurchasedGame;
use Illuminate\Http\Request;

class PurchasedGameController extends Controller
{
    public function index($userId)
    {
        $purchasedGames = PurchasedGame::with('game')
            ->where('user_id', $userId)
            ->orderBy('purchase_date', 'desc')
            ->get();
            
        return response()->json($purchasedGames);
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'download_status' => 'required|in:NOT_DOWNLOADED,DOWNLOADING,DOWNLOADED,INSTALLED'
        ]);

        $purchase = PurchasedGame::findOrFail($id);
        $purchase->update(['download_status' => $data['download_status']]);

        return response()->json($purchase);
    }

    // Method baru untuk mendapatkan purchased game by game_id dan user_id
    public function getByGameAndUser($userId, $gameId)
    {
        $purchasedGame = PurchasedGame::with('game')
            ->where('user_id', $userId)
            ->where('game_id', $gameId)
            ->firstOrFail();
            
        return response()->json($purchasedGame);
    }
}