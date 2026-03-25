<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('system_users', function (Blueprint $table) {
            $table->string('secret_url', 64)->unique()->nullable()->after('user_id');
            $table->timestamp('secret_url_expires_at')->nullable()->after('secret_url');
            $table->timestamp('secret_url_generated_at')->nullable()->after('secret_url_expires_at');
            $table->boolean('secret_url_enabled')->default(true)->after('secret_url_generated_at');
            
            // Índice para busca rápida
            $table->index('secret_url_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_users', function (Blueprint $table) {
            $table->dropIndex(['secret_url_enabled']);
            $table->dropColumn([
                'secret_url',
                'secret_url_expires_at',
                'secret_url_generated_at',
                'secret_url_enabled'
            ]);
        });
    }
};
