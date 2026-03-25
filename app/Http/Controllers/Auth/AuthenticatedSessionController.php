<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        \Log::info('=== DEBUG: Login iniciado ===', [
            'username' => $request->username,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId()
        ]);

        $request->authenticate();

        $request->session()->regenerate();

        // Determinar o nome do usuário logado
        $userName = '';
        $guardType = '';
        if (Auth::guard('system')->check()) {
            $userName = Auth::guard('system')->user()->name;
            $guardType = 'system';
        } elseif (Auth::guard('web')->check()) {
            $userName = Auth::guard('web')->user()->name;
            $guardType = 'web';
        }

        \Log::info('=== DEBUG: Login concluído ===', [
            'user_name' => $userName,
            'guard_type' => $guardType,
            'web_auth' => Auth::guard('web')->check(),
            'system_auth' => Auth::guard('system')->check(),
            'session_id' => $request->session()->getId()
        ]);

        // Se for uma requisição AJAX, retornar JSON
        if ($request->expectsJson() || $request->ajax()) {
            // Definir mensagem de sucesso na sessão para o toast aparecer após reload
            $request->session()->flash('success', "Logado com sucesso como {$userName}!");
            
            return response()->json([
                'success' => true,
                'message' => "Logado com sucesso como {$userName}!",
                'user' => [
                    'name' => $userName,
                    'guard' => $guardType
                ]
            ]);
        }

        return redirect()->intended(RouteServiceProvider::HOME)
                        ->with('success', "Logado com sucesso como {$userName}!");
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        \Log::info('=== DEBUG: Logout iniciado ===', [
            'web_auth' => Auth::guard('web')->check(),
            'system_auth' => Auth::guard('system')->check(),
            'web_user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
            'system_user_id' => Auth::guard('system')->check() ? Auth::guard('system')->id() : null,
            'session_id' => $request->session()->getId(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Obter informações do usuário antes do logout
        $userName = '';
        $guardType = '';
        if (Auth::guard('system')->check()) {
            $userName = Auth::guard('system')->user()->name;
            $guardType = 'system';
        } elseif (Auth::guard('web')->check()) {
            $userName = Auth::guard('web')->user()->name;
            $guardType = 'web';
        }

        // Fazer logout de ambos os guards
        Auth::guard('web')->logout();
        Auth::guard('system')->logout();

        // Invalidar a sessão completamente
        $request->session()->invalidate();

        // Regenerar o token CSRF
        $request->session()->regenerateToken();

        \Log::info('=== DEBUG: Logout concluído ===', [
            'user_name' => $userName,
            'guard_type' => $guardType,
            'ip' => $request->ip()
        ]);

        return redirect()->route('home')->with('success', "Logout realizado com sucesso! Até logo, {$userName}!");
    }
} 