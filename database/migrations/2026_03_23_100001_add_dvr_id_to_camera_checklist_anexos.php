<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('camera_checklist_anexos', function (Blueprint $table) {
            $table->foreignId('dvr_id')->nullable()->after('camera_checklist_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('camera_checklist_anexos', function (Blueprint $table) {
            $table->dropForeign(['dvr_id']);
        });
    }
};
