<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\NetworkMap;

class DeviceApiController extends Controller
{
    /**
     * Dispositivo no mapa de rede ativo (URL secreta / integrações).
     */
    public function show(string $type, string $code)
    {
        $type = strtoupper($type);
        if (! in_array($type, Device::TYPES, true)) {
            return response()->json(['success' => false, 'message' => 'Tipo inválido'], 400);
        }

        $map = NetworkMap::active()->first();
        if (! $map) {
            return response()->json(['success' => false, 'message' => 'Nenhum mapa ativo'], 404);
        }

        $device = $map->devices()->where('type', $type)->where('code', $code)->first();
        if (! $device) {
            return response()->json(['success' => false, 'message' => 'Dispositivo não encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'device' => $device->toApiArray(),
        ]);
    }

    /**
     * Lista full_code de assentos (SEAT) ocupados no mapa ativo — colaborador preenchido.
     */
    public function occupiedSeats()
    {
        $map = NetworkMap::active()->first();
        if (! $map) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $codes = $map->devices()
            ->where('type', 'SEAT')
            ->get()
            ->filter(fn (Device $d) => ! empty($d->metadata['collaborator_name']))
            ->pluck('full_code')
            ->values();

        return response()->json(['success' => true, 'data' => $codes]);
    }
}
