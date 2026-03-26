<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\NetworkMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class NetworkMapController extends Controller
{
    public function index()
    {
        $maps = NetworkMap::withCount('devices')->latest()->paginate(10);

        return view('admin.network-maps.index', compact('maps'));
    }

    public function create()
    {
        return view('admin.network-maps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:svg|max:10240',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'O nome do mapa é obrigatório.',
            'file.required' => 'Selecione um arquivo SVG.',
            'file.mimes' => 'O arquivo deve ser do tipo SVG.',
        ]);

        $file = $request->file('file');
        $fileName = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move(public_path('media'), $fileName);

        $map = NetworkMap::create([
            'name' => $validated['name'],
            'file_name' => $fileName,
            'file_path' => '/media/',
            'is_active' => $request->boolean('is_active', true),
        ]);

        $synced = $map->syncDevicesFromSvg();

        return redirect()->route('admin.network-maps.index')
            ->with('success', 'Mapa criado com sucesso. '.$synced.' dispositivo(s) detectado(s) no SVG.');
    }

    public function show(NetworkMap $network_map)
    {
        $network_map->load(['devices']);
        $svgContent = $network_map->fileExists() ? $network_map->getSvgContent() : null;

        $deviceLabels = $network_map->devices
            ->where('type', 'SEAT')
            ->mapWithKeys(function (Device $d) {
                $name = $d->metadata['collaborator_name'] ?? null;

                return [$d->full_code => $name];
            })
            ->toArray();

        return view('admin.network-maps.show', [
            'network_map' => $network_map,
            'svgContent' => $svgContent,
            'deviceLabels' => $deviceLabels,
            'canEditDevices' => true,
        ]);
    }

    public function getDevice(NetworkMap $network_map, string $type, string $code)
    {
        $type = strtoupper($type);
        if (! in_array($type, Device::TYPES, true)) {
            return response()->json(['success' => false, 'message' => 'Tipo inválido'], 400);
        }

        $device = $network_map->devices()
            ->where('type', $type)
            ->where('code', $code)
            ->first();

        if (! $device) {
            return response()->json(['success' => false, 'message' => 'Dispositivo não encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'device' => $device->toApiArray(),
        ]);
    }

    public function updateDevice(Request $request, NetworkMap $network_map, string $type, string $code)
    {
        $type = strtoupper($type);
        if (! in_array($type, Device::TYPES, true)) {
            return response()->json(['success' => false, 'message' => 'Tipo inválido'], 400);
        }

        $device = $network_map->devices()
            ->where('type', $type)
            ->where('code', $code)
            ->first();

        if (! $device) {
            return response()->json(['success' => false, 'message' => 'Dispositivo não encontrado'], 404);
        }

        $rules = [
            'setor' => 'nullable|string|max:100',
            'observacoes' => 'nullable|string|max:500',
            'metadata' => 'nullable|array',
        ];

        if ($type === 'SEAT') {
            $rules['metadata.collaborator_name'] = 'nullable|string|max:255';
            $rules['metadata.computer_name'] = 'nullable|string|max:100';
            $rules['metadata.computer_kind'] = 'nullable|in:desktop,notebook';
            $rules['metadata.computer_ip'] = 'nullable|string|max:45';
            $rules['workstation_photo'] = 'nullable|image|max:5120';
            $rules['remove_workstation_photo'] = 'nullable|boolean';
        } elseif ($type === 'PRINTER') {
            $rules['metadata.ip'] = 'nullable|string|max:45';
            $rules['metadata.model'] = 'nullable|string|max:150';
        } elseif ($type === 'TV') {
            $rules['metadata.location'] = 'nullable|string|max:255';
        } elseif ($type === 'SCAN') {
            $rules['metadata.sector'] = 'nullable|string|max:150';
        } elseif ($type === 'PHONE') {
            $rules['metadata.extension'] = 'nullable|string|max:50';
        } elseif ($type === 'AP') {
            $rules['metadata.ssid'] = 'nullable|string|max:100';
            $rules['metadata.location'] = 'nullable|string|max:255';
        } elseif ($type === 'OUTLET') {
            $rules['metadata.outlet_type'] = 'nullable|in:network,phone';
        }

        if (in_array($type, ['PRINTER', 'TV', 'SCAN', 'PHONE', 'AP', 'OUTLET'], true)) {
            $rules['device_photo'] = 'nullable|image|max:5120';
            $rules['remove_device_photo'] = 'nullable|boolean';
        }

        $validated = $request->validate($rules);

        $metaIn = $validated['metadata'] ?? [];
        $merged = array_merge($device->metadata ?? [], is_array($metaIn) ? $metaIn : []);

        if ($type === 'SEAT') {
            foreach ([
                'point_1_code', 'point_1_ip', 'point_1_mac',
                'point_2_code', 'point_2_ip', 'point_2_mac',
            ] as $legacyKey) {
                unset($merged[$legacyKey]);
            }
            if ($request->boolean('remove_workstation_photo')) {
                $old = $merged['workstation_photo'] ?? null;
                if (is_string($old) && $old !== '' && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $merged['workstation_photo'] = null;
            }
            if ($request->hasFile('workstation_photo')) {
                $old = $merged['workstation_photo'] ?? null;
                if (is_string($old) && $old !== '' && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $merged['workstation_photo'] = $request->file('workstation_photo')->store('workstations', 'public');
            }
        }

        if (in_array($type, ['PRINTER', 'TV', 'SCAN', 'PHONE', 'AP', 'OUTLET'], true)) {
            if ($request->boolean('remove_device_photo')) {
                $old = $merged['device_photo'] ?? null;
                if (is_string($old) && $old !== '' && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $merged['device_photo'] = null;
            }
            if ($request->hasFile('device_photo')) {
                $old = $merged['device_photo'] ?? null;
                if (is_string($old) && $old !== '' && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $merged['device_photo'] = $request->file('device_photo')->store('map-device-photos', 'public');
            }
        }

        $device->update([
            'setor' => $request->has('setor') ? $request->input('setor') : $device->setor,
            'observacoes' => $request->has('observacoes') ? $request->input('observacoes') : $device->observacoes,
            'metadata' => $merged,
        ]);

        $device->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo atualizado com sucesso.',
            'device' => $device->toApiArray(),
        ]);
    }

    public function resyncDevices(NetworkMap $network_map)
    {
        $synced = $network_map->syncDevicesFromSvg();

        return back()->with('success', 'Varredura concluída. '.$synced.' dispositivo(s) no mapa.');
    }

    public function edit(NetworkMap $network_map)
    {
        return view('admin.network-maps.edit', compact('network_map'));
    }

    public function update(Request $request, NetworkMap $network_map)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:svg|max:10240',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('file')) {
            if ($network_map->fileExists()) {
                @unlink($network_map->full_path);
            }
            $file = $request->file('file');
            $fileName = time().'_'.preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('media'), $fileName);
            $network_map->file_name = $fileName;
        }

        $network_map->name = $validated['name'];
        $network_map->is_active = $request->boolean('is_active', $network_map->is_active);
        $network_map->save();

        $synced = $network_map->syncDevicesFromSvg();

        return redirect()->route('admin.network-maps.index')
            ->with('success', 'Mapa atualizado com sucesso. '.$synced.' dispositivo(s) na varredura.');
    }

    public function destroy(NetworkMap $network_map)
    {
        if ($network_map->fileExists()) {
            @unlink($network_map->full_path);
        }
        $network_map->delete();

        return redirect()->route('admin.network-maps.index')
            ->with('success', 'Mapa excluído com sucesso.');
    }

    public function toggleStatus(NetworkMap $networkMap)
    {
        $networkMap->update(['is_active' => ! $networkMap->is_active]);
        $status = $networkMap->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Mapa {$status} com sucesso.");
    }
}
