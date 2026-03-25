<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camera_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dvr_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('responsavel_nome')->nullable();
            $table->string('status'); // em_andamento, finalizado, cancelado
            $table->timestamp('iniciado_em');
            $table->timestamp('finalizado_em')->nullable();
            $table->text('observacoes_gerais')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camera_checklists');
    }
};
