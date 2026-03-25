<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NetworkMap;
use App\Models\Seat;
use App\Models\SeatNetworkPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NetworkMapController extends Controller
{
    public function index()
    {
        $maps = NetworkMap::withCount('seats')->latest()->paginate(10);
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
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $file->move(public_path('media'), $fileName);

        $map = NetworkMap::create([
            'name' => $validated['name'],
            'file_name' => $fileName,
            'file_path' => '/media/',
            'is_active' => $request->boolean('is_active', true),
        ]);

        $synced = $map->syncSeatsFromSvg();

        return redirect()->route('admin.network-maps.index')
            ->with('success', 'Mapa criado com sucesso. ' . $synced . ' mesas detectadas na varredura do SVG.');
    }

    public function show(NetworkMap $network_map)
    {
        $network_map->load(['seats.currentAssignment.user', 'seats.networkPoints']);
        $svgContent = $network_map->fileExists() ? $network_map->getSvgContent() : null;
        // Mapa código da mesa => nome do colaborador (para exibir no SVG)
        $seatLabels = $network_map->seats->mapWithKeys(function ($seat) {
            $a = $seat->currentAssignment;
            $name = $a ? ($a->collaborator_name ?: $a->user?->name) : null;
            return [$seat->code => $name ?: null];
        })->toArray();
        return view('admin.network-maps.show', compact('network_map', 'svgContent', 'seatLabels'));
    }

    /**
     * Retorna dados da mesa para o modal de edição (JSON).
     */
    public function getSeat(NetworkMap $network_map, string $code)
    {
        $seat = $network_map->seats()->where('code', $code)->with(['currentAssignment.user', 'networkPoints'])->first();
        if (!$seat) {
            return response()->json(['success' => false, 'message' => 'Mesa não encontrada'], 404);
        }
        $points = $seat->networkPoints->sortBy('code')->values();
        return response()->json([
            'success' => true,
            'seat' => [
                'id' => $seat->id,
                'code' => $seat->code,
                'setor' => $seat->setor,
                'observacoes' => $seat->observacoes,
                'current_assignment' => $seat->currentAssignment ? [
                    'id' => $seat->currentAssignment->id,
                    'collaborator_name' => $seat->currentAssignment->collaborator_name,
                    'computer_name' => $seat->currentAssignment->computer_name,
                ] : null,
                'network_points' => [
                    $points->get(0) ? ['id' => $points[0]->id, 'code' => $points[0]->code, 'ip' => $points[0]->ip, 'mac_address' => $points[0]->mac_address] : ['id' => null, 'code' => $seat->code . '-01', 'ip' => '', 'mac_address' => ''],
                    $points->get(1) ? ['id' => $points[1]->id, 'code' => $points[1]->code, 'ip' => $points[1]->ip, 'mac_address' => $points[1]->mac_address] : ['id' => null, 'code' => $seat->code . '-02', 'ip' => '', 'mac_address' => ''],
                ],
            ],
        ]);
    }

    /**
     * Atualiza os dados da mesa (setor, observações, colaborador, pontos de rede).
     */
    public function updateSeat(Request $request, NetworkMap $network_map, string $code)
    {
        $seat = $network_map->seats()->firstOrCreate(
            ['code' => $code],
            ['setor' => null, 'observacoes' => null]
        );

        $request->validate([
            'setor' => 'nullable|string|max:100',
            'observacoes' => 'nullable|string|max:500',
            'collaborator_name' => 'nullable|string|max:255',
            'computer_name' => 'nullable|string|max:100',
            'point_1_code' => 'nullable|string|max:20',
            'point_1_ip' => 'nullable|string|max:45',
            'point_1_mac' => 'nullable|string|max:50',
            'point_2_code' => 'nullable|string|max:20',
            'point_2_ip' => 'nullable|string|max:45',
            'point_2_mac' => 'nullable|string|max:50',
        ]);

        $seat->update([
            'setor' => $request->input('setor'),
            'observacoes' => $request->input('observacoes'),
        ]);

        $collaboratorName = $request->filled('collaborator_name') ? trim($request->input('collaborator_name')) : null;
        if ($collaboratorName !== null && $collaboratorName !== '') {
            if ($seat->currentAssignment) {
                $seat->currentAssignment->update([
                    'collaborator_name' => $collaboratorName,
                    'computer_name' => $request->input('computer_name'),
                ]);
            } else {
                $seat->assignCollaborator($collaboratorName, $request->input('computer_name'), 'Alteração pelo admin');
            }
        } else {
            if ($seat->currentAssignment) {
                $seat->release('Liberado pelo admin');
            }
        }
        if ($seat->currentAssignment && $request->has('computer_name')) {
            $seat->currentAssignment->update(['computer_name' => $request->input('computer_name')]);
        }

        $points = $seat->networkPoints()->orderBy('code')->get();
        $p1 = $points->get(0);
        $p2 = $points->get(1);
        if ($p1) {
            $p1->update([
                'code' => $request->input('point_1_code', $p1->code),
                'ip' => $request->input('point_1_ip'),
                'mac_address' => $request->input('point_1_mac'),
            ]);
        } else {
            $seat->networkPoints()->create([
                'code' => $request->input('point_1_code', $seat->code . '-01'),
                'ip' => $request->input('point_1_ip'),
                'mac_address' => $request->input('point_1_mac'),
            ]);
        }
        $seat->load('networkPoints');
        $points = $seat->networkPoints->sortBy('code')->values();
        $p2 = $points->get(1);
        if ($p2) {
            $p2->update([
                'code' => $request->input('point_2_code', $p2->code),
                'ip' => $request->input('point_2_ip'),
                'mac_address' => $request->input('point_2_mac'),
            ]);
        } else {
            $seat->networkPoints()->create([
                'code' => $request->input('point_2_code', $seat->code . '-02'),
                'ip' => $request->input('point_2_ip'),
                'mac_address' => $request->input('point_2_mac'),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Mesa atualizada com sucesso.']);
    }

    /**
     * Reexecuta a varredura do SVG e sincroniza mesas (sem substituir o arquivo).
     */
    public function resyncSeats(NetworkMap $network_map)
    {
        $synced = $network_map->syncSeatsFromSvg();
        return back()->with('success', 'Varredura concluída. ' . $synced . ' mesas no mapa.');
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
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $file->move(public_path('media'), $fileName);
            $network_map->file_name = $fileName;
        }

        $network_map->name = $validated['name'];
        $network_map->is_active = $request->boolean('is_active', $network_map->is_active);
        $network_map->save();

        $synced = $network_map->syncSeatsFromSvg();

        return redirect()->route('admin.network-maps.index')
            ->with('success', 'Mapa atualizado com sucesso. ' . $synced . ' mesas na varredura.');
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
        $networkMap->update(['is_active' => !$networkMap->is_active]);
        $status = $networkMap->is_active ? 'ativado' : 'desativado';
        return back()->with('success', "Mapa {$status} com sucesso.");
    }
}
