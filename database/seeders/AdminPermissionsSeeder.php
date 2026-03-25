<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserPermission;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o usuário administrador
        $admin = User::where('email', 'admin@engepecas.com')->first();
        
        if (!$admin) {
            $this->command->error('Usuário administrador não encontrado!');
            return;
        }
        
        // Criar todas as permissões para o administrador
        $permissions = [
            UserPermission::VIEW_PASSWORDS,
            UserPermission::MANAGE_SYSTEM_USERS,
            UserPermission::FULL_ACCESS
        ];
        
        foreach ($permissions as $permissionType) {
            UserPermission::updateOrCreate(
                [
                    'user_id' => $admin->id,
                    'permission_type' => $permissionType
                ],
                [
                    'is_active' => true
                ]
            );
        }
        
        $this->command->info('Permissões do administrador criadas com sucesso!');
        $this->command->info('Usuário: ' . $admin->email);
        $this->command->info('Permissões: ' . implode(', ', $permissions));
    }
}
