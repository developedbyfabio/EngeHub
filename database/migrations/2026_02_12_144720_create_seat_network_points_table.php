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
        Schema::create('seat_network_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade');
            $table->string('code'); // A01-01, A01-02
            $table->string('mac_address')->nullable();
            $table->string('ip')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            $table->index('seat_id');
            $table->index('ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seat_network_points');
    }
};
