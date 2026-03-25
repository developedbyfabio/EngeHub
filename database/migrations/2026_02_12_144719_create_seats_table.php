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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('network_map_id')->constrained('network_maps')->onDelete('cascade');
            $table->string('code'); // A01, V17, etc
            $table->string('setor')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            $table->unique(['network_map_id', 'code']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
