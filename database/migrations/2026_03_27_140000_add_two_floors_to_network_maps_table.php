<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('network_maps', function (Blueprint $table) {
            $table->boolean('has_two_floors')->default(false)->after('file_path');
            $table->string('file_name_floor2')->nullable()->after('has_two_floors');
        });
    }

    public function down(): void
    {
        Schema::table('network_maps', function (Blueprint $table) {
            $table->dropColumn(['has_two_floors', 'file_name_floor2']);
        });
    }
};
