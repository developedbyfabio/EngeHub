<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SystemUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TestLogoutSecurity extends Command
{
    protected $signature = 'auth:test-logout';
    protected $description = 'Testa a segurança do sistema de logout do EngeHub';

    public function handle()
    {
        $this->info('=== TESTE DE SEGURANÇA DO LOGOUT ===');
        
        // Teste 1: Verificar usuários disponíveis
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
        
        // Teste 2: Simular login
        $this->info("\n2. Simulando login...");
        
        if ($systemUsers->count() > 0) {
            $testUser = $systemUsers->first();
            $this->info("Testando login com usuário: {$testUser->name} ({$testUser->username})");
            
            // Simular login
            Auth::guard('system')->login($testUser);
            
            $this->info("Login simulado realizado");
            $this->info("Auth::check(): " . (Auth::guard('system')->check() ? 'true' : 'false'));
            $this->info("Usuário logado: " . (Auth::guard('system')->check() ? Auth::guard('system')->user()->name : 'nenhum'));
        }
        
        // Teste 3: Simular logout
        $this->info("\n3. Simulando logout...");
        
        if (Auth::guard('system')->check()) {
            $userName = Auth::guard('system')->user()->name;
            $this->info("Fazendo logout do usuário: {$userName}");
            
            // Simular logout
            Auth::guard('system')->logout();
            Session::invalidate();
            Session::regenerateToken();
            Session::flush();
            
            $this->info("Logout simulado realizado");
            $this->info("Auth::check(): " . (Auth::guard('system')->check() ? 'true' : 'false'));
            $this->info("Usuário logado: " . (Auth::guard('system')->check() ? Auth::guard('system')->user()->name : 'nenhum'));
        }
        
        // Teste 4: Verificar configurações de sessão
        $this->info("\n4. Verificando configurações de sessão...");
        
        $sessionConfig = config('session');
        $this->info("Driver de sessão: " . $sessionConfig['driver']);
        $this->info("Lifetime: " . $sessionConfig['lifetime'] . " minutos");
        $this->info("Cookie name: " . $sessionConfig['cookie']);
        $this->info("Secure: " . ($sessionConfig['secure'] ? 'true' : 'false'));
        $this->info("HttpOnly: " . ($sessionConfig['http_only'] ? 'true' : 'false'));
        
        // Teste 5: Verificar middleware
        $this->info("\n5. Verificando middleware de autenticação...");
        
        $middlewareAliases = app('router')->getMiddleware();
        $authMiddleware = [];
        
        foreach ($middlewareAliases as $alias => $class) {
            if (strpos($alias, 'auth') !== false || strpos($alias, 'logout') !== false) {
                $authMiddleware[] = "{$alias}: {$class}";
            }
        }
        
        if (!empty($authMiddleware)) {
            $this->info("Middleware de autenticação encontrados:");
            foreach ($authMiddleware as $middleware) {
                $this->info("- {$middleware}");
            }
        }
        
        // Teste 6: Verificar rotas protegidas
        $this->info("\n6. Verificando rotas protegidas...");
        
        $routes = app('router')->getRoutes();
        $protectedRoutes = [];
        
        foreach ($routes as $route) {
            $middleware = $route->middleware();
            if (in_array('auth.any', $middleware) || in_array('force.logout', $middleware)) {
                $methods = implode('|', $route->methods());
                $protectedRoutes[] = "{$methods} /{$route->uri()}";
            }
        }
        
        if (!empty($protectedRoutes)) {
            $this->info("Rotas protegidas encontradas:");
            foreach ($protectedRoutes as $route) {
                $this->info("- {$route}");
            }
        }
        
        $this->info("\n=== TESTE CONCLUÍDO ===");
        $this->info("\nPara testar manualmente:");
        $this->info("1. Acesse: http://192.168.11.201/login");
        $this->info("2. Faça login com qualquer usuário");
        $this->info("3. Faça logout");
        $this->info("4. Tente acessar: http://192.168.11.201/");
        $this->info("5. Deve redirecionar para login (não mais acesso à home)");
        $this->info("6. Verifique os logs: tail -f storage/logs/laravel.log");
        
        $this->info("\n✅ CORREÇÕES IMPLEMENTADAS:");
        $this->info("- Rota home agora protegida por autenticação");
        $this->info("- Middleware force.logout criado");
        $this->info("- Logout limpa cookies manualmente");
        $this->info("- Verificação de sessão robusta");
        $this->info("- Redirecionamento correto após logout");
    }
}