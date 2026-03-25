<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin com username
        $adminUser = User::create([
            'name' => 'Administrador',
            'email' => 'admin@engehub.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Criar permissão de acesso total
        UserPermission::create([
            'user_id' => $adminUser->id,
            'permission_type' => UserPermission::FULL_ACCESS,
            'is_active' => true,
        ]);

        $this->command->info('Usuário administrador criado com sucesso!');
        $this->command->info('Email: admin@engehub.com');
        $this->command->info('Senha: admin123');
    }
}
