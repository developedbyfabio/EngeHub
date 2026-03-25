<?php

namespace App\Console\Commands;

use App\Models\SystemUser;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateSecretUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secret-url:generate 
                            {--all : Gerar URLs para todos os usuários que ainda não possuem}
                            {--regenerate : Regenerar URLs para todos os usuários (inclusive os que já possuem)}
                            {--user= : ID do usuário específico para gerar URL}
                            {--system-user= : ID do SystemUser específico para gerar URL}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gera URLs secretas para usuários do sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Gerador de URLs Secretas ===');
        $this->newLine();
        
        // Gerar para um usuário específico
        if ($userId = $this->option('user')) {
            return $this->generateForUser($userId);
        }
        
        // Gerar para um SystemUser específico
        if ($systemUserId = $this->option('system-user')) {
            return $this->generateForSystemUser($systemUserId);
        }
        
        // Gerar para todos
        if ($this->option('all') || $this->option('regenerate')) {
            return $this->generateForAll($this->option('regenerate'));
        }
        
        // Menu interativo
        $choice = $this->choice(
            'O que você deseja fazer?',
            [
                'Gerar URLs para todos os usuários que ainda não possuem',
                'Regenerar URLs para TODOS os usuários (inclusive os que já possuem)',
                'Gerar URL para um usuário específico',
                'Listar usuários com URLs secretas',
                'Cancelar'
            ],
            0
        );
        
        switch ($choice) {
            case 'Gerar URLs para todos os usuários que ainda não possuem':
                return $this->generateForAll(false);
                
            case 'Regenerar URLs para TODOS os usuários (inclusive os que já possuem)':
                if ($this->confirm('ATENÇÃO: Isso invalidará todas as URLs existentes. Deseja continuar?')) {
                    return $this->generateForAll(true);
                }
                $this->info('Operação cancelada.');
                return 0;
                
            case 'Gerar URL para um usuário específico':
                $userId = $this->ask('Digite o ID do usuário (da tabela users)');
                return $this->generateForUser($userId);
                
            case 'Listar usuários com URLs secretas':
                return $this->listUsersWithSecretUrls();
                
            default:
                $this->info('Operação cancelada.');
                return 0;
        }
    }
    
    /**
     * Gerar URL para um usuário específico
     */
    protected function generateForUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("Usuário com ID {$userId} não encontrado.");
            return 1;
        }
        
        // Buscar ou criar SystemUser
        $systemUser = SystemUser::where('user_id', $user->id)->first();
        
        if (!$systemUser) {
            $this->info("Criando SystemUser para {$user->name}...");
            $systemUser = SystemUser::create([
                'name' => $user->name,
                'username' => $user->username ?: $user->email,
                'password' => 'N/A',
                'is_active' => true,
                'user_id' => $user->id
            ]);
        }
        
        $oldUrl = $systemUser->secret_url;
        $newUrl = $systemUser->generateSecretUrl();
        
        $this->info("✅ URL gerada para: {$user->name}");
        $this->info("   URL anterior: " . ($oldUrl ?: 'Nenhuma'));
        $this->info("   Nova URL: {$systemUser->full_secret_url}");
        
        return 0;
    }
    
    /**
     * Gerar URL para um SystemUser específico
     */
    protected function generateForSystemUser($systemUserId)
    {
        $systemUser = SystemUser::find($systemUserId);
        
        if (!$systemUser) {
            $this->error("SystemUser com ID {$systemUserId} não encontrado.");
            return 1;
        }
        
        $oldUrl = $systemUser->secret_url;
        $newUrl = $systemUser->generateSecretUrl();
        
        $this->info("✅ URL gerada para: {$systemUser->name}");
        $this->info("   URL anterior: " . ($oldUrl ?: 'Nenhuma'));
        $this->info("   Nova URL: {$systemUser->full_secret_url}");
        
        return 0;
    }
    
    /**
     * Gerar URLs para todos os usuários
     */
    protected function generateForAll($regenerate = false)
    {
        $this->info($regenerate 
            ? 'Regenerando URLs para TODOS os usuários...' 
            : 'Gerando URLs para usuários sem URL secreta...');
        
        $this->newLine();
        
        // Buscar todos os SystemUsers
        $query = SystemUser::query();
        
        if (!$regenerate) {
            $query->whereNull('secret_url');
        }
        
        $systemUsers = $query->get();
        
        if ($systemUsers->isEmpty()) {
            $this->info('Nenhum usuário para processar.');
            return 0;
        }
        
        $bar = $this->output->createProgressBar($systemUsers->count());
        $bar->start();
        
        $generated = 0;
        $errors = 0;
        
        foreach ($systemUsers as $systemUser) {
            try {
                $systemUser->generateSecretUrl();
                $generated++;
            } catch (\Exception $e) {
                $errors++;
                $this->error("Erro ao gerar URL para {$systemUser->name}: {$e->getMessage()}");
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("✅ URLs geradas: {$generated}");
        
        if ($errors > 0) {
            $this->error("❌ Erros: {$errors}");
        }
        
        // Também verificar usuários que não têm SystemUser
        $usersWithoutSystemUser = User::whereDoesntHave('systemUser')->get();
        
        if ($usersWithoutSystemUser->isNotEmpty()) {
            $this->newLine();
            $this->warn("Existem {$usersWithoutSystemUser->count()} usuário(s) sem SystemUser associado:");
            
            foreach ($usersWithoutSystemUser as $user) {
                $this->line("  - {$user->name} (ID: {$user->id})");
            }
            
            if ($this->confirm('Deseja criar SystemUsers e gerar URLs para estes usuários?')) {
                foreach ($usersWithoutSystemUser as $user) {
                    $systemUser = SystemUser::create([
                        'name' => $user->name,
                        'username' => $user->username ?: $user->email,
                        'password' => 'N/A',
                        'is_active' => true,
                        'user_id' => $user->id
                    ]);
                    
                    $systemUser->generateSecretUrl();
                    $this->info("  ✅ Criado SystemUser e URL para: {$user->name}");
                }
            }
        }
        
        return 0;
    }
    
    /**
     * Listar usuários com URLs secretas
     */
    protected function listUsersWithSecretUrls()
    {
        $systemUsers = SystemUser::whereNotNull('secret_url')->get();
        
        if ($systemUsers->isEmpty()) {
            $this->info('Nenhum usuário possui URL secreta.');
            return 0;
        }
        
        $this->info("Total: {$systemUsers->count()} usuário(s) com URL secreta");
        $this->newLine();
        
        $headers = ['ID', 'Nome', 'Status', 'Expira em', 'URL'];
        $rows = [];
        
        foreach ($systemUsers as $systemUser) {
            $status = $systemUser->isSecretUrlValid() ? '✅ Válida' : '❌ Inválida';
            $expiresAt = $systemUser->secret_url_expires_at 
                ? $systemUser->secret_url_expires_at->format('d/m/Y H:i') 
                : 'Nunca';
            
            $rows[] = [
                $systemUser->id,
                $systemUser->name,
                $status,
                $expiresAt,
                $systemUser->full_secret_url
            ];
        }
        
        $this->table($headers, $rows);
        
        return 0;
    }
}
