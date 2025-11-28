<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Game;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Checkout: buat transaksi baru
    public function checkout(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'games' => 'required|array',
            'games.*.id' => 'required|integer',
            'games.*.qty' => 'required|integer'
        ]);

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            foreach ($data['games'] as $g) {
                $game = Game::findOrFail($g['id']);
                $totalPrice += $game->price * $g['qty'];
            }

            $transaction = Transactions::create([
                'user_id' => $data['user_id'],
                'total_price' => $totalPrice,
                'date' => now(),
            ]);

            foreach ($data['games'] as $g) {
                $game = Game::findOrFail($g['id']);
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'game_id' => $game->id,
                    'price' => $game->price,
                    'qty' => $g['qty']
                ]);
            }

            DB::commit();
            return response()->json($transaction, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Riwayat transaksi user
    public function history($userId)
    {
        $transactions = Transactions::with('details.game')->where('user_id', $userId)->get();
        return response()->json($transactions);
    }
}
