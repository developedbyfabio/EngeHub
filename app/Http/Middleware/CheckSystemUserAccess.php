<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver autenticado, redirecionar para login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Se for um SystemUser, permitir acesso
        if ($user instanceof \App\Models\SystemUser) {
            return $next($request);
        }

        // Se for um User admin, verificar permissões
        if ($user instanceof \App\Models\User) {
            // Verificar se tem permissões de admin
            if ($user->hasFullAccess() || $user->canManageSystemUsers()) {
                return $next($request);
            }
        }

        // Se não tiver permissões, redirecionar para home
        return redirect()->route('home')->with('error', 'Você não tem permissão para acessar esta área.');
    }
}
