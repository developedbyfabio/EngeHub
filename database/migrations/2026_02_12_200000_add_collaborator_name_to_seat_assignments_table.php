<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seat_assignments', function (Blueprint $table) {
            $table->string('collaborator_name', 255)->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('seat_assignments', function (Blueprint $table) {
            $table->dropColumn('collaborator_name');
        });
    }
};
