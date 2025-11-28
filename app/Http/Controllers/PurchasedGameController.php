<?php

namespace App\Http\Controllers;

use App\Models\PurchasedGame;
use Illuminate\Http\Request;

class PurchasedGameController extends Controller
{
    public function index($userId)
    {
        $purchased = PurchasedGame::with('game')->where('user_id', $userId)->get();
        return response()->json($purchased);
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
}
