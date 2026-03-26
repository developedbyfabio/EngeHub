<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('network_map_id')->constrained('network_maps')->cascadeOnDelete();
            $table->string('type', 32);
            $table->string('code');
            $table->string('full_code');
            $table->string('setor')->nullable();
            $table->text('observacoes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['network_map_id', 'type', 'code']);
            $table->index(['network_map_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
