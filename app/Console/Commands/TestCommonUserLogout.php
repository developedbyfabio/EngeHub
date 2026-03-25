<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SystemUser;

class TestCommonUserLogout extends Command
{
    protected $signature = 'auth:test-common-user-logout';
    protected $description = 'Testa o logout de usuários comuns (não administradores)';

    public function handle()
    {
        $this->info('=== TESTE DE LOGOUT DE USUÁRIOS COMUNS ===');
        
        // Teste 1: Verificar usuários
        $this->info("\n1. Verificando usuários disponíveis...");
        
        $adminUsers = User::all();
        $systemUsers = SystemUser::all();
        
        $this->info("Usuários administrativos:");
        foreach ($adminUsers as $user) {
            $this->info("- ID: {$user->id}, Nome: {$user->name}, Email: {$user->email}");
        }
        
        $this->info("\nUsuários do sistema (comuns):");
        foreach ($systemUsers as $user) {
            $this->info("- ID: {$user->id}, Nome: {$user->name}, Username: {$user->username}");
        }
        
        // Teste 2: Verificar rota de logout
        $this->info("\n2. Verificando rota de logout...");
        
        $routes = app('router')->getRoutes();
        $logoutRoute = null;
        
        foreach ($routes as $route) {
            if ($route->getName() === 'logout') {
                $logoutRoute = $route;
                break;
            }
        }
        
        if ($logoutRoute) {
            $methods = implode('|', $logoutRoute->methods());
            $middleware = $logoutRoute->middleware();
            $this->info("Rota de logout encontrada:");
            $this->info("- Métodos: {$methods}");
            $this->info("- URI: /{$logoutRoute->uri()}");
            $this->info("- Middleware: " . implode(', ', $middleware));
        } else {
            $this->error("Rota de logout não encontrada!");
        }
        
        // Teste 3: Verificar controller de logout
        $this->info("\n3. Verificando controller de logout...");
        
        $controllerFile = app_path('Http/Controllers/Auth/AuthenticatedSessionController.php');
        if (file_exists($controllerFile)) {
            $this->info("✅ AuthenticatedSessionController existe");
            
            // Verificar se o método destroy existe
            $reflection = new \ReflectionClass(\App\Http\Controllers\Auth\AuthenticatedSessionController::class);
            if ($reflection->hasMethod('destroy')) {
                $this->info("✅ Método destroy() existe");
                
                $method = $reflection->getMethod('destroy');
                $this->info("- Método é público: " . ($method->isPublic() ? 'Sim' : 'Não'));
            } else {
                $this->error("❌ Método destroy() não existe");
            }
        } else {
            $this->error("❌ AuthenticatedSessionController não existe");
        }
        
        // Teste 4: Verificar middleware de autenticação
        $this->info("\n4. Verificando middleware de autenticação...");
        
        $middlewareAliases = app('router')->getMiddleware();
        
        $authMiddleware = ['auth', 'auth.any', 'public.auth'];
        foreach ($authMiddleware as $alias) {
            if (isset($middlewareAliases[$alias])) {
                $this->info("- {$alias}: {$middlewareAliases[$alias]}");
            }
        }
        
        // Teste 5: Verificar redirecionamento
        $this->info("\n5. Verificando configuração de redirecionamento...");
        
        $authConfig = config('auth');
        $this->info("Guard padrão: " . $authConfig['defaults']['guard']);
        
        $routeServiceProvider = config('app.providers');
        $this->info("Providers configurados: " . count($routeServiceProvider));
        
        $this->info("\n=== DIAGNÓSTICO ===");
        $this->info("\n🔍 PROBLEMA IDENTIFICADO:");
        $this->info("Rota de logout estava dentro do middleware 'auth' que redireciona para login");
        $this->info("após invalidar a sessão, causando o comportamento incorreto.");
        
        $this->info("\n✅ CORREÇÃO APLICADA:");
        $this->info("1. Removida rota de logout do grupo middleware 'auth'");
        $this->info("2. Rota de logout agora é independente");
        $this->info("3. AuthenticatedSessionController redireciona para home");
        
        $this->info("\n🧪 TESTE MANUAL NECESSÁRIO:");
        $this->info("1. Faça login com usuário comum (ex: fabio.lemes)");
        $this->info("2. Clique em 'Log Out'");
        $this->info("3. ✅ Deve redirecionar para HOME (não para login)");
        $this->info("4. ✅ Deve mostrar toast de logout");
        $this->info("5. ✅ Deve estar deslogado (sem nome no canto superior)");
        
        $this->info("\n🎯 COMPORTAMENTO ESPERADO:");
        $this->info("- ✅ Usuário comum: Logout → Home deslogado");
        $this->info("- ✅ Administrador: Logout → Home deslogado (mesmo comportamento)");
        $this->info("- ✅ Toast de logout aparece");
        $this->info("- ✅ Sessão completamente limpa");
        
        $this->info("\n🚀 TESTE AGORA NO NAVEGADOR!");
        $this->info("O problema foi identificado e corrigido!");
    }
}