<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemLogin;
use App\Models\SystemUser;
use App\Models\SystemLoginPermission;

class SystemLoginPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar alguns logins existentes
        $systemLogins = SystemLogin::take(3)->get();
        
        // Buscar alguns usuários do sistema
        $systemUsers = SystemUser::take(2)->get();
        
        if ($systemLogins->count() > 0 && $systemUsers->count() > 0) {
            // Criar permissões de exemplo
            foreach ($systemLogins as $login) {
                foreach ($systemUsers as $user) {
                    SystemLoginPermission::create([
                        'system_login_id' => $login->id,
                        'system_user_id' => $user->id,
                        'is_active' => true
                    ]);
                }
            }
            
            $this->command->info('Permissões de login criadas com sucesso!');
            $this->command->info('Logins: ' . $systemLogins->count());
            $this->command->info('Usuários: ' . $systemUsers->count());
        } else {
            $this->command->warn('Nenhum login ou usuário encontrado para criar permissões.');
        }
    }
}