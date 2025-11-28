<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;


    
    protected $fillable = [
        'transaction_id',
        'game_id',
        'price',
        'qty'
    ];

    // Relationship dengan Transaction
    public function transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    // Relationship dengan Game
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
