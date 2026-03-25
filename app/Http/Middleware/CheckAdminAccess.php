<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log para debug
        \Log::info('CheckAdminAccess middleware - Verificando acesso administrativo', [
            'url' => $request->url(),
            'method' => $request->method(),
            'web_auth' => Auth::guard('web')->check(),
            'system_auth' => Auth::guard('system')->check(),
            'web_user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
            'system_user_id' => Auth::guard('system')->check() ? Auth::guard('system')->id() : null,
            'ip' => $request->ip()
        ]);
        
        // Verificar se é um usuário administrativo (guard 'web')
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            
            // Verificar se o usuário tem permissões administrativas (acesso total)
            if ($user && $user->hasFullAccess()) {
                \Log::info('CheckAdminAccess - Acesso administrativo concedido', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'url' => $request->url()
                ]);
                return $next($request);
            }
        }
        
        // Se chegou até aqui, é usuário sem permissões administrativas
        \Log::warning('CheckAdminAccess - Acesso negado', [
            'url' => $request->url(),
            'web_auth' => Auth::guard('web')->check(),
            'system_auth' => Auth::guard('system')->check(),
            'web_user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
            'system_user_id' => Auth::guard('system')->check() ? Auth::guard('system')->id() : null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        // Se for requisição AJAX, retornar JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado. Você não tem permissões para acessar esta área.',
                'error' => 'Forbidden'
            ], 403);
        }
        
        // Retornar erro 403 Forbidden
        abort(403, 'Acesso negado. Você não tem permissões para acessar esta área administrativa.');
    }
}