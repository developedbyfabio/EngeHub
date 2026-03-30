<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWebUserNavAccess
{
    /**
     * @param  string  ...$args  primeiro argumento: chave NavPermission
     */
    public function handle(Request $request, Closure $next, string $navKey): Response
    {
        if (Auth::guard('system')->check()) {
            return $next($request);
        }

        if (! Auth::guard('web')->check()) {
            return $next($request);
        }

        $user = Auth::guard('web')->user();
        if ($user && ! $user->canAccessNav($navKey)) {
            return redirect()->route('home')->with('error', 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }
}
