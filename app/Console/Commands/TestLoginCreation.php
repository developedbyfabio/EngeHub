<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemLogin;
use App\Models\Card;

class TestLoginCreation extends Command
{
    protected $signature = 'test:login-creation';
    protected $description = 'Testa a criação e exibição de logins';

    public function handle()
    {
        $this->info('=== TESTE DE CRIAÇÃO E EXIBIÇÃO DE LOGINS ===');
        
        // Buscar um card
        $card = Card::first();
        if (!$card) {
            $this->error('Nenhum card encontrado!');
            return;
        }
        
        $this->info("Card encontrado: {$card->name} (ID: {$card->id})");
        
        // Contar logins existentes
        $existingLogins = $card->systemLogins()->count();
        $this->info("Logins existentes: {$existingLogins}");
        
        // Listar logins existentes
        $this->info("\nLogins existentes:");
        foreach ($card->systemLogins as $login) {
            $this->info("- ID: {$login->id}, Title: {$login->title}, Username: {$login->username}");
        }
        
        // Criar um novo login de teste
        $this->info("\nCriando novo login de teste...");
        $newLogin = SystemLogin::create([
            'card_id' => $card->id,
            'title' => 'Teste ' . now()->format('H:i:s'),
            'username' => 'teste',
            'password' => '123456',
            'notes' => 'Login criado via comando de teste',
            'is_active' => true
        ]);
        
        $this->info("Login criado com sucesso! ID: {$newLogin->id}");
        
        // Verificar se aparece na consulta
        $this->info("\nVerificando se o login aparece na consulta...");
        $allLogins = $card->systemLogins()->orderBy('title')->get();
        $this->info("Total de logins após criação: {$allLogins->count()}");
        
        foreach ($allLogins as $login) {
            $this->info("- ID: {$login->id}, Title: {$login->title}, Username: {$login->username}");
        }
        
        // Verificar permissões
        $this->info("\nVerificando permissões do novo login...");
        $permissions = $newLogin->permissions()->count();
        $this->info("Permissões associadas: {$permissions}");
        
        if ($permissions === 0) {
            $this->warn("ATENÇÃO: O novo login não tem permissões associadas!");
            $this->info("Isso pode causar problemas na exibição para usuários do sistema.");
        }
        
        $this->info("\n=== TESTE CONCLUÍDO ===");
    }
}