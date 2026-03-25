@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Ícone de Acesso Negado -->
            <div class="mx-auto h-32 w-32 text-red-500 mb-8">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01"/>
                </svg>
            </div>
            
            <!-- Título -->
            <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Acesso Negado</h2>
            
            <!-- Mensagem -->
            <p class="text-lg text-gray-600 mb-8">
                Você não tem permissões para acessar esta área administrativa.
            </p>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Área Restrita:</strong> Esta página é reservada apenas para administradores do sistema.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Botões de Ação -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Voltar ao Início
                </a>
                
                @if(auth()->guard('system')->check())
                <div class="text-sm text-gray-500">
                    Logado como: <strong>{{ auth()->guard('system')->user()->name }}</strong> (Usuário do Sistema)
                </div>
                @elseif(auth()->guard('web')->check())
                <div class="text-sm text-gray-500">
                    Logado como: <strong>{{ auth()->guard('web')->user()->name }}</strong> (Administrador)
                </div>
                @endif
            </div>
            
            <!-- Informações Técnicas -->
            <div class="mt-8 text-xs text-gray-400">
                <p>Erro HTTP 403 - Forbidden</p>
                <p>Se você acredita que deveria ter acesso a esta área, entre em contato com o administrador do sistema.</p>
            </div>
        </div>
    </div>
</div>
@endsection
