<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('standard_weight_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('standard_weight_profiles')->onDelete('cascade');
            $table->string('option_text');
            $table->integer('weight');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standard_weight_options');
    }
};
