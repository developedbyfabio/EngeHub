<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\NetworkMapController;
use App\Models\Device;
use App\Models\NetworkMap;
use App\Support\NavPermission;
use Illuminate\Http\Request;

class FiliaisController extends Controller
{
    public function index(Request $request)
    {
        $maps = NetworkMap::active()->orderBy('name')->get();

        if ($maps->isEmpty()) {
            return view('filiais.empty', ['maps' => $maps]);
        }

        $network_map = $maps->firstWhere('id', (int) $request->query('map')) ?? $maps->first();

        $network_map->load(['devices']);

        $mapActiveFloor = (int) $request->query('floor', 1);
        if ($mapActiveFloor !== 2) {
            $mapActiveFloor = 1;
        }
        if ($mapActiveFloor === 2 && ! $network_map->has_two_floors) {
            $mapActiveFloor = 1;
        }

        $svgContent = $network_map->getSvgContentForFloor($mapActiveFloor);

        $deviceLabels = $network_map->devices
            ->where('type', 'SEAT')
            ->mapWithKeys(function (Device $d) {
                $name = $d->metadata['collaborator_name'] ?? null;

                return [$d->full_code => $name];
            })
            ->toArray();

        $deviceCountsByType = $network_map->devices->countBy('type')->all();

        $filiaisMode = true;
        $canEditDevices = auth()->guard('web')->check()
            && auth()->guard('web')->user()->canAccessNav(NavPermission::ADMIN_NETWORK_MAPS);

        return view('admin.network-maps.show', compact(
            'network_map',
            'svgContent',
            'mapActiveFloor',
            'deviceLabels',
            'deviceCountsByType',
            'maps',
            'filiaisMode',
            'canEditDevices'
        ));
    }

    /**
     * SVG de um andar em JSON (mapas ativos — mesma permissão da página Filiais).
     */
    public function mapSvgContent(Request $request, NetworkMap $network_map)
    {
        if (! $network_map->is_active) {
            abort(404);
        }

        return app(NetworkMapController::class)->svgFloorJson($request, $network_map);
    }

    /**
     * JSON do dispositivo (Filiais — usuário autenticado).
     */
    public function getDevice(NetworkMap $network_map, string $type, string $code)
    {
        if (! $network_map->is_active) {
            abort(404);
        }

        return app(NetworkMapController::class)->getDevice($network_map, $type, $code);
    }
}
