<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('standard_weight_profiles', function (Blueprint $table) {
            $table->foreignId('form_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        $firstFormId = \DB::table('forms')->orderBy('id')->value('id');
        if ($firstFormId) {
            \DB::table('standard_weight_profiles')->whereNull('form_id')->update(['form_id' => $firstFormId]);
        } else {
            \DB::table('standard_weight_profiles')->delete();
        }

    }

    public function down(): void
    {
        Schema::table('standard_weight_profiles', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
        });
    }
};
