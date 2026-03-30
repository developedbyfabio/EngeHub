<?php

namespace App\Http\Middleware;

use App\Support\NavPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAccess
{
    /**
     * Acesso à área admin: usuário web com permissão específica da rota (grupo) ou acesso total (legacy).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('web')->check()) {
            return $this->deny($request);
        }

        $user = Auth::guard('web')->user();

        if ($user->hasFullAccess()) {
            return $next($request);
        }

        if ($user->userGroup?->full_access) {
            return $next($request);
        }

        if (! $user->canAccessAnyAdminNav()) {
            return $this->deny($request);
        }

        $routeName = $request->route()?->getName();
        $key = NavPermission::adminRouteToNavKey($routeName);

        if ($key === null) {
            return $this->deny($request);
        }

        if (! $user->canAccessNav($key)) {
            return $this->deny($request);
        }

        return $next($request);
    }

    private function deny(Request $request): Response
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado. Você não tem permissões para acessar esta área.',
                'error' => 'Forbidden',
            ], 403);
        }

        abort(403, 'Acesso negado. Você não tem permissões para acessar esta área administrativa.');
    }
}