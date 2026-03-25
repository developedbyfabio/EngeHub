<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_response_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('form_responses')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('form_questions')->onDelete('cascade');
            $table->foreignId('selected_option_id')->constrained('form_question_options')->onDelete('cascade');
            $table->integer('weight');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_response_answers');
    }
};
