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
        Schema::table('dvrs', function (Blueprint $table) {
            $table->string('acesso_web', 500)->nullable()->after('localizacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dvrs', function (Blueprint $table) {
            $table->dropColumn('acesso_web');
        });
    }
};
