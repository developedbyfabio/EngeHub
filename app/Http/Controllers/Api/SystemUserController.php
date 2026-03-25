<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\SystemLogin;
use Illuminate\Http\Request;

class SystemUserController extends Controller
{
    /**
     * Buscar logins dos sistemas por card
     */
    public function getLoginsByCard(Card $card)
    {
        try {
            // Verificar se o usuário está autenticado
            $user = null;
            $hasPermission = false;
            
            // Verificar autenticação no guard 'web' (usuários normais)
            if (auth()->guard('web')->check()) {
                $user = auth()->guard('web')->user();
                
                // Verificar se tem permissão para ver senhas
                if ($user->canViewPasswords()) {
                    $hasPermission = true;
                }
            }
            // Verificar autenticação no guard 'system' (usuários de sistema)
            elseif (auth()->guard('system')->check()) {
                $systemUser = auth()->guard('system')->user();
                
                // System users sempre podem ver senhas dos cards que têm acesso
                if ($systemUser->canViewSystem($card->id)) {
                    $hasPermission = true;
                }
            }
            
            // Log detalhado para debug
            \Log::info('API getLoginsByCard - Debug info', [
                'card_id' => $card->id,
                'card_name' => $card->name,
                'web_auth' => auth()->guard('web')->check(),
                'system_auth' => auth()->guard('system')->check(),
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->name : null,
                'has_permission' => $hasPermission,
                'user_permissions' => $user ? $user->userPermissions->pluck('permission_type')->toArray() : null,
                'user_permissions_active' => $user ? $user->userPermissions->where('is_active', true)->pluck('permission_type')->toArray() : null,
                'request_headers' => $request->headers->all(),
                'session_id' => session()->getId()
            ]);
            
            if (!$hasPermission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para acessar os logins deste sistema. Verifique suas permissões com o administrador.'
                ], 403);
            }

            // Buscar logins ativos do sistema
            $systemLogins = SystemLogin::where('card_id', $card->id)
                ->where('is_active', true)
                ->get(['id', 'title', 'username', 'password', 'notes']);

            return response()->json([
                'success' => true,
                'systemLogins' => $systemLogins,
                'cardName' => $card->name
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar logins do sistema', [
                'card_id' => $card->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar logins do sistema. Tente novamente.'
            ], 500);
        }
    }
}
