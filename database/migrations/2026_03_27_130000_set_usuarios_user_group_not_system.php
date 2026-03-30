<?php

use App\Models\UserGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('user_groups')
            ->where('slug', UserGroup::SLUG_USUARIOS)
            ->update(['is_system' => false]);
    }

    public function down(): void
    {
        DB::table('user_groups')
            ->where('slug', UserGroup::SLUG_USUARIOS)
            ->update(['is_system' => true]);
    }
};
