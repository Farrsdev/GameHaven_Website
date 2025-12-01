<?php

namespace App\Http\Controllers;

use App\Models\DownloadHistory;
use App\Models\PurchasedGame;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadHistoryController extends Controller
{
    // Lihat histori download user
    public function index($userId)
    {
        $history = DownloadHistory::with('game')
            ->where('user_id', $userId)
            ->orderBy('download_date', 'desc')
            ->get();
            
        return response()->json($history);
    }

    // Process download game
    public function downloadGame(Request $request, $gameId)
    {
        DB::beginTransaction();

        try {
            $userId = $request->user_id ?? session('user_id');
            
            if (!$userId) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Cek apakah game sudah dibeli
            $purchasedGame = PurchasedGame::where('user_id', $userId)
                ->where('game_id', $gameId)
                ->first();

            if (!$purchasedGame) {
                return response()->json(['error' => 'Game not purchased'], 403);
            }

            $game = Game::findOrFail($gameId);

            // Update download status di purchased_games
            $purchasedGame->update([
                'download_status' => 'DOWNLOADED',
                'last_played' => now()
            ]);

            // Tambah ke download history
            $downloadHistory = DownloadHistory::create([
                'user_id' => $userId,
                'game_id' => $gameId,
                'download_date' => now(),
                'file_size' => $game->file_size,
                'version' => $game->version
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Download recorded successfully',
                'download' => $downloadHistory,
                'game' => $game
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tambah histori download baru (alternatif)
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'game_id' => 'required|integer',
            'download_date' => 'nullable|date',
            'file_size' => 'nullable|string',
            'version' => 'nullable|string'
        ]);

        $data['download_date'] = $data['download_date'] ?? now();

        $download = DownloadHistory::create($data);

        return response()->json($download, 201);
    }
}