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
        Schema::create('system_login_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_login_id')->constrained()->onDelete('cascade');
            $table->foreignId('system_user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Índice único para evitar duplicatas
            $table->unique(['system_login_id', 'system_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_login_permissions');
    }
};