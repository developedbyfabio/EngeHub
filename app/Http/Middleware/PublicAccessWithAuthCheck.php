<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PublicAccessWithAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Este middleware permite acesso público mas adiciona informações de autenticação
        // para que as views possam decidir o que mostrar baseado no status de login
        
        // Log para debug
        \Log::info('PublicAccessWithAuthCheck middleware', [
            'url' => $request->url(),
            'method' => $request->method(),
            'web_auth' => Auth::guard('web')->check(),
            'system_auth' => Auth::guard('system')->check(),
            'web_user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
            'system_user_id' => Auth::guard('system')->check() ? Auth::guard('system')->id() : null,
            'session_id' => $request->session()->getId()
        ]);
        
        // Sempre permitir acesso - a lógica de permissão fica no controller
        return $next($request);
    }
}