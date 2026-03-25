<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Adicionar índices para que as FKs (form_id, branch_id) continuem válidas após remover o unique
        Schema::table('form_links', function (Blueprint $table) {
            $table->index('form_id');
            $table->index('branch_id');
        });

        Schema::table('form_links', function (Blueprint $table) {
            $table->dropUnique(['form_id', 'branch_id']);
        });

        Schema::table('form_links', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->after('branch_id')->constrained()->onDelete('cascade');
        });

        // Índice único funcional no MySQL 8+: expressão entre parênteses duplos
        DB::statement('CREATE UNIQUE INDEX form_links_form_branch_sector_unique ON form_links (form_id, branch_id, (COALESCE(sector_id, 0)))');

        Schema::table('form_responses', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->after('branch_id')->constrained()->onDelete('set null');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        DB::statement('DROP INDEX form_links_form_branch_sector_unique ON form_links');

        Schema::table('form_links', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });

        Schema::table('form_links', function (Blueprint $table) {
            $table->unique(['form_id', 'branch_id']);
        });

        Schema::table('form_responses', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });
    }
};
