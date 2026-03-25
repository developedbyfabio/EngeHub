<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se há uma sessão válida
        if (!$request->session()->isStarted()) {
            \Log::warning('ValidateSession - Sessão não iniciada', [
                'url' => $request->url(),
                'ip' => $request->ip()
            ]);
            
            // Forçar logout se não há sessão válida
            Auth::guard('web')->logout();
            Auth::guard('system')->logout();
            
            return redirect()->route('login')->with('error', 'Sessão inválida. Faça login novamente.');
        }

        // Verificação simplificada - apenas verificar se o usuário está autenticado
        if (Auth::guard('web')->check() || Auth::guard('system')->check()) {
            // Usuário autenticado, permitir acesso
            return $next($request);
        }

        return $next($request);
    }
}