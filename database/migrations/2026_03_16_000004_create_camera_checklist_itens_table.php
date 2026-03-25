<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camera_checklist_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camera_checklist_id')->constrained()->onDelete('cascade');
            $table->foreignId('camera_id')->constrained()->onDelete('cascade');
            $table->string('status_operacional')->default('nao_verificada'); // online, offline, com_alerta, nao_verificada
            $table->boolean('gravando')->nullable();
            $table->boolean('problema')->default(false);
            $table->text('descricao_problema')->nullable();
            $table->text('acao_corretiva_necessaria')->nullable();
            $table->text('acao_corretiva_realizada')->nullable();
            $table->string('status_acao')->nullable(); // pendente, em_andamento, resolvido
            $table->text('motivo_nao_resolvido')->nullable();
            $table->text('observacao')->nullable();
            $table->string('evidencia_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camera_checklist_itens');
    }
};
