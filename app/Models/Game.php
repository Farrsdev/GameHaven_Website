<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','description','developer','category','price','release_date','stock','rating','file_url','image_url'
    ];

     public function transactionDetails() {
        return $this->hasMany(TransactionDetail::class);
    }

    public function purchasedGames() {
        return $this->hasMany(PurchasedGame::class);
    }

    public function downloadHistories() {
        return $this->hasMany(DownloadHistory::class);
    }

}
