<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('camera_checklists')->get() as $row) {
            DB::table('camera_checklist_dvrs')->insertOrIgnore([
                'camera_checklist_id' => $row->id,
                'dvr_id' => $row->dvr_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('camera_checklists', function (Blueprint $table) {
            $table->foreignId('dvr_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('camera_checklists', function (Blueprint $table) {
            $table->foreignId('dvr_id')->nullable(false)->change();
        });
    }
};
