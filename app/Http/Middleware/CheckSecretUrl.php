<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemUser;
use App\Models\SecretUrlAccessLog;

class CheckSecretUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secretUrl = $request->route('secret_url');
        
        if (!$secretUrl) {
            abort(404, 'URL secreta não fornecida');
        }
        
        // Buscar SystemUser pela URL secreta
        $systemUser = SystemUser::where('secret_url', $secretUrl)
            ->where('secret_url_enabled', true)
            ->first();
        
        if (!$systemUser) {
            \Log::warning('Tentativa de acesso com URL secreta inválida', [
                'secret_url' => $secretUrl,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            abort(404, 'URL secreta inválida ou não encontrada');
        }
        
        // Verificar se a URL está válida (não expirada)
        if (!$systemUser->isSecretUrlValid()) {
            \Log::warning('Tentativa de acesso com URL secreta expirada', [
                'system_user_id' => $systemUser->id,
                'secret_url' => $secretUrl,
                'expires_at' => $systemUser->secret_url_expires_at,
                'ip' => $request->ip()
            ]);
            
            abort(403, 'URL secreta expirada');
        }
        
        // Registrar log de acesso
        try {
            SecretUrlAccessLog::create([
                'system_user_id' => $systemUser->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'accessed_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao registrar log de acesso de URL secreta', [
                'error' => $e->getMessage(),
                'system_user_id' => $systemUser->id
            ]);
        }
        
        // Adicionar systemUser ao request para uso no controller
        $request->merge(['secret_system_user' => $systemUser]);
        
        \Log::info('Acesso por URL secreta autorizado', [
            'system_user_id' => $systemUser->id,
            'system_user_name' => $systemUser->name,
            'ip' => $request->ip(),
            'url' => $request->url()
        ]);
        
        return $next($request);
    }
}
