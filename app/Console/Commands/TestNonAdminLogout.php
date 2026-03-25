<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SystemUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestNonAdminLogout extends Command
{
    protected $signature = 'auth:test-non-admin-logout';
    protected $description = 'Testa especificamente o logout de usuários não administradores';

    public function handle()
    {
        $this->info('=== TESTE DE LOGOUT DE USUÁRIOS NÃO ADMINISTRADORES ===');
        
        // Teste 1: Verificar usuários não administradores
        $this->info("\n1. Verificando usuários não administradores...");
        
        $systemUsers = SystemUser::all();
        
        $this->info("Usuários do sistema (não administradores):");
        foreach ($systemUsers as $user) {
            $this->info("- ID: {$user->id}, Nome: {$user->name}, Username: {$user->username}");
        }
        
        if ($systemUsers->isEmpty()) {
            $this->error("Nenhum usuário do sistema encontrado!");
            return;
        }
        
        // Teste 2: Simular login e logout de usuário não admin
        $this->info("\n2. Simulando login/logout de usuário não admin...");
        
        $testUser = $systemUsers->first();
        $this->info("Testando com usuário: {$testUser->name} (ID: {$testUser->id})");
        
        // Simular login
        Auth::guard('system')->login($testUser);
        $this->info("✅ Login simulado: " . (Auth::guard('system')->check() ? 'Logado' : 'Falhou'));
        
        if (Auth::guard('system')->check()) {
            $loggedUser = Auth::guard('system')->user();
            $this->info("Usuário logado: {$loggedUser->name} (ID: {$loggedUser->id})");
        }
        
        // Simular logout
        $this->info("\nFazendo logout...");
        Auth::guard('web')->logout();
        Auth::guard('system')->logout();
        Session::invalidate();
        Session::regenerateToken();
        
        $this->info("✅ Logout simulado: " . (Auth::guard('system')->check() ? 'AINDA LOGADO (PROBLEMA!)' : 'Deslogado com sucesso'));
        
        // Teste 3: Verificar configuração de guards
        $this->info("\n3. Verificando configuração de guards...");
        
        $authConfig = config('auth');
        $this->info("Guard padrão: " . $authConfig['defaults']['guard']);
        
        foreach ($authConfig['guards'] as $name => $guard) {
            $this->info("- {$name}: driver={$guard['driver']}, provider={$guard['provider']}");
        }
        
        // Teste 4: Verificar middleware aplicados
        $this->info("\n4. Verificando middleware aplicados...");
        
        $routes = app('router')->getRoutes();
        $homeRoute = null;
        
        foreach ($routes as $route) {
            if ($route->getName() === 'home') {
                $homeRoute = $route;
                break;
            }
        }
        
        if ($homeRoute) {
            $middleware = $homeRoute->middleware();
            $this->info("Middleware na rota home:");
            foreach ($middleware as $mw) {
                $this->info("- {$mw}");
            }
        }
        
        // Teste 5: Verificar middleware problemáticos
        $this->info("\n5. Verificando middleware problemáticos...");
        
        $middlewareAliases = app('router')->getMiddleware();
        
        $problematicMiddleware = ['validate.session', 'force.logout', 'auth.any'];
        foreach ($problematicMiddleware as $alias) {
            if (isset($middlewareAliases[$alias])) {
                $this->info("- {$alias}: {$middlewareAliases[$alias]}");
            }
        }
        
        $this->info("\n=== DIAGNÓSTICO ===");
        $this->info("\n🔍 POSSÍVEIS PROBLEMAS:");
        $this->info("1. Middleware ValidateSession verificando chave específica");
        $this->info("2. Middleware ForceLogoutAfterSession verificando chave específica");
        $this->info("3. Middleware aplicado às rotas admin causando interferência");
        
        $this->info("\n✅ CORREÇÕES APLICADAS:");
        $this->info("1. Simplificado ValidateSession");
        $this->info("2. Simplificado ForceLogoutAfterSession");
        $this->info("3. Removido validate.session das rotas admin");
        $this->info("4. Removido botão 'Voltar ao Engehub' da página de login");
        
        $this->info("\n🧪 TESTE MANUAL NECESSÁRIO:");
        $this->info("1. Login com usuário não admin → Logout");
        $this->info("2. Verificar se desloga completamente");
        $this->info("3. Verificar se ao recarregar página não volta logado");
        $this->info("4. Verificar se toast aparece");
        
        $this->info("\n🎯 RESULTADO ESPERADO:");
        $this->info("- ✅ Usuário não admin desloga completamente");
        $this->info("- ✅ Não fica logado infinitamente");
        $this->info("- ✅ Redireciona para home com toast");
        $this->info("- ✅ Ao recarregar página, permanece deslogado");
        
        $this->info("\n🚀 TESTE AGORA NO NAVEGADOR!");
    }
}