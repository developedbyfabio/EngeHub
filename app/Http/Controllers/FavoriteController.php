<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\UserFavorite;
use App\Models\Card;

class FavoriteController extends Controller
{
    /**
     * Alternar favorito (adicionar/remover)
     */
    public function toggle(Request $request, $cardId): JsonResponse
    {
        try {
            // Validar se o card existe
            $card = Card::findOrFail($cardId);
            
            // Identificar o usuário logado
            $userId = null;
            $systemUserId = null;
            
            if (Auth::guard('web')->check()) {
                $userId = Auth::guard('web')->id();
            } elseif (Auth::guard('system')->check()) {
                $systemUserId = Auth::guard('system')->id();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }
            
            // Verificar se já é favorito
            $isFavorite = UserFavorite::isFavorite($cardId, $userId, $systemUserId);
            
            if ($isFavorite) {
                // Remover dos favoritos
                UserFavorite::removeFromFavorites($cardId, $userId, $systemUserId);
                $action = 'removed';
                $message = 'Card removido dos favoritos';
            } else {
                // Adicionar aos favoritos
                UserFavorite::addToFavorites($cardId, $userId, $systemUserId);
                $action = 'added';
                $message = 'Card adicionado aos favoritos';
            }
            
            // Log da ação
            \Log::info('Favorite toggled', [
                'card_id' => $cardId,
                'card_name' => $card->name,
                'user_id' => $userId,
                'system_user_id' => $systemUserId,
                'action' => $action,
                'ip' => $request->ip()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action,
                'is_favorite' => !$isFavorite
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error toggling favorite', [
                'card_id' => $cardId,
                'error' => $e->getMessage(),
                'user_id' => $userId ?? null,
                'system_user_id' => $systemUserId ?? null
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
    
    /**
     * Listar favoritos do usuário atual
     */
    public function index(): JsonResponse
    {
        try {
            $favorites = [];
            
            if (Auth::guard('web')->check()) {
                $user = Auth::guard('web')->user();
                $favorites = $user->favoriteCards()->with(['tab', 'category'])->get();
            } elseif (Auth::guard('system')->check()) {
                $systemUser = Auth::guard('system')->user();
                $favorites = $systemUser->favoriteCards()->with(['tab', 'category'])->get();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }
            
            return response()->json([
                'success' => true,
                'favorites' => $favorites
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching favorites', [
                'error' => $e->getMessage(),
                'user_id' => Auth::guard('web')->check() ? Auth::guard('web')->id() : null,
                'system_user_id' => Auth::guard('system')->check() ? Auth::guard('system')->id() : null
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
    
    /**
     * Verificar se um card é favorito
     */
    public function check($cardId): JsonResponse
    {
        try {
            $userId = null;
            $systemUserId = null;
            
            if (Auth::guard('web')->check()) {
                $userId = Auth::guard('web')->id();
            } elseif (Auth::guard('system')->check()) {
                $systemUserId = Auth::guard('system')->id();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não autenticado'
                ], 401);
            }
            
            $isFavorite = UserFavorite::isFavorite($cardId, $userId, $systemUserId);
            
            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}
