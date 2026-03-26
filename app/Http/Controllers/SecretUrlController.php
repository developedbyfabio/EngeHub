<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Device;
use App\Models\NetworkMap;
use App\Models\SystemUser;
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

        if (! $systemUser) {
            abort(404, 'Setor não encontrado');
        }

        $cards = $systemUser->cards()
            ->with(['category', 'dataCenter', 'tab'])
            ->orderBy('name', 'asc')
            ->get();

        $allCategories = $cards->pluck('category')->filter()->unique('id')->sortBy('name');
        $allDataCenters = $cards->pluck('dataCenter')->filter()->unique('id')->sortBy('name');

        $activeNetworkMap = NetworkMap::active()->first();
        $mapSvgContent = $activeNetworkMap && $activeNetworkMap->fileExists() ? $activeNetworkMap->getSvgContent() : null;
        $deviceLabels = [];
        if ($activeNetworkMap) {
            $activeNetworkMap->load(['devices']);
            $deviceLabels = $activeNetworkMap->devices
                ->where('type', 'SEAT')
                ->mapWithKeys(function (Device $d) {
                    $name = $d->metadata['collaborator_name'] ?? null;

                    return [$d->full_code => $name];
                })
                ->toArray();
        }

        Log::info('SecretUrlController::index - Cards carregados', [
            'system_user_id' => $systemUser->id,
            'system_user_name' => $systemUser->name,
            'total_cards' => $cards->count(),
        ]);

        return view('secret-url.home', compact('cards', 'systemUser', 'allCategories', 'allDataCenters', 'activeNetworkMap', 'mapSvgContent', 'deviceLabels'));
    }

    /**
     * Exibe os logins de um card específico
     */
    public function logins(Request $request, Card $card)
    {
        $systemUser = $request->secret_system_user;

        if (! $systemUser) {
            abort(404, 'Setor não encontrado');
        }

        if (! $systemUser->canViewSystem($card->id)) {
            Log::warning('SecretUrlController::logins - Acesso negado', [
                'system_user_id' => $systemUser->id,
                'card_id' => $card->id,
                'card_name' => $card->name,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para acessar os logins deste sistema.',
                ], 403);
            }

            abort(403, 'Você não tem permissão para acessar os logins deste sistema.');
        }

        $systemLogins = $card->systemLogins()->orderBy('title')->get();
        $systemLogins = $systemLogins->filter(function ($login) use ($systemUser) {
            return $login->canUserView($systemUser->id);
        });

        Log::info('SecretUrlController::logins - Logins carregados', [
            'system_user_id' => $systemUser->id,
            'card_id' => $card->id,
            'card_name' => $card->name,
            'total_logins' => $systemLogins->count(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('secret-url.logins', compact('card', 'systemLogins', 'systemUser'))->render(),
            ]);
        }

        return view('secret-url.logins', compact('card', 'systemLogins', 'systemUser'));
    }
}
