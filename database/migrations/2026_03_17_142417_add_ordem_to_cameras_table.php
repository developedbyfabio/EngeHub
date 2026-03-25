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
        Schema::table('cameras', function (Blueprint $table) {
            $table->unsignedInteger('ordem')->default(0)->after('status');
        });
        // Define ordem = id para câmeras existentes (mais antiga = menor id = primeiro)
        \DB::statement('UPDATE cameras SET ordem = id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cameras', function (Blueprint $table) {
            $table->dropColumn('ordem');
        });
    }
};
