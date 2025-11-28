<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchased_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->timestamp('purchase_date');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->enum('download_status', ['NOT_DOWNLOADED', 'DOWNLOADING', 'DOWNLOADED', 'INSTALLED'])->default('NOT_DOWNLOADED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchased_games');
    }
};
