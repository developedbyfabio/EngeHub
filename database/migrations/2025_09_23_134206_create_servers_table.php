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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address');
            $table->foreignId('data_center_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->string('group_name')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('monitor_status')->default(true);
            $table->enum('status', ['online', 'offline', 'unknown'])->default('unknown');
            $table->timestamp('last_status_check')->nullable();
            $table->integer('response_time')->nullable(); // em milissegundos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
