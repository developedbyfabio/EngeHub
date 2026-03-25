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
        Schema::table('servers', function (Blueprint $table) {
            $table->foreignId('server_group_id')->nullable()->constrained('server_groups')->onDelete('set null');
            $table->dropColumn('group_name'); // Remover o campo group_name antigo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropForeign(['server_group_id']);
            $table->dropColumn('server_group_id');
            $table->string('group_name')->nullable(); // Restaurar o campo group_name
        });
    }
};