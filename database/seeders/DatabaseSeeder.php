<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tab;
use App\Models\Card;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Chamar o seeder de usuário admin
        $this->call([
            AdminUserSeeder::class,
        ]);

        // DVRs do módulo Câmeras (se a tabela existir)
        if (\Schema::hasTable('dvrs')) {
            $this->call(DvrSeeder::class);
        }

        // Criar permissões
        Permission::create(['name' => 'manage tabs']);
        Permission::create(['name' => 'manage cards']);
        Permission::create(['name' => 'access admin']);

        // Criar role de admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['manage tabs', 'manage cards', 'access admin']);

        // Criar usuário admin
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@engehub.com',
            'password' => bcrypt('Jgr34eng02@'),
        ]);
        $admin->assignRole('admin');

        // Criar abas de exemplo
        $tabs = [
            [
                'name' => 'Sistemas Principais',
                'description' => 'Sistemas essenciais para o dia a dia',
                'color' => '#3B82F6',
                'order' => 1
            ],
            [
                'name' => 'Ferramentas',
                'description' => 'Ferramentas e utilitários',
                'color' => '#10B981',
                'order' => 2
            ],
            [
                'name' => 'Documentação',
                'description' => 'Manuais e documentação',
                'color' => '#F59E0B',
                'order' => 3
            ]
        ];

        foreach ($tabs as $tabData) {
            $tab = Tab::create($tabData);
        }

        // Criar cards de exemplo
        $cards = [
            [
                'name' => 'ERP Principal',
                'description' => 'Sistema ERP da empresa',
                'link' => 'https://erp.engehub.com',
                'tab_id' => 1,
                'order' => 1,
                'icon' => 'fas fa-cogs'
            ],
            [
                'name' => 'CRM',
                'description' => 'Gestão de relacionamento com clientes',
                'link' => 'https://crm.engehub.com',
                'tab_id' => 1,
                'order' => 2,
                'icon' => 'fas fa-users'
            ],
            [
                'name' => 'E-mail Corporativo',
                'description' => 'Acesso ao e-mail da empresa',
                'link' => 'https://mail.engehub.com',
                'tab_id' => 2,
                'order' => 1,
                'icon' => 'fas fa-envelope'
            ],
            [
                'name' => 'Drive Compartilhado',
                'description' => 'Arquivos compartilhados da empresa',
                'link' => 'https://drive.engehub.com',
                'tab_id' => 2,
                'order' => 2,
                'icon' => 'fas fa-cloud'
            ],
            [
                'name' => 'Manual do Colaborador',
                'description' => 'Manual de procedimentos internos',
                'link' => 'https://docs.engehub.com/manual',
                'tab_id' => 3,
                'order' => 1,
                'icon' => 'fas fa-book'
            ]
        ];

        foreach ($cards as $cardData) {
            Card::create($cardData);
        }
    }
} 