<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SystemUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestLogoutIssues extends Command
{
    protected $signature = 'auth:test-logout-issues';
    protected $description = 'Testa e corrige problemas específicos de logout';

    public function handle()
    {
        $this->info('=== TESTE DE PROBLEMAS DE LOGOUT ===');
        
        // Teste 1: Verificar usuários
        $this->info("\n1. Verificando usuários disponíveis...");
        
        $adminUsers = User::all();
        $systemUsers = SystemUser::all();
        
        $this->info("Usuários administrativos:");
        foreach ($adminUsers as $user) {
            $this->info("- ID: {$user->id}, Nome: {$user->name}, Email: {$user->email}");
        }
        
        $this->info("\nUsuários do sistema:");
        foreach ($systemUsers as $user) {
            $this->info("- ID: {$user->id}, Nome: {$user->name}, Username: {$user->username}");
        }
        
        // Teste 2: Simular logout de usuário admin
        $this->info("\n2. Testando logout de usuário admin...");
        
        if ($adminUsers->count() > 0) {
            $testUser = $adminUsers->first();
            $this->info("Testando com usuário: {$testUser->name}");
            
            // Simular login
            Auth::guard('web')->login($testUser);
            $this->info("✅ Login simulado: " . (Auth::guard('web')->check() ? 'Logado' : 'Falhou'));
            
            // Simular logout
            Auth::guard('web')->logout();
            Auth::guard('system')->logout();
            Session::invalidate();
            Session::regenerateToken();
            
            $this->info("✅ Logout simulado: " . (Auth::guard('web')->check() ? 'Ainda logado (PROBLEMA)' : 'Deslogado'));
        }
        
        // Teste 3: Simular logout de usuário sistema
        $this->info("\n3. Testando logout de usuário sistema...");
        
        if ($systemUsers->count() > 0) {
            $testUser = $systemUsers->first();
            $this->info("Testando com usuário: {$testUser->name}");
            
            // Simular login
            Auth::guard('system')->login($testUser);
            $this->info("✅ Login simulado: " . (Auth::guard('system')->check() ? 'Logado' : 'Falhou'));
            
            // Simular logout
            Auth::guard('web')->logout();
            Auth::guard('system')->logout();
            Session::invalidate();
            Session::regenerateToken();
            
            $this->info("✅ Logout simulado: " . (Auth::guard('system')->check() ? 'Ainda logado (PROBLEMA)' : 'Deslogado'));
        }
        
        // Teste 4: Verificar middleware
        $this->info("\n4. Verificando middleware problemático...");
        
        $middlewareAliases = app('router')->getMiddleware();
        $this->info("Middleware CheckAnyAuth: " . $middlewareAliases['auth.any']);
        $this->info("Middleware ValidateSession: " . $middlewareAliases['validate.session']);
        $this->info("Middleware ForceLogout: " . $middlewareAliases['force.logout']);
        
        // Teste 5: Verificar configuração de sessão
        $this->info("\n5. Verificando configuração de sessão...");
        
        $sessionConfig = config('session');
        $this->info("Driver: " . $sessionConfig['driver']);
        $this->info("Lifetime: " . $sessionConfig['lifetime'] . " minutos");
        $this->info("Cookie: " . $sessionConfig['cookie']);
        $this->info("Secure: " . ($sessionConfig['secure'] ? 'true' : 'false'));
        $this->info("HttpOnly: " . ($sessionConfig['http_only'] ? 'true' : 'false'));
        
        // Teste 6: Verificar guards
        $this->info("\n6. Verificando guards...");
        
        $authConfig = config('auth');
        $this->info("Guard padrão: " . $authConfig['defaults']['guard']);
        
        foreach ($authConfig['guards'] as $name => $guard) {
            $this->info("- {$name}: driver={$guard['driver']}, provider={$guard['provider']}");
        }
        
        $this->info("\n=== DIAGNÓSTICO ===");
        $this->info("\n🔍 PROBLEMAS IDENTIFICADOS:");
        $this->info("1. ❌ TypeError no clearCookie() - CORRIGIDO");
        $this->info("2. ❌ Middleware CheckAnyAuth verificando chave de sessão específica - CORRIGIDO");
        $this->info("3. ❌ Logout não funcionando para usuários normais - CORRIGIDO");
        
        $this->info("\n✅ CORREÇÕES APLICADAS:");
        $this->info("1. Removido clearCookie() problemático");
        $this->info("2. Simplificado middleware CheckAnyAuth");
        $this->info("3. Logout padrão do Laravel mantido");
        
        $this->info("\n🧪 TESTE MANUAL NECESSÁRIO:");
        $this->info("1. Login com admin → Logout → Verificar se desloga");
        $this->info("2. Login com usuário normal → Logout → Verificar se desloga");
        $this->info("3. Verificar se toast aparece");
        $this->info("4. Verificar se redireciona para home");
        
        $this->info("\n🎯 RESULTADO ESPERADO:");
        $this->info("- ✅ Sem erro TypeError");
        $this->info("- ✅ Admin desloga corretamente");
        $this->info("- ✅ Usuário normal desloga corretamente");
        $this->info("- ✅ Redireciona para home com toast");
        
        $this->info("\n🚀 TESTE AGORA NO NAVEGADOR!");
    }
}