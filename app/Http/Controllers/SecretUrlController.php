<?php

namespace App\Http\Controllers;

use App\Models\Tab;
use App\Models\Card;
use App\Models\SystemUser;
use App\Models\NetworkMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecretUrlController extends Controller
{
    /**
     * Exibe a página principal com os cards do setor
     */
    public function index(Request $request)
    {
        // Obter SystemUser do middleware
        $systemUser = $request->secret_system_user;
        
        if (!$systemUser) {
            abort(404, 'Setor não encontrado');
        }
        
        // Carregar todos os cards permitidos para este SystemUser (sem agrupar por abas)
        // Ordenar alfabeticamente por padrão
        $cards = $systemUser->cards()
            ->with(['category', 'dataCenter', 'tab'])
            ->orderBy('name', 'asc')
            ->get();
        
        // Coletar todas as categorias e data centers dos cards para os filtros
        $allCategories = $cards->pluck('category')->filter()->unique('id')->sortBy('name');
        $allDataCenters = $cards->pluck('dataCenter')->filter()->unique('id')->sortBy('name');
        
        // Mapa de rede ativo (para a aba Mapa de Rede)
        $activeNetworkMap = NetworkMap::active()->first();
        $mapSvgContent = $activeNetworkMap && $activeNetworkMap->fileExists() ? $activeNetworkMap->getSvgContent() : null;
        $seatLabels = [];
        if ($activeNetworkMap) {
            $activeNetworkMap->load(['seats.currentAssignment.user', 'seats.currentAssignment']);
            $seatLabels = $activeNetworkMap->seats->mapWithKeys(function ($seat) {
                $a = $seat->currentAssignment;
                $name = $a ? ($a->collaborator_name ?: $a->user?->name) : null;
                return [$seat->code => $name ?: null];
            })->toArray();
        }
        
        Log::info('SecretUrlController::index - Cards carregados', [
            'system_user_id' => $systemUser->id,
            'system_user_name' => $systemUser->name,
            'total_cards' => $cards->count()
        ]);
        
        return view('secret-url.home', compact('cards', 'systemUser', 'allCategories', 'allDataCenters', 'activeNetworkMap', 'mapSvgContent', 'seatLabels'));
    }

    /**
     * Exibe os logins de um card específico
     */
    public function logins(Request $request, Card $card)
    {
        // Obter SystemUser do middleware
        $systemUser = $request->secret_system_user;
        
        if (!$systemUser) {
            abort(404, 'Setor não encontrado');
        }
        
        // Verificar se o SystemUser tem acesso a este card
        if (!$systemUser->canViewSystem($card->id)) {
            Log::warning('SecretUrlController::logins - Acesso negado', [
                'system_user_id' => $systemUser->id,
                'card_id' => $card->id,
                'card_name' => $card->name
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para acessar os logins deste sistema.'
                ], 403);
            }
            
            abort(403, 'Você não tem permissão para acessar os logins deste sistema.');
        }
        
        // Buscar logins do card
        $systemLogins = $card->systemLogins()->orderBy('title')->get();
        
        // Aplicar filtro granular - apenas logins permitidos para este SystemUser
        $systemLogins = $systemLogins->filter(function ($login) use ($systemUser) {
            return $login->canUserView($systemUser->id);
        });
        
        Log::info('SecretUrlController::logins - Logins carregados', [
            'system_user_id' => $systemUser->id,
            'card_id' => $card->id,
            'card_name' => $card->name,
            'total_logins' => $systemLogins->count()
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('secret-url.logins', compact('card', 'systemLogins', 'systemUser'))->render()
            ]);
        }
        
        return view('secret-url.logins', compact('card', 'systemLogins', 'systemUser'));
    }
}
