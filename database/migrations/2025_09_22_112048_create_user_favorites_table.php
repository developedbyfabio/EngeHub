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
        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('system_user_id')->nullable()->constrained('system_users')->onDelete('cascade');
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->timestamps();
            
            // Evitar duplicatas - cada usuário pode favoritar cada card apenas uma vez
            $table->unique(['user_id', 'card_id'], 'user_card_favorite_unique');
            $table->unique(['system_user_id', 'card_id'], 'system_user_card_favorite_unique');
            
            // Índices para performance
            $table->index(['user_id']);
            $table->index(['system_user_id']);
            $table->index(['card_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_favorites');
    }
};
