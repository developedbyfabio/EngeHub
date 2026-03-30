<?php

use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserPermission;
use App\Support\NavPermission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('full_access')->default(false);
            $table->boolean('is_system')->default(false);
            $table->json('nav_permissions')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_group_id')->nullable()->after('id')->constrained('user_groups')->nullOnDelete();
        });

        $fullMap = NavPermission::fullPermissionMap();

        $adminId = DB::table('user_groups')->insertGetId([
            'name' => 'Administradores',
            'slug' => UserGroup::SLUG_ADMINISTRADORES,
            'full_access' => true,
            'is_system' => true,
            'nav_permissions' => json_encode($fullMap),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $defaultMap = $fullMap;
        foreach (NavPermission::adminKeys() as $k) {
            $defaultMap[$k] = false;
        }
        foreach (NavPermission::allKeys() as $k) {
            if (! isset($defaultMap[$k])) {
                $defaultMap[$k] = false;
            }
        }
        $defaultMap[NavPermission::HOME] = true;
        $defaultMap[NavPermission::SERVERS] = true;
        $defaultMap[NavPermission::CAMERAS] = true;
        $defaultMap[NavPermission::FILIAIS] = true;

        $usuariosId = DB::table('user_groups')->insertGetId([
            'name' => 'Usuários',
            'slug' => UserGroup::SLUG_USUARIOS,
            'full_access' => false,
            'is_system' => true,
            'nav_permissions' => json_encode($defaultMap),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminUserIds = UserPermission::query()
            ->whereIn('permission_type', [UserPermission::FULL_ACCESS, UserPermission::MANAGE_SYSTEM_USERS])
            ->where('is_active', true)
            ->pluck('user_id')
            ->unique()
            ->filter();

        if ($adminUserIds->isNotEmpty()) {
            User::query()->whereIn('id', $adminUserIds)->update(['user_group_id' => $adminId]);
        }

        User::query()->whereNull('user_group_id')->update(['user_group_id' => $usuariosId]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_group_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_group_id');
        });
        Schema::dropIfExists('user_groups');
    }
};
