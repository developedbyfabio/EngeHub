<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camera_checklist_anexos', function (Blueprint $table) {
            $table->foreignId('camera_id')->nullable()->after('dvr_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('camera_checklist_anexos', function (Blueprint $table) {
            $table->dropForeign(['camera_id']);
        });
    }
};
