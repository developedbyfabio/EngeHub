<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camera_checklist_anexos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camera_checklist_id')->constrained()->onDelete('cascade');
            $table->string('caminho_arquivo');
            $table->string('nome_original');
            $table->string('tipo_arquivo', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camera_checklist_anexos');
    }
};
