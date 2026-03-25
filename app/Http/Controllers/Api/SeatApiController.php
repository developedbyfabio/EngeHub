<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NetworkMap;
use Illuminate\Http\Request;

class SeatApiController extends Controller
{
    /**
     * Retorna dados do assento para exibição no modal (página link secreto).
     * Formato esperado pelo front: data.success, data.data { disponivel, colaborador: { nome, email, computador }, pontos_rede, historico }
     */
    public function show(string $code)
    {
        $map = NetworkMap::active()->first();
        if (!$map) {
            return response()->json(['success' => false, 'message' => 'Nenhum mapa ativo'], 404);
        }
        $seat = $map->seats()->where('code', $code)->with(['currentAssignment.user', 'networkPoints', 'assignments' => function ($q) {
            $q->with('user')->orderBy('started_at', 'desc')->limit(5);
        }])->first();
        if (!$seat) {
            return response()->json(['success' => false, 'message' => 'Assento não encontrado'], 404);
        }
        $a = $seat->currentAssignment;
        $disponivel = !$a;
        $colaborador = null;
        if ($a) {
            $nome = $a->collaborator_name ?: $a->user?->name;
            $colaborador = [
                'nome' => $nome,
                'email' => $a->user?->email,
                'computador' => $a->computer_name,
            ];
        }
        $pontos_rede = $seat->networkPoints->sortBy('code')->map(function ($p) {
            return ['code' => $p->code, 'ip' => $p->ip, 'mac_address' => $p->mac_address];
        })->values()->toArray();
        $historico = $seat->assignments->map(function ($h) {
            $nome = $h->collaborator_name ?: $h->user?->name;
            $periodo = $h->started_at->format('d/m/Y') . ' - ' . ($h->ended_at ? $h->ended_at->format('d/m/Y') : 'Atual');
            return ['colaborador' => $nome, 'periodo' => $periodo];
        })->toArray();
        return response()->json([
            'success' => true,
            'data' => [
                'codigo' => $seat->code,
                'setor' => $seat->setor,
                'observacoes' => $seat->observacoes,
                'disponivel' => $disponivel,
                'colaborador' => $colaborador,
                'pontos_rede' => $pontos_rede,
                'historico' => $historico,
            ],
        ]);
    }

    public function occupied()
    {
        $map = NetworkMap::active()->first();
        if (!$map) {
            return response()->json(['success' => true, 'data' => []]);
        }
        $codes = $map->seats()->whereHas('currentAssignment')->pluck('code');
        return response()->json(['success' => true, 'data' => $codes]);
    }
}
