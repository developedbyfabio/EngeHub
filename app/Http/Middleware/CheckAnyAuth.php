<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAnyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log para debug
        \Log::info('CheckAnyAuth middleware - Verificando autenticação', [
            'url' => $request->url(),
            'method' => $request->method(),
            'web_auth' => Auth::guard('web')->check(),
            'system_auth' => Auth::guard('system')->check(),
            'web_user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
            'system_user_id' => Auth::guard('system')->check() ? Auth::guard('system')->id() : null,
            'session_id' => $request->session()->getId(),
            'session_started' => $request->session()->isStarted()
        ]);
        
        // Verificar se a sessão está iniciada
        if (!$request->session()->isStarted()) {
            \Log::warning('CheckAnyAuth - Sessão não iniciada, redirecionando para login');
            return redirect()->route('login')->with('error', 'Sessão inválida. Faça login novamente.');
        }
        
        // Verificar se o usuário está autenticado em qualquer um dos guards
        if (Auth::guard('web')->check() || Auth::guard('system')->check()) {
            return $next($request);
        }

        // Se não estiver autenticado, redirecionar para login
        \Log::info('CheckAnyAuth - Usuário não autenticado, redirecionando para login');
        return redirect()->route('login')->with('error', 'Você precisa fazer login para acessar esta página.');
    }
}