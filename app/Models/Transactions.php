<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'total_price',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship dengan TransactionDetail
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
