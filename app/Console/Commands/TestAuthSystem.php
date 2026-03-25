<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SystemUser;
use Illuminate\Support\Facades\Hash;

class TestAuthSystem extends Command
{
    protected $signature = 'auth:test';
    protected $description = 'Testa o sistema de autenticação do EngeHub';

    public function handle()
    {
        $this->info('=== TESTE DO SISTEMA DE AUTENTICAÇÃO ===');
        
        // Teste 1: Verificar usuários existentes
        $this->info("\n1. Verificando usuários existentes...");
        
        $adminUsers = User::count();
        $systemUsers = SystemUser::count();
        
        $this->info("Usuários administrativos: {$adminUsers}");
        $this->info("Usuários do sistema: {$systemUsers}");
        
        if ($adminUsers > 0) {
            $this->info("\nUsuários administrativos encontrados:");
            foreach (User::all() as $user) {
                $this->info("- ID: {$user->id}, Nome: {$user->name}, Email: {$user->email}");
            }
        }
        
        if ($systemUsers > 0) {
            $this->info("\nUsuários do sistema encontrados:");
            foreach (SystemUser::all() as $user) {
                $this->info("- ID: {$user->id}, Nome: {$user->name}, Username: {$user->username}");
            }
        }
        
        // Teste 2: Verificar senhas
        $this->info("\n2. Verificando senhas...");
        
        $testPassword = '123456';
        $hashedPassword = Hash::make($testPassword);
        
        $this->info("Senha de teste: {$testPassword}");
        $this->info("Hash gerado: {$hashedPassword}");
        
        // Teste 3: Verificar configuração de autenticação
        $this->info("\n3. Verificando configuração de autenticação...");
        
        $guards = config('auth.guards');
        $providers = config('auth.providers');
        
        $this->info("Guards configurados:");
        foreach ($guards as $name => $config) {
            $this->info("- {$name}: {$config['driver']} -> {$config['provider']}");
        }
        
        $this->info("\nProviders configurados:");
        foreach ($providers as $name => $config) {
            $this->info("- {$name}: {$config['driver']} -> {$config['model']}");
        }
        
        // Teste 4: Verificar middleware
        $this->info("\n4. Verificando middleware...");
        
        $middlewareAliases = app('router')->getMiddleware();
        $this->info("Middleware registrados:");
        foreach ($middlewareAliases as $alias => $class) {
            if (strpos($alias, 'auth') !== false) {
                $this->info("- {$alias}: {$class}");
            }
        }
        
        // Teste 5: Verificar rotas de autenticação
        $this->info("\n5. Verificando rotas de autenticação...");
        
        $routes = app('router')->getRoutes();
        $authRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            if (strpos($uri, 'login') !== false || strpos($uri, 'logout') !== false) {
                $methods = implode('|', $route->methods());
                $authRoutes[] = "{$methods} /{$uri}";
            }
        }
        
        if (!empty($authRoutes)) {
            $this->info("Rotas de autenticação encontradas:");
            foreach ($authRoutes as $route) {
                $this->info("- {$route}");
            }
        }
        
        $this->info("\n=== TESTE CONCLUÍDO ===");
        $this->info("Para testar o login/logout:");
        $this->info("1. Acesse: http://192.168.11.201/login");
        $this->info("2. Faça login com um usuário");
        $this->info("3. Faça logout");
        $this->info("4. Verifique se não consegue acessar áreas protegidas");
        $this->info("5. Verifique os logs em: storage/logs/laravel.log");
    }
}