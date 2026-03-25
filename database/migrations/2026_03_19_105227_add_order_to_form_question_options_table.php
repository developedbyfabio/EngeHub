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
        Schema::table('form_question_options', function (Blueprint $table) {
            $table->unsignedInteger('order')->default(0)->after('weight');
        });

        // Backfill: ordem inicial igual ao id (preserva ordem atual)
        \DB::statement('UPDATE form_question_options SET `order` = id WHERE `order` = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_question_options', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
