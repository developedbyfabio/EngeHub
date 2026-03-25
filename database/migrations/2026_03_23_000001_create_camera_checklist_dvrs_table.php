<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camera_checklist_dvrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camera_checklist_id')->constrained()->onDelete('cascade');
            $table->foreignId('dvr_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['camera_checklist_id', 'dvr_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camera_checklist_dvrs');
    }
};
