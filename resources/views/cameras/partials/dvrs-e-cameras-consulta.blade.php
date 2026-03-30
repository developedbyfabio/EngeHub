@props(['dvrs', 'problemaHistoricoPorCamera'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h2 class="text-lg font-semibold mb-2">
            <i class="fas fa-server mr-2 text-slate-600"></i>
            DVRs e Câmeras
        </h2>
        <p class="text-sm text-gray-500 mb-4">Visualização das fotos cadastradas. Para editar ou anexar imagens, use <strong>Gerenciar Câmeras</strong>.</p>
        @if($dvrs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localização</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Câmeras</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $viewerIdxConsulta = 0; @endphp
                        @foreach($dvrs as $dvr)
                            @php
                                $dvrFotoHistArrConsulta = $dvr->fotos->map(fn($f) => [
                                    'data' => $f->created_at->format('d/m/Y H:i'),
                                    'arquivo' => $f->original_filename ?: basename($f->path),
                                    'url' => asset('storage/' . $f->path),
                                    'dvrNome' => $dvr->nome,
                                ])->values()->all();
                            @endphp
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <button type="button" onclick="toggleDvrExpandConsulta({{ $dvr->id }})" class="text-gray-500 hover:text-gray-700 focus:outline-none" title="Expandir/recolher">
                                        <i class="fas fa-chevron-right expand-icon-consulta transition-transform duration-200" id="expand-icon-consulta-{{ $dvr->id }}"></i>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dvr->nome }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dvr->localizacao ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $dvr->cameras->count() }} câmeras</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($dvr->fotos->isNotEmpty())
                                        @php $dvrThumbFoto = $dvr->fotos->sortByDesc(fn($f) => $f->created_at->timestamp)->first(); @endphp
                                        <button type="button" onclick="openDvrFotoViewerConsulta({{ $dvr->id }})" class="cursor-pointer hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 rounded" title="Visualizar fotos dos DVRs">
                                            <img src="{{ asset('storage/' . $dvrThumbFoto->path) }}" alt="" class="h-10 w-auto rounded border border-gray-300 object-cover">
                                        </button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dvr->status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($dvr->status) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" class="btn-historico-dvr-consulta text-indigo-600 hover:text-indigo-900" title="Histórico de fotos do DVR" data-dvr-nome="{{ e($dvr->nome) }}" data-historical="{{ base64_encode(json_encode($dvrFotoHistArrConsulta, JSON_UNESCAPED_UNICODE)) }}"><i class="fas fa-history"></i></button>
                                </td>
                            </tr>
                            <tr data-dvr-cameras-consulta="{{ $dvr->id }}" class="dvr-cameras-row-consulta hidden bg-gray-50">
                                <td colspan="7" class="px-6 py-0">
                                    <div class="border-l-4 border-blue-200 pl-4 py-3">
                                        <span class="text-sm font-medium text-gray-700">Câmeras do DVR {{ $dvr->nome }}</span>
                                        <div id="cameras-table-wrapper-consulta-{{ $dvr->id }}" class="{{ $dvr->cameras->count() > 0 ? 'mt-2' : 'mt-2 hidden' }}">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full text-sm">
                                                    <thead>
                                                        <tr class="border-b border-gray-200">
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Nome</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Canal</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Foto</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Status</th>
                                                            <th class="text-right py-2 font-medium text-gray-600">Ações</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($dvr->cameras as $camera)
                                                            <tr class="border-b border-gray-100 hover:bg-white">
                                                                <td class="py-2 pr-4 font-medium text-gray-900">{{ $camera->nome }}</td>
                                                                <td class="py-2 pr-4 text-gray-600">{{ $camera->canal ?? '-' }}</td>
                                                                <td class="py-2 pr-4">
                                                                    @if($camera->foto)
                                                                        <button type="button" onclick="openCameraViewerConsulta({{ $viewerIdxConsulta }})" class="cursor-pointer hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 rounded" title="Visualizar">
                                                                            <img src="{{ asset('storage/' . $camera->foto) }}" alt="" class="h-10 w-auto rounded border border-gray-300 object-cover">
                                                                        </button>
                                                                    @else
                                                                        <span class="text-gray-400">-</span>
                                                                    @endif
                                                                </td>
                                                                <td class="py-2 pr-4">
                                                                    @php
                                                                        $statusClass = match($camera->status ?? '') {
                                                                            'ativo' => 'bg-green-100 text-green-800',
                                                                            'aguardando_correcao' => 'bg-amber-100 text-amber-800',
                                                                            default => 'bg-gray-100 text-gray-800',
                                                                        };
                                                                        $statusLabel = \App\Models\Camera::statusOptions()[$camera->status ?? ''] ?? ucfirst($camera->status ?? '-');
                                                                    @endphp
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }}">{{ $statusLabel }}</span>
                                                                </td>
                                                                <td class="py-2 text-right">
                                                                    <div class="flex justify-end space-x-2 items-center">
                                                                        @php
                                                                            $histCam = $problemaHistoricoPorCamera[$camera->id] ?? collect();
                                                                            $histData = $histCam->map(fn($e) => ['data' => $e->cameraChecklist?->iniciado_em?->format('d/m/Y H:i') ?? $e->updated_at?->format('d/m/Y H:i') ?? '-', 'problema' => $e->descricao_problema ?? '-', 'acao' => $e->acao_corretiva_necessaria ?? '-', 'solucao' => $e->acao_corretiva_realizada ?? null])->values()->toArray();
                                                                        @endphp
                                                                        @if($histCam->isNotEmpty())
                                                                            <button type="button" class="btn-historico-camera-consulta text-indigo-600 hover:text-indigo-900" title="Histórico da Câmera" data-camera-nome="{{ e($camera->nome) }}" data-historical="{{ base64_encode(json_encode($histData)) }}"><i class="fas fa-history"></i></button>
                                                                        @else
                                                                            <span class="text-gray-300">—</span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @php $viewerIdxConsulta++; @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @if($dvr->cameras->count() === 0)
                                            <p class="text-gray-500 text-sm py-2">Nenhuma câmera neste DVR.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Nenhum DVR ativo cadastrado.</p>
        @endif
    </div>
</div>
