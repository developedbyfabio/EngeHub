<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $db = DB::getDatabaseName();

        $legacyUnique = DB::selectOne(
            'SELECT 1 AS ok FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$db, 'form_links', 'form_links_form_id_branch_id_unique']
        );
        if ($legacyUnique) {
            Schema::table('form_links', function (Blueprint $table) {
                $table->dropUnique(['form_id', 'branch_id']);
            });
        }

        if (! Schema::hasColumn('form_links', 'sector_id')) {
            Schema::table('form_links', function (Blueprint $table) {
                $table->foreignId('sector_id')->nullable()->after('branch_id')->constrained()->onDelete('cascade');
            });
        }

        $functionalIdx = DB::selectOne(
            'SELECT 1 AS ok FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$db, 'form_links', 'form_links_form_branch_sector_unique']
        );
        if (! $functionalIdx) {
            DB::statement('CREATE UNIQUE INDEX form_links_form_branch_sector_unique ON form_links (form_id, branch_id, (COALESCE(sector_id, 0)))');
        }

        if (! Schema::hasColumn('form_responses', 'sector_id')) {
            Schema::table('form_responses', function (Blueprint $table) {
                $table->foreignId('sector_id')->nullable()->after('branch_id')->constrained()->onDelete('set null');
            });
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        try {
            DB::statement('DROP INDEX form_links_form_branch_sector_unique ON form_links');
        } catch (\Throwable $e) {
            // já removido
        }

        if (Schema::hasColumn('form_links', 'sector_id')) {
            Schema::table('form_links', function (Blueprint $table) {
                $table->dropForeign(['sector_id']);
                $table->dropColumn('sector_id');
            });
        }

        $legacyUnique = DB::selectOne(
            'SELECT 1 AS ok FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [DB::getDatabaseName(), 'form_links', 'form_links_form_id_branch_id_unique']
        );
        if (! $legacyUnique) {
            Schema::table('form_links', function (Blueprint $table) {
                $table->unique(['form_id', 'branch_id']);
            });
        }

        if (Schema::hasColumn('form_responses', 'sector_id')) {
            Schema::table('form_responses', function (Blueprint $table) {
                $table->dropForeign(['sector_id']);
                $table->dropColumn('sector_id');
            });
        }
    }
};
