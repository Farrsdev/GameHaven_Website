<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedGame extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','game_id','purchase_date','transaction_id','download_status'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function transaction() {
        return $this->belongsTo(Transactions::class);
    }
}
