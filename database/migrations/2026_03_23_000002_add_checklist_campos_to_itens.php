<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camera_checklist_itens', function (Blueprint $table) {
            $table->boolean('online')->nullable()->after('status_operacional');
            $table->boolean('angulo_correto')->nullable()->after('online');
        });
    }

    public function down(): void
    {
        Schema::table('camera_checklist_itens', function (Blueprint $table) {
            $table->dropColumn(['online', 'angulo_correto']);
        });
    }
};
