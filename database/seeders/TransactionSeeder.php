<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Game;
use App\Models\Transactions;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Clear existing data - urutan penting!
        TransactionDetail::truncate();
        Transactions::truncate();
        
        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $users = User::where('role', 0)->get(); // Regular users only
        $games = Game::all();

        if ($users->isEmpty() || $games->isEmpty()) {
            $this->command->info('No users or games found! Please run UserSeeder and GameSeeder first.');
            return;
        }

        $transactions = [];

        // Create transactions for the last 90 days
        for ($i = 0; $i < 200; $i++) {
            $user = $users->random();
            $transactionDate = Carbon::now()->subDays(rand(0, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $transactions[] = [
                'user_id' => $user->id,
                'total_price' => 0, // Will be calculated from details
                'date' => $transactionDate,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert transactions
        Transactions::insert($transactions);

        // Get all inserted transactions
        $insertedTransactions = Transactions::all();
        $transactionDetails = [];

        foreach ($insertedTransactions as $transaction) {
            $totalPrice = 0;
            $numberOfGames = rand(1, 4); // 1-4 games per transaction

            $selectedGames = $games->random($numberOfGames);

            foreach ($selectedGames as $game) {
                $quantity = rand(1, 3);
                $subtotal = $game->price * $quantity;
                $totalPrice += $subtotal;

                $transactionDetails[] = [
                    'transaction_id' => $transaction->id, // Pastikan ini transaction_id
                    'game_id' => $game->id,
                    'price' => $game->price,
                    'qty' => $quantity,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Update transaction total price
            $transaction->update(['total_price' => $totalPrice]);
        }

        // Insert transaction details
        TransactionDetail::insert($transactionDetails);

        $this->command->info('Transactions seeded successfully!');
        $this->command->info('Total transactions: ' . $insertedTransactions->count());
        $this->command->info('Total transaction details: ' . count($transactionDetails));
    }
}