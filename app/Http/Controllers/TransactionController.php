<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Game;
use App\Models\PurchasedGame;
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

            // Calculate total price and check stock
            foreach ($data['games'] as $g) {
                $game = Game::findOrFail($g['id']);
                
                // Check stock
                if ($game->stock < $g['qty']) {
                    throw new \Exception("Game {$game->title} is out of stock");
                }
                
                $totalPrice += $game->price * $g['qty'];
            }

            // Create transaction
            $transaction = Transactions::create([
                'user_id' => $data['user_id'],
                'total_price' => $totalPrice,
                'date' => now(),
            ]);

            // Create transaction details and purchased games
            foreach ($data['games'] as $g) {
                $game = Game::findOrFail($g['id']);
                
                // Create transaction detail
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'game_id' => $game->id,
                    'price' => $game->price,
                    'qty' => $g['qty']
                ]);

                // Check if purchased game already exists for this user and game
                $existingPurchase = PurchasedGame::where('user_id', $data['user_id'])
                    ->where('game_id', $game->id)
                    ->first();

                if ($existingPurchase) {
                    // Update existing purchase
                    $existingPurchase->update([
                        'transaction_id' => $transaction->id,
                        'purchase_date' => now(),
                        'download_status' => 'NOT_DOWNLOADED'
                    ]);
                } else {
                    // Create new purchased game record
                    PurchasedGame::create([
                        'user_id' => $data['user_id'],
                        'game_id' => $game->id,
                        'transaction_id' => $transaction->id, // Pastikan ini diisi
                        'purchase_date' => now(),
                        'download_status' => 'NOT_DOWNLOADED'
                    ]);
                }

                // Update game stock
                $game->decrement('stock', $g['qty']);
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Checkout successful',
                'transaction' => $transaction->load('details.game')
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Riwayat transaksi user
    public function history($userId)
    {
        $transactions = Transactions::with('details.game')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($transactions);
    }
}