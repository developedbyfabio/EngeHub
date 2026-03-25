<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForceLogoutAfterSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Middleware simplificado - apenas verificar se a sessão está iniciada
        if (!$request->session()->isStarted()) {
            \Log::warning('ForceLogoutAfterSession - Sessão não iniciada');
            return redirect()->route('login')->with('error', 'Sessão inválida. Faça login novamente.');
        }

        // Verificar se o usuário ainda existe no banco de dados (apenas verificação básica)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if (!$user || !$user->exists) {
                \Log::warning('ForceLogoutAfterSession - Usuário web não existe mais');
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                return redirect()->route('login')->with('error', 'Usuário não encontrado. Faça login novamente.');
            }
        }
        
        if (Auth::guard('system')->check()) {
            $user = Auth::guard('system')->user();
            if (!$user || !$user->exists) {
                \Log::warning('ForceLogoutAfterSession - Usuário system não existe mais');
                Auth::guard('system')->logout();
                $request->session()->invalidate();
                return redirect()->route('login')->with('error', 'Usuário não encontrado. Faça login novamente.');
            }
        }

        return $next($request);
    }
}