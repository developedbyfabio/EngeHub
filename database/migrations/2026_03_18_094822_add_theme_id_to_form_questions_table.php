<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_questions', function (Blueprint $table) {
            $table->foreignId('theme_id')->nullable()->after('form_id')->constrained('form_themes')->onDelete('cascade');
        });

        // Migrar perguntas existentes para um tema padrão
        $formsWithQuestions = \DB::table('form_questions')->select('form_id')->distinct()->pluck('form_id');
        foreach ($formsWithQuestions as $formId) {
            $themeId = \DB::table('form_themes')->insertGetId([
                'form_id' => $formId,
                'title' => 'Perguntas gerais',
                'description' => null,
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \DB::table('form_questions')->where('form_id', $formId)->update(['theme_id' => $themeId]);
        }

    }

    public function down(): void
    {
        Schema::table('form_questions', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
        });
    }
};
