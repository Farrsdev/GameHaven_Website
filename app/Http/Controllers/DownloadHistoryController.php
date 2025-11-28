<?php

namespace App\Http\Controllers;

use App\Models\DownloadHistory;
use Illuminate\Http\Request;

class DownloadHistoryController extends Controller
{
    // Lihat histori download user
    public function index($userId)
    {
        $history = DownloadHistory::with('game')->where('user_id', $userId)->get();
        return response()->json($history);
    }

    // Tambah histori download baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'game_id' => 'required|integer',
            'download_date' => 'nullable|date'
        ]);

        $data['download_date'] = $data['download_date'] ?? now();

        $download = DownloadHistory::create($data);

        return response()->json($download, 201);
    }
}
