@extends('layouts.app')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <i class="fas fa-clipboard-check mr-2" style="color: #E9B32C;"></i>
            Checklist — {{ $mostrarTodosDvrs ?? false ? 'Todos os DVRs' : ($checklist->dvrs->count() > 0 ? $checklist->dvrs->pluck('nome')->join(', ') : ($checklist->dvr?->nome ?? '')) }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Responsável: {{ $checklist->responsavel }} | Iniciado em {{ $checklist->iniciado_em->format('d/m/Y H:i') }}
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('cameras.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
        <button type="button" id="btnFinalizarChecklist" onclick="finalizarChecklist()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-green-600" disabled>
            <i class="fas fa-check mr-2"></i>Finalizar Checklist
        </button>
        <button type="button" onclick="cancelarChecklist()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">
            <i class="fas fa-times mr-2"></i>Cancelar
        </button>
    </div>
</div>
@endsection

@section('content')
@php
    $camerasFlatList = [];
    foreach ($itensPorDvr ?? [] as $dvrId => $itens) {
        $dvr = $itens->first()->camera->dvr;
        foreach ($itens as $item) {
            if ($item->camera->foto) {
                $camerasFlatList[] = [
                    'dvrNome' => $dvr->nome,
                    'cameraNome' => $item->camera->nome,
                    'fotoUrl' => asset('storage/' . $item->camera->foto),
                    'itemId' => $item->id,
                ];
            }
        }
    }
    $evidenciaFlatList = [];
    $evidenciaIndexMap = [];
    foreach ($checklist->anexos->sortBy('dvr_id')->sortBy('id') as $idx => $anexo) {
        $dvrNome = $anexo->dvr?->nome ?? 'Geral';
        $evidenciaFlatList[] = [
            'dvrNome' => $dvrNome,
            'url' => asset('storage/' . $anexo->caminho_arquivo),
            'anexoId' => $anexo->id,
            'dvrId' => $anexo->dvr_id,
        ];
        $evidenciaIndexMap[$anexo->id] = $idx;
    }
    $dvrIdsComEvidencia = $checklist->anexos->pluck('dvr_id')->unique()->filter()->values()->toArray();
@endphp
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- DVRs e Câmeras (estilo Gerenciar) --}}
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-video mr-2 text-gray-600"></i>
                    DVRs e Câmeras ({{ $checklist->itens->count() }})
                </h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase w-10"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">DVR</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Localização</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Câmeras</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evidências</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $viewerIdx = 0; @endphp
                            @foreach($itensPorDvr as $dvrId => $itens)
                                @php
                                    $dvr = $itens->first()->camera->dvr;
                                    $dvrIndex = $loop->index;
                                    $dvrCompleto = $itens->every(fn($i) => $i->online !== null && $i->angulo_correto !== null && $i->gravando !== null);
                                @endphp
                                <tr data-dvr-id="{{ $dvr->id }}" data-dvr-index="{{ $dvrIndex }}" class="hover:bg-gray-50 checklist-dvr-row {{ $dvrCompleto ? 'bg-green-50' : '' }}">
                                    <td class="px-4 py-3">
                                        <button type="button" onclick="toggleDvrExpand({{ $dvr->id }})" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                            <i class="fas fa-chevron-right expand-icon transition-transform duration-200" id="expand-icon-{{ $dvr->id }}"></i>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $dvr->nome }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $dvr->localizacao ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $itens->count() }} câmeras</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button type="button" onclick="tudoOkDvr({{ $dvr->id }})" class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 whitespace-nowrap">
                                                <i class="fas fa-check-double mr-1"></i> Tudo Ok
                                            </button>
                                            <button type="button" onclick="abrirModalEvidencia({{ $dvr->id }}, this.dataset.dvrNome)" data-dvr-nome="{{ $dvr->nome }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 whitespace-nowrap">
                                                <i class="fas fa-image mr-1"></i> Evidência
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $anexosDvr = $checklist->anexos->where('dvr_id', $dvr->id); @endphp
                                        @if($anexosDvr->count() > 0)
                                            <div class="grid grid-cols-3 gap-2 w-52">
                                                @foreach($anexosDvr as $anexo)
                                                <div class="relative group">
                                                    <img src="{{ asset('storage/' . $anexo->caminho_arquivo) }}" alt="Evidência" class="h-14 w-full rounded border object-cover cursor-pointer evidencia-thumb aspect-video" data-dvr-id="{{ $dvr->id }}" data-dvr-nome="{{ addslashes($dvr->nome) }}" data-url="{{ asset('storage/' . $anexo->caminho_arquivo) }}" data-anexo-id="{{ $anexo->id }}" data-evidencia-index="{{ $evidenciaIndexMap[$anexo->id] ?? 0 }}" title="Clique para ampliar">
                                                    <button type="button" class="btn-remover-evidencia absolute -top-1 -right-1 bg-red-600 text-white rounded-full w-4 h-4 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100" data-anexo-id="{{ $anexo->id }}" title="Remover">
                                                        <i class="fas fa-times" style="font-size:8px"></i>
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr data-dvr-cameras="{{ $dvr->id }}" class="dvr-cameras-row hidden bg-gray-50">
                                    <td colspan="7" class="px-6 py-0">
                                        <div class="border-l-4 border-blue-200 pl-4 py-3">
                                            <span class="text-sm font-medium text-gray-700">Câmeras do DVR {{ $dvr->nome }}</span>
                                            <div class="overflow-x-auto mt-2">
                                                <table class="min-w-full text-sm">
                                                    <thead>
                                                        <tr class="border-b border-gray-200">
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Nome</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Foto</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Online?</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Ângulo Correto?</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Gravando?</th>
                                                            <th class="text-left py-2 pr-4 font-medium text-gray-600">Ações</th>
                                                            <th class="text-left py-2 font-medium text-gray-600">Solução</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($itens as $item)
                                                        @php
                                                                $anexosSolucao = $checklist->anexos->where('camera_id', $item->camera_id);
                                                            @endphp
                                                        <tr data-item-id="{{ $item->id }}" data-camera-id="{{ $item->camera_id }}" data-dvr-id="{{ $dvr->id }}" data-tem-problema="{{ $item->problema ? '1' : '0' }}" data-aguardando-correcao="{{ $item->camera->status === \App\Models\Camera::STATUS_AGUARDANDO_CORRECAO ? '1' : '0' }}" data-descricao-problema="{{ e($item->descricao_problema ?? '') }}" data-acao-corretiva="{{ e($item->acao_corretiva_necessaria ?? '') }}" data-acao-corretiva-realizada="{{ e($item->acao_corretiva_realizada ?? '') }}" data-solucao-anexos="{{ $anexosSolucao->map(fn($a) => asset('storage/' . $a->caminho_arquivo))->values()->toJson() }}" class="border-b border-gray-100 hover:bg-white checklist-item-row">
                                                            <td class="py-2 pr-4 font-medium text-gray-900">{{ $item->camera->nome }}</td>
                                                            <td class="py-2 pr-4">
                                                                @if($item->camera->foto)
                                                                    <button type="button" onclick="openCameraViewer({{ $viewerIdx }})" class="cursor-pointer hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 rounded">
                                                                        <img src="{{ asset('storage/' . $item->camera->foto) }}" alt="{{ $item->camera->nome }}" class="h-12 w-auto rounded border border-gray-300 object-cover">
                                                                    </button>
                                                                    @php $viewerIdx++; @endphp
                                                                @else
                                                                    <span class="text-gray-400">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="py-2 pr-4">
                                                                <div class="flex gap-2">
                                                                    <label class="inline-flex items-center cursor-pointer">
                                                                        <input type="radio" name="online_{{ $item->id }}" value="1" class="online-sim" data-item-id="{{ $item->id }}" {{ $item->online === true ? 'checked' : '' }} onchange="salvarCheckboxItem({{ $item->id }}, 'online', true)">
                                                                        <span class="ml-1 text-xs">Sim</span>
                                                                    </label>
                                                                    <label class="inline-flex items-center cursor-pointer">
                                                                        <input type="radio" name="online_{{ $item->id }}" value="0" class="online-nao" data-item-id="{{ $item->id }}" {{ $item->online === false ? 'checked' : '' }} onchange="salvarCheckboxItem({{ $item->id }}, 'online', false)">
                                                                        <span class="ml-1 text-xs">Não</span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-2 pr-4">
                                                                <div class="flex gap-2">
                                                                    <label class="inline-flex items-center cursor-pointer">
                                                                        <input type="radio" name="angulo_{{ $item->id }}" value="1" class="angulo-sim" data-item-id="{{ $item->id }}" {{ $item->angulo_correto === true ? 'checked' : '' }} onchange="salvarCheckboxItem({{ $item->id }}, 'angulo_correto', true)">
                                                                        <span class="ml-1 text-xs">Sim</span>
                                                                    </label>
                                                                    <label class="inline-flex items-center cursor-pointer">
                                                                        <input type="radio" name="angulo_{{ $item->id }}" value="0" class="angulo-nao" data-item-id="{{ $item->id }}" {{ $item->angulo_correto === false ? 'checked' : '' }} onchange="salvarCheckboxItem({{ $item->id }}, 'angulo_correto', false)">
                                                                        <span class="ml-1 text-xs">Não</span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-2 pr-4">
                                                                <div class="flex gap-2">
                                                                    <label class="inline-flex items-center cursor-pointer">
                                                                        <input type="radio" name="gravando_{{ $item->id }}" value="1" class="gravando-sim" data-item-id="{{ $item->id }}" {{ $item->gravando === true ? 'checked' : '' }} onchange="salvarCheckboxItem({{ $item->id }}, 'gravando', true)">
                                                                        <span class="ml-1 text-xs">Sim</span>
                                                                    </label>
                                                                    <label class="inline-flex items-center cursor-pointer">
                                                                        <input type="radio" name="gravando_{{ $item->id }}" value="0" class="gravando-nao" data-item-id="{{ $item->id }}" {{ $item->gravando === false ? 'checked' : '' }} onchange="salvarCheckboxItem({{ $item->id }}, 'gravando', false)">
                                                                        <span class="ml-1 text-xs">Não</span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="py-2">
                                                                <input type="hidden" class="observacao-input" data-item-id="{{ $item->id }}" value="{{ $item->observacao ?? '' }}">
                                                                <div class="flex gap-1 flex-wrap">
                                                                    <button type="button" onclick="abrirModalObservacao({{ $item->id }})" class="px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300" title="Observação">
                                                                        <i class="fas fa-comment-dots mr-1"></i>Obs
                                                                    </button>
                                                                    <button type="button" onclick="abrirModalProblemaParaCamera({{ $item->id }})" class="px-2 py-1 bg-amber-100 text-amber-800 text-xs rounded hover:bg-amber-200 problema-camera-btn" data-item-id="{{ $item->id }}" title="Ação corretiva (quando marcado Não)">
                                                                        <i class="fas fa-exclamation-triangle mr-1"></i>Problema
                                                                    </button>
                                                                </div>
                                                            </td>
                                                            <td class="py-2 pr-4 solucao-cell" data-item-id="{{ $item->id }}">
                                                                @if($item->camera->status === \App\Models\Camera::STATUS_AGUARDANDO_CORRECAO)
                                                                    @if($item->acao_corretiva_realizada || $anexosSolucao->count() > 0)
                                                                    <div class="solucao-conteudo text-xs space-y-1">
                                                                        @if($item->acao_corretiva_realizada)
                                                                        <p class="text-green-800"><strong>O que foi feito:</strong> {{ $item->acao_corretiva_realizada }}</p>
                                                                        @endif
                                                                        @if($anexosSolucao->count() > 0)
                                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                                            @foreach($anexosSolucao as $ax)
                                                                            <a href="{{ asset('storage/' . $ax->caminho_arquivo) }}" target="_blank" class="inline-block" title="Clique para ampliar"><img src="{{ asset('storage/' . $ax->caminho_arquivo) }}" alt="Evidência" class="h-10 w-auto rounded border object-cover cursor-pointer hover:opacity-80"></a>
                                                                            @endforeach
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @else
                                                                    <button type="button" onclick="abrirModalSolucao({{ $item->id }}, {{ $item->camera_id }}, '{{ addslashes($item->camera->nome) }}')" class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded hover:bg-green-200" title="Registrar solução e voltar câmera para Ativo">
                                                                        <i class="fas fa-check-circle mr-1"></i>Solução
                                                                    </button>
                                                                    @endif
                                                                @else
                                                                    @if($item->acao_corretiva_realizada || $anexosSolucao->count() > 0)
                                                                    <div class="solucao-conteudo text-xs space-y-1">
                                                                        @if($item->acao_corretiva_realizada)
                                                                        <p class="text-green-800"><strong>O que foi feito:</strong> {{ $item->acao_corretiva_realizada }}</p>
                                                                        @endif
                                                                        @if($anexosSolucao->count() > 0)
                                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                                            @foreach($anexosSolucao as $ax)
                                                                            <a href="{{ asset('storage/' . $ax->caminho_arquivo) }}" target="_blank" class="inline-block" title="Clique para ampliar"><img src="{{ asset('storage/' . $ax->caminho_arquivo) }}" alt="Evidência" class="h-10 w-auto rounded border object-cover cursor-pointer hover:opacity-80"></a>
                                                                            @endforeach
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                    @else
                                                                    <span class="text-gray-400">-</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $historicoProblema = $problemaHistoricoPorCamera[$item->camera_id] ?? collect();
                                                            $mostrarProblemaChecklist = ($historicoProblema->isNotEmpty() && $item->camera->status === \App\Models\Camera::STATUS_AGUARDANDO_CORRECAO) || ($item->problema && ($item->descricao_problema || $item->acao_corretiva_necessaria));
                                                            $entradasParaExibir = ($item->camera->status === \App\Models\Camera::STATUS_AGUARDANDO_CORRECAO)
                                                                ? $historicoProblema
                                                                : $historicoProblema->where('camera_checklist_id', $checklist->id);
                                                        @endphp
                                                        @if($mostrarProblemaChecklist && $entradasParaExibir->isNotEmpty())
                                                        <tr class="bg-amber-50 border-b border-amber-100 problema-row problema-historico-row" data-item-id="{{ $item->id }}" data-camera-id="{{ $item->camera_id }}">
                                                            <td colspan="7" class="py-2 px-4 text-xs text-amber-800">
                                                                <div class="space-y-2 problema-historico-conteudo">
                                                                    @foreach($entradasParaExibir as $entrada)
                                                                    <div class="problema-historico-entrada flex items-start gap-2" data-entrada-id="{{ $entrada->id }}">
                                                                        <button type="button" onclick="abrirModalExcluirHistorico({{ $entrada->id }})" class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded bg-red-500 hover:bg-red-600 text-white text-xs font-bold cursor-pointer" title="Excluir histórico">
                                                                            <i class="fas fa-times" style="font-size:10px"></i>
                                                                        </button>
                                                                        <span class="flex-1">
                                                                            <span class="font-medium text-amber-900">[{{ $entrada->cameraChecklist?->iniciado_em?->format('d/m/Y H:i') ?? $entrada->updated_at?->format('d/m/Y H:i') ?? '-' }}]</span>
                                                                            <strong>Problema:</strong> {{ $entrada->descricao_problema ?? '-' }} |
                                                                            <strong>Ação:</strong> {{ $entrada->acao_corretiva_necessaria ?? '-' }}
                                                                            @if($entrada->acao_corretiva_realizada)
                                                                            <span class="block text-green-700 font-medium mt-0.5">Solução aplicada: {{ $entrada->acao_corretiva_realizada }}</span>
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Visualizador de Câmera --}}
<div id="cameraViewerModal" class="fixed inset-0 bg-gray-900 bg-opacity-90 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
    <div class="relative bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
            <div>
                <h3 class="text-sm font-medium text-gray-500">DVR</h3>
                <p id="cameraViewerDvrName" class="text-lg font-semibold text-gray-900"></p>
                <h3 class="text-sm font-medium text-gray-500 mt-1">Câmera</h3>
                <p id="cameraViewerCameraName" class="text-base font-medium text-gray-800"></p>
            </div>
            <button type="button" onclick="closeCameraViewer()" class="text-gray-400 hover:text-gray-600 p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 flex items-center justify-center p-6 bg-gray-100 min-h-[300px]">
            <div id="cameraViewerImageContainer" class="relative">
                <img id="cameraViewerImage" src="" alt="" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg" style="display: none;">
                <div id="cameraViewerPlaceholder" class="hidden flex items-center justify-center w-64 h-48 bg-gray-200 rounded-lg text-gray-500">
                    <span><i class="fas fa-image mr-2"></i>Sem imagem</span>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <button type="button" onclick="cameraViewerPrev()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                <i class="fas fa-chevron-left mr-2"></i>Anterior
            </button>
            <span id="cameraViewerCounter" class="text-sm text-gray-600"></span>
            <button type="button" onclick="cameraViewerNext()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                Próxima<i class="fas fa-chevron-right ml-2"></i>
            </button>
        </div>
    </div>
</div>

{{-- Modal Ação Corretiva (ao clicar Não ou ao Finalizar) --}}
<div id="modalAcaoCorretiva" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 p-6 max-h-[90vh] overflow-y-auto relative">
        <button type="button" onclick="fecharModalAcaoCorretiva()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1 focus:outline-none" title="Fechar">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="font-semibold text-lg text-gray-900 mb-2 pr-8">
            <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
            Câmeras com problema
        </h3>
        <p id="modalAcaoCorretivaTexto" class="text-sm text-gray-600 mb-4">As seguintes câmeras foram marcadas com &quot;Não&quot; em algum item do checklist. Informe a ação que será feita para corrigir:</p>
        <div id="modalAcaoCorretivaCameras" class="space-y-4"></div>
    </div>
</div>

{{-- Modal Solução (câmera aguardando correção) --}}
<div id="modalSolucao" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 p-6 relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="fecharModalSolucao()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1" title="Fechar">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="font-semibold text-lg text-gray-900 mb-2 pr-8">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            Registrar Solução
        </h3>
        <p class="text-sm text-gray-500 mb-4">Câmera: <strong id="modalSolucaoCameraNome"></strong></p>
        <p id="modalSolucaoInstrucao" class="text-sm text-gray-600 mb-4">Descreva o que foi feito para corrigir a câmera. Adicione evidências (fotos) se quiser. Ao salvar, a câmera voltará para status <strong>Ativo</strong>.</p>
        <label class="block text-sm font-medium text-gray-700 mb-1">O que foi feito para corrigir? *</label>
        <textarea id="modalSolucaoTexto" class="w-full border rounded p-2 text-sm mb-4" rows="4" placeholder="Ex: Cabo substituído, ângulo ajustado, limpeza da lente..."></textarea>
        <label class="block text-sm font-medium text-gray-700 mb-1">Evidências (opcional)</label>
        <div id="solucaoDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4 cursor-pointer hover:border-green-500 transition-colors">
            <input type="file" id="solucaoFileInput" accept="image/*" multiple class="hidden">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
            <p class="text-sm text-gray-600">Clique para selecionar ou arraste imagens aqui</p>
        </div>
        <div id="solucaoPreviewList" class="grid grid-cols-3 gap-2 mb-4 max-h-40 overflow-y-auto"></div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="fecharModalSolucao()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancelar</button>
            <button type="button" onclick="salvarModalSolucao()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                <i class="fas fa-check mr-1"></i>Registrar Solução
            </button>
        </div>
    </div>
</div>

{{-- Modal Observação --}}
<div id="modalObservacao" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6 relative">
        <button type="button" onclick="fecharModalObservacao()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1" title="Fechar">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="font-semibold text-lg text-gray-900 mb-2 pr-8">Observação</h3>
        <label class="block text-sm text-gray-700 mb-1">Descrição da observação:</label>
        <textarea id="modalObservacaoTexto" class="w-full border rounded p-2 text-sm mb-4" rows="3" placeholder="Digite a observação..."></textarea>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="fecharModalObservacao()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancelar</button>
            <button type="button" onclick="salvarModalObservacao()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
        </div>
    </div>
</div>

{{-- Modal Upload Evidência --}}
<div id="modalEvidencia" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div id="modalEvidenciaContent" class="bg-white rounded-lg shadow-xl max-w-2xl w-full flex flex-col max-h-[90vh]" tabindex="-1">
        <div class="flex-shrink-0 p-6 pb-0 relative">
            <button type="button" onclick="fecharModalEvidencia()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1" title="Fechar">
                <i class="fas fa-times text-xl"></i>
            </button>
            <h3 class="font-semibold text-lg text-gray-900 mb-2 pr-8">
                <i class="fas fa-image text-blue-500 mr-2"></i>
                Evidência DVR: <span id="modalEvidenciaDvrNome"></span>
            </h3>
            <p class="text-sm text-gray-500 mb-4">Importe do computador ou cole com Ctrl+V (print da tela)</p>
        </div>
        <div class="flex-1 overflow-y-auto px-6">
            <div id="evidenciaDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4 cursor-pointer hover:border-blue-500 transition-colors">
                <input type="file" id="evidenciaFileInput" accept="image/*" multiple class="hidden">
                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                <p class="text-sm text-gray-600">Clique para selecionar ou arraste imagens aqui</p>
                <p class="text-xs text-gray-500 mt-1">ou use Ctrl+V para colar</p>
            </div>
            <div id="evidenciaPreviewList" class="grid grid-cols-3 gap-2 mb-4 max-h-48 overflow-y-auto"></div>
        </div>
        <div class="flex-shrink-0 p-6 pt-4 border-t border-gray-100 flex justify-end gap-2">
            <button type="button" onclick="fecharModalEvidencia()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancelar</button>
            <button type="button" id="btnSalvarEvidencia" onclick="salvarEvidenciasModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                <i class="fas fa-save mr-1"></i>Salvar
            </button>
        </div>
    </div>
</div>

{{-- Modal Visualizador Evidência --}}
<div id="modalEvidenciaViewer" class="fixed inset-0 bg-gray-900 bg-opacity-90 hidden z-[99999] flex items-center justify-center p-4">
    <div class="relative bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
            <h3 class="text-lg font-semibold text-gray-900">
                Evidência DVR: <span id="evidenciaViewerDvrNome"></span>
            </h3>
            <button type="button" onclick="fecharEvidenciaViewer()" class="text-gray-400 hover:text-gray-600 p-2">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 flex items-center justify-center p-6 bg-gray-100 min-h-[300px]">
            <img id="evidenciaViewerImage" src="" alt="" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg">
        </div>
        <div class="flex justify-between items-center p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <button type="button" onclick="evidenciaViewerPrev()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                <i class="fas fa-chevron-left mr-2"></i>Anterior
            </button>
            <span id="evidenciaViewerCounter" class="text-sm text-gray-600"></span>
            <button type="button" onclick="evidenciaViewerNext()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                Próximo<i class="fas fa-chevron-right ml-2"></i>
            </button>
        </div>
    </div>
</div>

{{-- Modal Confirmar Remover Evidência --}}
<div id="modalConfirmarRemoverEvidencia" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[60] flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="font-semibold text-lg text-gray-900 mb-2">
            <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
            Remover evidência
        </h3>
        <p class="text-sm text-gray-600 mb-6">Remover esta evidência? Esta ação não pode ser desfeita.</p>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="fecharModalConfirmarRemoverEvidencia()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancelar</button>
            <button type="button" onclick="confirmarRemoverEvidencia()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Remover</button>
        </div>
    </div>
</div>

{{-- Modal DVRs sem Evidência --}}
<div id="modalDvrsSemEvidencia" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 p-6 relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="fecharModalDvrsSemEvidencia()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 p-1" title="Fechar">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h3 class="font-semibold text-lg text-gray-900 mb-2 pr-8">
            <i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>
            Evidência obrigatória
        </h3>
        <p class="text-sm text-gray-600 mb-4">Cada DVR precisa de pelo menos uma foto de evidência. Adicione nos DVRs abaixo:</p>
        <div id="modalDvrsSemEvidenciaLista" class="space-y-3 mb-6"></div>
    </div>
</div>

{{-- Modal Confirmação Finalizar Checklist --}}
<div id="modalConfirmarFinalizar" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="font-semibold text-lg text-gray-900 mb-2">
            <i class="fas fa-check-circle text-green-500 mr-2"></i>
            Finalizar Checklist
        </h3>
        <p class="text-sm text-gray-600 mb-6">Finalizar este checklist? Não será mais possível editá-lo.</p>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="fecharModalConfirmarFinalizar()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancelar</button>
            <button type="button" onclick="confirmarFinalizarChecklist()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Finalizar</button>
        </div>
    </div>
</div>

{{-- Modal Confirmação Cancelar Checklist --}}
<div id="modalConfirmarCancelar" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="font-semibold text-lg text-gray-900">Cancelar Checklist</h3>
                <p class="text-sm text-gray-600 mt-0.5">Cancelar este checklist? Todo o progresso será perdido.</p>
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="fecharModalConfirmarCancelar()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Não, manter</button>
            <button type="button" onclick="confirmarCancelarChecklist()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Sim, cancelar</button>
        </div>
    </div>
</div>

{{-- Modal Excluir Histórico (exige senha) --}}
<div id="modalExcluirHistorico" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                <i class="fas fa-trash-alt text-red-600"></i>
            </div>
            <div>
                <h3 class="font-semibold text-lg text-gray-900">Excluir Histórico</h3>
                <p class="text-sm text-gray-600 mt-0.5">Digite a senha para confirmar a exclusão deste registro de histórico:</p>
            </div>
        </div>
        <input type="password" id="modalExcluirHistoricoSenha" placeholder="Senha de confirmação" class="w-full border border-gray-300 rounded-md px-3 py-2 mb-3" autocomplete="off">
        <p id="modalExcluirHistoricoErro" class="text-red-500 text-sm mb-3 hidden"></p>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="fecharModalExcluirHistorico()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancelar</button>
            <button type="button" onclick="confirmarExcluirHistorico()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Excluir</button>
        </div>
    </div>
</div>

<style>
.expand-icon.expanded { transform: rotate(90deg); }
.dvr-cameras-row td { border-bottom: 1px solid #e5e7eb; }
</style>

<script>
const checklistId = {{ $checklist->id }};
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const storeItemUrl = '{{ route("cameras.checklists.itens.store", $checklist) }}';
const limparHistoricoBaseUrl = '{{ route("cameras.checklists.itens.limpar-historico", [$checklist, 0]) }}'.replace(/\/0\/limpar-historico/, '/');
const dvrIds = @json(array_keys($itensPorDvr->toArray()));
const camerasList = @json($camerasFlatList ?? []);
const evidenciaList = @json($evidenciaFlatList ?? []);
const dvrsList = @json($checklist->dvrs->map(fn($d) => ['id' => $d->id, 'nome' => $d->nome])->values()->toArray());
const dvrIdsComEvidencia = @json($dvrIdsComEvidencia ?? []);

let cameraViewerCurrentIndex = 0;

function showToastErro(mensagem) {
    if (typeof window.showToast === 'function') {
        window.showToast(mensagem, 'error', 4000);
        return;
    }
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = 'bg-amber-600 text-white border-amber-700 border-l-4 px-6 py-4 shadow-lg rounded-lg max-w-sm w-full flex items-center space-x-3 transition-all duration-300';
    toast.innerHTML = `
        <div class="flex-shrink-0"><i class="fas fa-exclamation-triangle text-lg"></i></div>
        <div class="flex-1"><p class="text-sm font-medium">${mensagem}</p></div>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

document.addEventListener('DOMContentLoaded', function() {
    restoreExpandedDvrs();
    atualizarEstadoBotaoFinalizar();
});

function openCameraViewer(index) {
    if (index < 0 || index >= camerasList.length) return;
    cameraViewerCurrentIndex = index;
    updateCameraViewerContent();
    document.getElementById('cameraViewerModal').classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function closeCameraViewer() {
    document.getElementById('cameraViewerModal').classList.add('hidden');
    document.body.classList.remove('modal-open');
}

function updateCameraViewerContent() {
    const item = camerasList[cameraViewerCurrentIndex];
    if (!item) return;
    document.getElementById('cameraViewerDvrName').textContent = item.dvrNome;
    document.getElementById('cameraViewerCameraName').textContent = item.cameraNome;
    const imgEl = document.getElementById('cameraViewerImage');
    const placeholderEl = document.getElementById('cameraViewerPlaceholder');
    if (item.fotoUrl) {
        imgEl.src = item.fotoUrl;
        imgEl.alt = item.cameraNome;
        imgEl.style.display = '';
        placeholderEl.classList.add('hidden');
    } else {
        imgEl.style.display = 'none';
        placeholderEl.classList.remove('hidden');
    }
    document.getElementById('cameraViewerCounter').textContent = (cameraViewerCurrentIndex + 1) + ' / ' + camerasList.length;
}

function cameraViewerNext() {
    cameraViewerCurrentIndex = (cameraViewerCurrentIndex + 1) % camerasList.length;
    updateCameraViewerContent();
}

function cameraViewerPrev() {
    cameraViewerCurrentIndex = cameraViewerCurrentIndex <= 0 ? camerasList.length - 1 : cameraViewerCurrentIndex - 1;
    updateCameraViewerContent();
}

document.getElementById('cameraViewerModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeCameraViewer();
});
document.getElementById('modalAcaoCorretiva')?.addEventListener('click', function(e) {
    if (e.target === this) fecharModalAcaoCorretiva();
});
document.getElementById('modalObservacao')?.addEventListener('click', function(e) {
    if (e.target === this) fecharModalObservacao();
});
document.addEventListener('keydown', function(e) {
    if (document.getElementById('cameraViewerModal')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') closeCameraViewer();
    if (e.key === 'ArrowRight') cameraViewerNext();
    if (e.key === 'ArrowLeft') cameraViewerPrev();
});
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modalAcaoCorretiva')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') fecharModalAcaoCorretiva();
});
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modalObservacao')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') fecharModalObservacao();
});

function tudoOkDvr(dvrId) {
    const tbody = document.querySelector(`tr[data-dvr-cameras="${dvrId}"] tbody`);
    if (!tbody) return;
    const rows = tbody.querySelectorAll('tr.checklist-item-row');
    rows.forEach(row => {
        const cameraId = parseInt(row.dataset.cameraId);
        const onlineSim = row.querySelector('input.online-sim');
        const anguloSim = row.querySelector('input.angulo-sim');
        const gravandoSim = row.querySelector('input.gravando-sim');
        if (onlineSim) onlineSim.checked = true;
        if (anguloSim) anguloSim.checked = true;
        if (gravandoSim) gravandoSim.checked = true;
        const obsEl = row.querySelector('.observacao-input');
        const payload = {
            camera_id: cameraId,
            online: true,
            angulo_correto: true,
            gravando: true,
            _token: csrfToken
        };
        if (obsEl && obsEl.value) payload.observacao = obsEl.value;
        fetch(storeItemUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        }).then(r => r.json()).then(d => {}).catch(e => console.error(e));
    });
    atualizarEstadoBotaoFinalizar();
    atualizarDestaqueDvr(dvrId);
}

function getItemRow(itemId) {
    return document.querySelector(`tr.checklist-item-row[data-item-id="${itemId}"]`);
}

function getItemData(itemId) {
    const row = getItemRow(itemId);
    if (!row) return null;
    const onlineEl = row.querySelector('input.online-sim:checked') || row.querySelector('input.online-nao:checked');
    const anguloEl = row.querySelector('input.angulo-sim:checked') || row.querySelector('input.angulo-nao:checked');
    const gravandoEl = row.querySelector('input.gravando-sim:checked') || row.querySelector('input.gravando-nao:checked');
    const obsEl = row.querySelector('.observacao-input');
    return {
        camera_id: parseInt(row.dataset.cameraId),
        online: onlineEl?.name?.startsWith('online_') ? (row.querySelector('input.online-sim')?.checked ? true : (row.querySelector('input.online-nao')?.checked ? false : null)) : null,
        angulo_correto: anguloEl?.name?.startsWith('angulo_') ? (row.querySelector('input.angulo-sim')?.checked ? true : (row.querySelector('input.angulo-nao')?.checked ? false : null)) : null,
        gravando: gravandoEl?.name?.startsWith('gravando_') ? (row.querySelector('input.gravando-sim')?.checked ? true : (row.querySelector('input.gravando-nao')?.checked ? false : null)) : null,
        observacao: obsEl?.value ?? null
    };
}

function salvarCheckboxItem(itemId, campo, valor) {
    const row = getItemRow(itemId);
    if (!row) return;
    const data = {
        camera_id: parseInt(row.dataset.cameraId),
        [campo]: valor,
        _token: csrfToken
    };
    const full = getItemData(itemId);
    if (full) {
        if (full.online !== null) data.online = full.online;
        if (full.angulo_correto !== null) data.angulo_correto = full.angulo_correto;
        if (full.gravando !== null) data.gravando = full.gravando;
        if (full.observacao !== null) data.observacao = full.observacao;
    }
    fetch(storeItemUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(r => r.json()).then(d => { if (d.success) {} }).catch(e => console.error(e));

    if (valor === false) {
        const camerasSemAcao = getTodasCamerasComProblemaSemAcao();
        if (camerasSemAcao.length > 0) {
            abrirModalAcaoCorretiva(camerasSemAcao, null, null, null, 'nao');
        }
    }
    atualizarEstadoBotaoFinalizar();
    const dvrId = row?.dataset?.dvrId;
    if (dvrId) atualizarDestaqueDvr(dvrId);
}

function atualizarDestaqueDvr(dvrId) {
    const camerasRow = document.querySelector(`tr[data-dvr-cameras="${dvrId}"]`);
    if (!camerasRow) return;
    const dvrRow = camerasRow.previousElementSibling;
    if (!dvrRow || !dvrRow.classList.contains('checklist-dvr-row')) return;
    const itemRows = camerasRow.querySelectorAll('tr.checklist-item-row');
    if (itemRows.length === 0) return;
    const todosPreenchidos = Array.from(itemRows).every(row => {
        const online = row.querySelector('input.online-sim:checked') || row.querySelector('input.online-nao:checked');
        const angulo = row.querySelector('input.angulo-sim:checked') || row.querySelector('input.angulo-nao:checked');
        const gravando = row.querySelector('input.gravando-sim:checked') || row.querySelector('input.gravando-nao:checked');
        return !!online && !!angulo && !!gravando;
    });
    dvrRow.classList.toggle('bg-green-50', todosPreenchidos);
}

function salvarObservacao(itemId) {
    const data = getItemData(itemId);
    if (!data) return;
    const payload = { camera_id: data.camera_id, observacao: data.observacao, _token: csrfToken };
    if (data.online !== null) payload.online = data.online;
    if (data.angulo_correto !== null) payload.angulo_correto = data.angulo_correto;
    if (data.gravando !== null) payload.gravando = data.gravando;
    fetch(storeItemUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    }).then(r => r.json()).then(d => {}).catch(e => console.error(e));
}

function getItensNao(row) {
    const itens = [];
    if (row.querySelector('input.online-nao:checked')) itens.push('Online?');
    if (row.querySelector('input.angulo-nao:checked')) itens.push('Ângulo Correto?');
    if (row.querySelector('input.gravando-nao:checked')) itens.push('Gravando?');
    return itens;
}

function temProblemaSemAcao(itemRow, paraFinalizar) {
    const itensNao = getItensNao(itemRow);
    const temNao = itensNao.length > 0;
    const jaTemAcao = itemRow.dataset.temProblema === '1';
    const aguardandoCorrecao = itemRow.dataset.aguardandoCorrecao === '1';
    if (paraFinalizar) {
        return temNao && !jaTemAcao;
    }
    return temNao && (!jaTemAcao || aguardandoCorrecao);
}

function getDvrNomeFromDvrRow(dvrRow) {
    const prevRow = dvrRow.previousElementSibling;
    if (!prevRow || !prevRow.classList.contains('checklist-dvr-row')) return '';
    return prevRow.querySelector('td:nth-child(2)')?.textContent?.trim() || '';
}

function getCamerasComProblemaSemAcao(dvrId, paraFinalizar) {
    const dvrRow = document.querySelector(`tr[data-dvr-cameras="${dvrId}"]`);
    const tbody = dvrRow?.querySelector('tbody');
    if (!tbody) return [];
    const dvrNome = getDvrNomeFromDvrRow(dvrRow);
    const rows = tbody.querySelectorAll('tr.checklist-item-row');
    const comProblema = [];
    rows.forEach(row => {
        if (temProblemaSemAcao(row, paraFinalizar)) {
            const nome = row.querySelector('td:first-child')?.textContent?.trim() || 'Câmera';
            const label = dvrNome ? `${dvrNome}: ${nome}` : nome;
            comProblema.push({ itemId: row.dataset.itemId, cameraId: row.dataset.cameraId, nome, dvrNome, label, itensNao: getItensNao(row) });
        }
    });
    return comProblema;
}

function getTodasCamerasComProblemaSemAcao(paraFinalizar) {
    const todas = [];
    const seen = new Set();
    document.querySelectorAll('tr.dvr-cameras-row').forEach(dvrRow => {
        const tbody = dvrRow.querySelector('tbody');
        if (!tbody) return;
        const dvrNome = getDvrNomeFromDvrRow(dvrRow);
        tbody.querySelectorAll('tr.checklist-item-row').forEach(row => {
            if (temProblemaSemAcao(row, paraFinalizar) && !seen.has(row.dataset.itemId)) {
                seen.add(row.dataset.itemId);
                const nome = row.querySelector('td:first-child')?.textContent?.trim() || 'Câmera';
                const label = dvrNome ? `${dvrNome}: ${nome}` : nome;
                todas.push({ itemId: row.dataset.itemId, cameraId: row.dataset.cameraId, nome, dvrNome, label, itensNao: getItensNao(row), descricaoProblema: row.dataset.descricaoProblema || '', acaoCorretiva: row.dataset.acaoCorretiva || '' });
            }
        });
    });
    return todas;
}

function getCamerasAguardandoCorrecaoComTudoSimSemSolucao() {
    const cameras = [];
    document.querySelectorAll('tr.checklist-item-row').forEach(row => {
        if (row.dataset.aguardandoCorrecao !== '1') return;
        const onlineSim = row.querySelector('input.online-sim:checked');
        const anguloSim = row.querySelector('input.angulo-sim:checked');
        const gravandoSim = row.querySelector('input.gravando-sim:checked');
        const tudoSim = onlineSim && anguloSim && gravandoSim;
        if (!tudoSim) return;
        const solucaoCell = row.querySelector('.solucao-cell');
        const jaTemSolucao = solucaoCell?.querySelector('.solucao-conteudo') || (row.dataset.acaoCorretivaRealizada && row.dataset.acaoCorretivaRealizada.length > 0);
        if (jaTemSolucao) return;
        const nome = row.querySelector('td:first-child')?.textContent?.trim() || 'Câmera';
        cameras.push({ itemId: row.dataset.itemId, cameraId: row.dataset.cameraId, nome, cameraNome: nome });
    });
    return cameras;
}

const STORAGE_KEY = 'engehub_checklist_' + checklistId + '_expanded_dvrs';

function getExpandedDvrIds() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return [];
        const arr = JSON.parse(raw);
        return Array.isArray(arr) ? arr.map(id => parseInt(id, 10)) : [];
    } catch (e) { return []; }
}

function saveExpandedDvrs() {
    const expanded = [];
    document.querySelectorAll('tr.dvr-cameras-row').forEach(row => {
        if (!row.classList.contains('hidden')) {
            const id = parseInt(row.dataset.dvrCameras, 10);
            if (!isNaN(id)) expanded.push(id);
        }
    });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(expanded));
}

function restoreExpandedDvrs() {
    const ids = getExpandedDvrIds();
    document.querySelectorAll('tr.dvr-cameras-row').forEach(row => {
        const id = parseInt(row.dataset.dvrCameras, 10);
        const icon = document.getElementById('expand-icon-' + id);
        if (ids.includes(id)) {
            row.classList.remove('hidden');
            if (icon) icon.classList.add('expanded');
        } else {
            row.classList.add('hidden');
            if (icon) icon.classList.remove('expanded');
        }
    });
}

function toggleDvrExpand(dvrId) {
    const row = document.querySelector(`tr[data-dvr-cameras="${dvrId}"]`);
    const icon = document.getElementById(`expand-icon-${dvrId}`);
    if (!row || !icon) return;

    const estavaAberto = !row.classList.contains('hidden');

    if (estavaAberto) {
        const camerasComProblema = getCamerasComProblemaSemAcao(dvrId, true);
        if (camerasComProblema.length > 0) {
            abrirModalAcaoCorretiva(camerasComProblema, dvrId, row, icon, 'collapse');
            return;
        }
    }

    row.classList.toggle('hidden');
    icon.classList.toggle('expanded');
    saveExpandedDvrs();
}

function abrirModalAcaoCorretiva(cameras, dvrId, rowToClose, iconToUpdate, context) {
    const container = document.getElementById('modalAcaoCorretivaCameras');
    const textoEl = document.getElementById('modalAcaoCorretivaTexto');
    textoEl.textContent = 'As seguintes câmeras foram marcadas com "Não" em algum item do checklist. Informe a ação que será feita para corrigir:';
    container.innerHTML = '';
    cameras.forEach(cam => {
        const itensNaoTexto = (cam.itensNao && cam.itensNao.length) ? cam.itensNao.join(', ') : '';
        const label = cam.label || cam.nome;
        const descricaoVal = (cam.descricaoProblema || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        const acaoVal = (cam.acaoCorretiva || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        const div = document.createElement('div');
        div.className = 'border rounded p-4 bg-gray-50';
        div.innerHTML = `
            <p class="font-medium text-gray-900 mb-1">${label}</p>
            ${itensNaoTexto ? `<p class="text-xs text-amber-700 mb-2">Marcado "Não" em: ${itensNaoTexto}</p>` : ''}
            <label class="block text-sm text-gray-700 mb-1">Descrição do problema</label>
            <textarea class="descricao-problema-input w-full border rounded p-2 text-sm mb-3" rows="2" data-item-id="${cam.itemId}" data-camera-id="${cam.cameraId}" placeholder="Descreva o problema identificado...">${descricaoVal}</textarea>
            <label class="block text-sm text-gray-700 mb-1">Qual será a ação corretiva?</label>
            <textarea class="acao-corretiva-input w-full border rounded p-2 text-sm" rows="2" data-item-id="${cam.itemId}" data-camera-id="${cam.cameraId}" placeholder="Descreva a ação corretiva...">${acaoVal}</textarea>
            <button type="button" onclick="salvarAcaoCorretiva(this)" data-item-id="${cam.itemId}" data-camera-id="${cam.cameraId}" class="mt-2 px-3 py-1 bg-amber-600 text-white rounded text-sm hover:bg-amber-700">Salvar</button>
        `;
        container.appendChild(div);
    });
    window._modalAcaoDados = { dvrId, rowToClose, iconToUpdate };
    window._modalAcaoContext = context || (rowToClose ? 'collapse' : 'nao');
    document.getElementById('modalAcaoCorretiva').classList.remove('hidden');
}

function salvarAcaoCorretiva(btn) {
    const itemId = btn.dataset.itemId;
    const cameraId = btn.dataset.cameraId;
    const card = btn.closest('.border');
    const descricaoEl = card?.querySelector('.descricao-problema-input');
    const acaoEl = card?.querySelector('.acao-corretiva-input');
    const descricao = descricaoEl?.value?.trim() || '';
    const acao = acaoEl?.value?.trim();
    if (!descricao) {
        showToastErro('Preencha a descrição do problema.');
        return;
    }
    if (!acao) {
        showToastErro('Preencha a ação corretiva.');
        return;
    }
    const context = window._modalAcaoContext || 'nao';
    fetch(storeItemUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({
            camera_id: parseInt(cameraId),
            problema: true,
            descricao_problema: descricao,
            acao_corretiva_necessaria: acao,
            _token: csrfToken
        })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const mainRow = getItemRow(itemId);
            if (mainRow) {
                mainRow.dataset.temProblema = '1';
                mainRow.dataset.descricaoProblema = descricao;
                mainRow.dataset.acaoCorretiva = acao;
                injectarOuAtualizarLinhaProblema(mainRow, descricao, acao);
            }
            card?.remove();
            const container = document.getElementById('modalAcaoCorretivaCameras');
            if (container.children.length === 0) {
                const dados = window._modalAcaoDados;
                fecharModalAcaoCorretiva();
                if (context === 'collapse' && dados?.rowToClose && dados?.iconToUpdate) {
                    dados.rowToClose.classList.add('hidden');
                    dados.iconToUpdate.classList.remove('expanded');
                    saveExpandedDvrs();
                } else if (context === 'finalizar') {
                    document.getElementById('modalConfirmarFinalizar').classList.remove('hidden');
                }
            }
        }
    });
}

function injectarOuAtualizarLinhaProblema(mainRow, descricao, acao) {
    const now = new Date();
    const dataStr = String(now.getDate()).padStart(2,'0') + '/' + String(now.getMonth()+1).padStart(2,'0') + '/' + now.getFullYear() + ' ' + String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
    const descricaoEsc = (descricao || '-').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    const acaoEsc = (acao || '-').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    const itemId = mainRow.dataset.itemId;
    const entradaHtml = `<div class="problema-historico-entrada flex items-start gap-2" data-entrada-id="${itemId}"><button type="button" onclick="abrirModalExcluirHistorico(${itemId})" class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded bg-red-500 hover:bg-red-600 text-white text-xs font-bold cursor-pointer" title="Excluir histórico"><i class="fas fa-times" style="font-size:10px"></i></button><span class="flex-1"><span class="font-medium text-amber-900">[${dataStr}]</span> <strong>Problema:</strong> ${descricaoEsc} | <strong>Ação:</strong> ${acaoEsc}</span></div>`;
    let problemRow = mainRow.nextElementSibling;
    const isProblemRow = problemRow && (problemRow.classList.contains('bg-amber-50') || problemRow.classList.contains('problema-row')) && (problemRow.dataset.itemId === mainRow.dataset.itemId || problemRow.dataset.cameraId === mainRow.dataset.cameraId);
    if (isProblemRow) {
        const container = problemRow.querySelector('.problema-historico-conteudo');
        if (container) {
            container.insertAdjacentHTML('beforeend', entradaHtml);
        } else {
            const td = problemRow.querySelector('td[colspan="7"]') || problemRow.querySelector('td');
            if (td) td.innerHTML = `<div class="space-y-2 problema-historico-conteudo">${entradaHtml}</div>`;
        }
    } else {
        problemRow = document.createElement('tr');
        problemRow.className = 'bg-amber-50 border-b border-amber-100 problema-row problema-historico-row';
        problemRow.setAttribute('data-item-id', mainRow.dataset.itemId);
        problemRow.setAttribute('data-camera-id', mainRow.dataset.cameraId);
        problemRow.innerHTML = `<td colspan="7" class="py-2 px-4 text-xs text-amber-800"><div class="space-y-2 problema-historico-conteudo">${entradaHtml}</div></td>`;
        mainRow.parentNode.insertBefore(problemRow, mainRow.nextSibling);
    }
}

function fecharModalAcaoCorretiva() {
    document.getElementById('modalAcaoCorretiva').classList.add('hidden');
    window._modalAcaoDados = null;
}

function abrirModalObservacao(itemId) {
    const row = getItemRow(itemId);
    const obsEl = row?.querySelector('.observacao-input');
    const valor = obsEl?.value ?? '';
    window._modalObsItemId = itemId;
    document.getElementById('modalObservacaoTexto').value = valor;
    document.getElementById('modalObservacao').classList.remove('hidden');
}

function fecharModalObservacao() {
    document.getElementById('modalObservacao').classList.add('hidden');
    window._modalObsItemId = null;
}

function salvarModalObservacao() {
    const itemId = window._modalObsItemId;
    if (!itemId) return;
    const row = getItemRow(itemId);
    const obsEl = row?.querySelector('.observacao-input');
    const valor = document.getElementById('modalObservacaoTexto')?.value ?? '';
    if (obsEl) obsEl.value = valor;
    fecharModalObservacao();
    salvarObservacao(itemId);
}

function abrirModalProblemaParaCamera(itemId) {
    const row = getItemRow(itemId);
    if (!row) return;
    const temNao = row.querySelector('input.online-nao:checked, input.angulo-nao:checked, input.gravando-nao:checked');
    if (!temNao) {
        showToastErro('Esta câmera não possui itens marcados com "Não" no checklist.');
        return;
    }
    const camerasSemAcao = getTodasCamerasComProblemaSemAcao();
    if (camerasSemAcao.length > 0) {
        abrirModalAcaoCorretiva(camerasSemAcao, null, null, null, 'nao');
    } else {
        const dvrRow = row.closest('tr.dvr-cameras-row');
        const dvrNome = getDvrNomeFromDvrRow(dvrRow);
        const nome = row.querySelector('td:first-child')?.textContent?.trim() || 'Câmera';
        const label = dvrNome ? `${dvrNome}: ${nome}` : nome;
        abrirModalAcaoCorretiva([{
            itemId: row.dataset.itemId,
            cameraId: row.dataset.cameraId,
            nome,
            dvrNome,
            label,
            itensNao: getItensNao(row),
            descricaoProblema: row.dataset.descricaoProblema || '',
            acaoCorretiva: row.dataset.acaoCorretiva || ''
        }], null, null, null, 'nao');
    }
}

function continuarSemPreencher() {
    const dados = window._modalAcaoDados;
    fecharModalAcaoCorretiva();
    if (dados?.rowToClose) {
        dados.rowToClose.classList.add('hidden');
        document.getElementById('expand-icon-' + dados.dvrId)?.classList.remove('expanded');
        saveExpandedDvrs();
    }
}

let solucaoFilesToUpload = [];
let solucaoModalItemId = null;

function abrirModalSolucao(itemId, cameraId, cameraNome, fromFinalizar) {
    solucaoModalItemId = itemId;
    solucaoFilesToUpload = [];
    document.getElementById('modalSolucaoCameraNome').textContent = cameraNome || 'Câmera';
    document.getElementById('modalSolucaoTexto').value = '';
    document.getElementById('solucaoPreviewList').innerHTML = '';
    document.getElementById('solucaoFileInput').value = '';
    const instrucao = document.getElementById('modalSolucaoInstrucao');
    if (instrucao) {
        instrucao.innerHTML = fromFinalizar
            ? 'Para finalizar o checklist, você deve registrar a solução desta câmera. Descreva o que foi feito para corrigir. Ao salvar, a câmera voltará para status <strong>Ativo</strong> e você poderá finalizar.'
            : 'Descreva o que foi feito para corrigir a câmera. Adicione evidências (fotos) se quiser. Ao salvar, a câmera voltará para status <strong>Ativo</strong>.';
    }
    document.getElementById('modalSolucao').classList.remove('hidden');
}

function fecharModalSolucao() {
    document.getElementById('modalSolucao').classList.add('hidden');
    solucaoModalItemId = null;
    solucaoFilesToUpload = [];
    window._finalizarAposSolucao = false;
}

function addSolucaoFiles(files) {
    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    for (let i = 0; i < files.length; i++) {
        if (files[i] && validTypes.includes(files[i].type)) {
            solucaoFilesToUpload.push(files[i]);
            renderSolucaoPreview();
        }
    }
}

function removeSolucaoFile(index) {
    solucaoFilesToUpload.splice(index, 1);
    renderSolucaoPreview();
}

function renderSolucaoPreview() {
    const container = document.getElementById('solucaoPreviewList');
    container.innerHTML = solucaoFilesToUpload.map((file, i) => {
        const url = URL.createObjectURL(file);
        return `<div class="relative group">
            <img src="${url}" class="w-full h-20 object-cover rounded border">
            <button type="button" onclick="removeSolucaoFile(${i})" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-80 hover:opacity-100">
                <i class="fas fa-times" style="font-size:10px"></i>
            </button>
        </div>`;
    }).join('');
}

function salvarModalSolucao() {
    const texto = document.getElementById('modalSolucaoTexto')?.value?.trim() || '';
    if (!texto) {
        showToastErro('Descreva o que foi feito para corrigir.');
        return;
    }
    if (!solucaoModalItemId) return;

    const fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('item_id', solucaoModalItemId);
    fd.append('acao_corretiva_realizada', texto);
    solucaoFilesToUpload.forEach((file, i) => {
        fd.append('anexos[]', file);
    });

    fetch('{{ route("cameras.checklists.solucao.store", $checklist) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            fecharModalSolucao();
            const cell = document.querySelector(`.solucao-cell[data-item-id="${d.item_id}"]`);
            if (cell) {
                let html = '<div class="solucao-conteudo text-xs space-y-1">';
                if (d.acao_corretiva_realizada) {
                    html += '<p class="text-green-800"><strong>O que foi feito:</strong> ' + (d.acao_corretiva_realizada || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') + '</p>';
                }
                if (d.anexos && d.anexos.length > 0) {
                    html += '<div class="flex flex-wrap gap-1 mt-1">';
                    d.anexos.forEach(url => {
                        html += '<a href="' + url + '" target="_blank" class="inline-block" title="Clique para ampliar"><img src="' + url + '" alt="Evidência" class="h-10 w-auto rounded border object-cover cursor-pointer hover:opacity-80"></a>';
                    });
                    html += '</div>';
                }
                html += '</div>';
                cell.innerHTML = html;
            }
            const row = document.querySelector(`tr.checklist-item-row[data-item-id="${d.item_id}"]`);
            if (row) {
                row.dataset.aguardandoCorrecao = '0';
                const problemRow = row.nextElementSibling;
                if (problemRow && (problemRow.classList.contains('problema-row') || problemRow.classList.contains('problema-historico-row')) && (problemRow.dataset.cameraId === row.dataset.cameraId || problemRow.dataset.itemId === row.dataset.itemId)) {
                    problemRow.remove();
                }
            }
            if (window._finalizarAposSolucao) {
                window._finalizarAposSolucao = false;
                finalizarChecklist();
            }
        } else {
            showToastErro(d.message || 'Erro ao registrar solução.');
        }
    })
    .catch(() => showToastErro('Erro ao registrar solução.'));
}

document.getElementById('solucaoDropZone')?.addEventListener('click', () => document.getElementById('solucaoFileInput').click());
document.getElementById('solucaoFileInput')?.addEventListener('change', function() { addSolucaoFiles(Array.from(this.files || [])); this.value = ''; });
document.getElementById('solucaoDropZone')?.addEventListener('dragover', (e) => { e.preventDefault(); e.currentTarget.classList.add('border-green-500'); });
document.getElementById('solucaoDropZone')?.addEventListener('dragleave', (e) => { e.currentTarget.classList.remove('border-green-500'); });
document.getElementById('solucaoDropZone')?.addEventListener('drop', (e) => {
    e.preventDefault();
    e.currentTarget.classList.remove('border-green-500');
    addSolucaoFiles(Array.from(e.dataTransfer?.files || []));
});
document.getElementById('modalSolucao')?.addEventListener('click', function(e) { if (e.target === this) fecharModalSolucao(); });
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modalSolucao')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') fecharModalSolucao();
});

let evidenciaViewerIndex = 0;
let evidenciaModalDvrId = null;
let evidenciaModalDvrNome = '';
let evidenciaFilesToUpload = [];

function getDvrsSemEvidencia() {
    return dvrsList.filter(d => !dvrIdsComEvidencia.includes(d.id));
}

function abrirModalEvidencia(dvrId, dvrNome) {
    evidenciaModalDvrId = dvrId;
    evidenciaModalDvrNome = dvrNome || '';
    evidenciaFilesToUpload = [];
    document.getElementById('modalEvidenciaDvrNome').textContent = dvrNome || '';
    document.getElementById('evidenciaPreviewList').innerHTML = '';
    document.getElementById('evidenciaFileInput').value = '';
    const modal = document.getElementById('modalEvidencia');
    modal.classList.remove('hidden');
    document.getElementById('modalEvidenciaContent')?.focus?.();
}

function fecharModalEvidencia() {
    document.getElementById('modalEvidencia').classList.add('hidden');
    evidenciaModalDvrId = null;
}

function addEvidenciaFiles(files) {
    for (let i = 0; i < files.length; i++) {
        let f = files[i];
        if (!f) continue;
        const isImage = !f.type || f.type.startsWith('image/');
        if (!isImage) continue;
        if (!(f instanceof File) || !f.name || f.name === '') {
            const ext = (f.type === 'image/png') ? 'png' : (f.type === 'image/jpeg' || f.type === 'image/jpg') ? 'jpg' : (f.type === 'image/gif') ? 'gif' : 'png';
            f = new File([f], `evidencia-${Date.now()}.${ext}`, { type: f.type || 'image/png' });
        }
        evidenciaFilesToUpload.push(f);
        renderEvidenciaPreview();
    }
}

function removeEvidenciaFile(index) {
    evidenciaFilesToUpload.splice(index, 1);
    renderEvidenciaPreview();
}

function renderEvidenciaPreview() {
    const container = document.getElementById('evidenciaPreviewList');
    container.innerHTML = evidenciaFilesToUpload.map((file, i) => {
        const url = URL.createObjectURL(file);
        return `<div class="relative group">
            <img src="${url}" class="w-full h-20 object-cover rounded border">
            <button type="button" onclick="removeEvidenciaFile(${i})" class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-80 hover:opacity-100">
                <i class="fas fa-times" style="font-size:10px"></i>
            </button>
        </div>`;
    }).join('');
}

function salvarEvidenciasModal() {
    if (evidenciaFilesToUpload.length === 0) {
        showToastErro('Adicione pelo menos uma imagem.');
        return;
    }
    let done = 0;
    let hasError = false;
    const total = evidenciaFilesToUpload.length;
    evidenciaFilesToUpload.forEach(file => {
        const fd = new FormData();
        fd.append('anexo', file);
        fd.append('_token', csrfToken);
        if (evidenciaModalDvrId) fd.append('dvr_id', evidenciaModalDvrId);
        fetch('{{ route("cameras.checklists.anexos.store", $checklist) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: fd })
            .then(r => {
                if (!r.ok) return r.json().catch(() => ({ success: false, message: 'Erro no servidor.' }));
                return r.json();
            })
            .then(d => {
                done++;
                if (d && !d.success) {
                    hasError = true;
                    showToastErro(d.message || 'Erro ao enviar evidência.');
                }
                if (done === total && !hasError) {
                    fecharModalEvidencia();
                    location.reload();
                }
            })
            .catch(() => {
                done++;
                hasError = true;
                showToastErro('Erro ao enviar evidência. Tente selecionar o arquivo manualmente.');
            });
    });
}

document.getElementById('evidenciaDropZone')?.addEventListener('click', () => document.getElementById('evidenciaFileInput').click());
document.getElementById('evidenciaFileInput')?.addEventListener('change', function() { addEvidenciaFiles(Array.from(this.files)); this.value = ''; });
document.getElementById('evidenciaDropZone')?.addEventListener('dragover', (e) => { e.preventDefault(); e.currentTarget.classList.add('border-blue-500'); });
document.getElementById('evidenciaDropZone')?.addEventListener('dragleave', (e) => { e.currentTarget.classList.remove('border-blue-500'); });
document.getElementById('evidenciaDropZone')?.addEventListener('drop', (e) => {
    e.preventDefault();
    e.currentTarget.classList.remove('border-blue-500');
    addEvidenciaFiles(Array.from(e.dataTransfer.files));
});
document.addEventListener('paste', (e) => {
    if (document.getElementById('modalEvidencia')?.classList.contains('hidden')) return;
    let files = [];
    if (e.clipboardData?.files?.length) {
        files = Array.from(e.clipboardData.files).filter(f => f.type && f.type.startsWith('image/'));
    }
    if (files.length === 0 && e.clipboardData?.items) {
        for (let i = 0; i < e.clipboardData.items.length; i++) {
            const item = e.clipboardData.items[i];
            if (item.kind === 'file' && item.type && item.type.startsWith('image/')) {
                const file = item.getAsFile();
                if (file) files.push(file);
            }
        }
    }
    if (files.length) {
        e.preventDefault();
        addEvidenciaFiles(files);
    }
});

document.getElementById('modalEvidencia')?.addEventListener('click', function(e) { if (e.target === this) fecharModalEvidencia(); });
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modalEvidencia')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') fecharModalEvidencia();
});

function openEvidenciaViewer(index) {
    if (evidenciaList.length === 0) return;
    evidenciaViewerIndex = Math.max(0, Math.min(index, evidenciaList.length - 1));
    updateEvidenciaViewer();
    document.getElementById('modalEvidenciaViewer').classList.remove('hidden');
    document.body.classList.add('modal-open');
}

function fecharEvidenciaViewer() {
    document.getElementById('modalEvidenciaViewer').classList.add('hidden');
    document.body.classList.remove('modal-open');
}

function updateEvidenciaViewer() {
    const item = evidenciaList[evidenciaViewerIndex];
    if (!item) return;
    document.getElementById('evidenciaViewerDvrNome').textContent = item.dvrNome;
    document.getElementById('evidenciaViewerImage').src = item.url;
    document.getElementById('evidenciaViewerCounter').textContent = (evidenciaViewerIndex + 1) + ' / ' + evidenciaList.length;
}

function evidenciaViewerNext() {
    evidenciaViewerIndex = (evidenciaViewerIndex + 1) % evidenciaList.length;
    updateEvidenciaViewer();
}

function evidenciaViewerPrev() {
    evidenciaViewerIndex = evidenciaViewerIndex <= 0 ? evidenciaList.length - 1 : evidenciaViewerIndex - 1;
    updateEvidenciaViewer();
}

document.getElementById('modalEvidenciaViewer')?.addEventListener('click', function(e) { if (e.target === this) fecharEvidenciaViewer(); });
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modalEvidenciaViewer')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') fecharEvidenciaViewer();
    if (e.key === 'ArrowRight') evidenciaViewerNext();
    if (e.key === 'ArrowLeft') evidenciaViewerPrev();
});

document.body.addEventListener('click', function(e) {
    const thumb = e.target.closest('.evidencia-thumb');
    if (thumb && !e.target.closest('button')) {
        e.preventDefault();
        const idx = parseInt(thumb.dataset.evidenciaIndex, 10);
        if (!isNaN(idx)) openEvidenciaViewer(idx);
    }
});

function abrirModalDvrsSemEvidencia() {
    const dvrs = getDvrsSemEvidencia();
    const container = document.getElementById('modalDvrsSemEvidenciaLista');
    container.innerHTML = dvrs.map(d => `
        <div class="flex items-center justify-between border rounded p-3 bg-gray-50">
            <span class="font-medium">${(d.nome || '').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</span>
            <button type="button" class="btn-evidencia-modal-dvr px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700" data-dvr-id="${d.id}" data-dvr-nome="${(d.nome || '').replace(/"/g, '&quot;')}">
                <i class="fas fa-image mr-1"></i>Evidência
            </button>
        </div>
    `).join('');
    document.getElementById('modalDvrsSemEvidencia').classList.remove('hidden');
    container.querySelectorAll('.btn-evidencia-modal-dvr').forEach(btn => {
        btn.addEventListener('click', function() {
            fecharModalDvrsSemEvidencia();
            abrirModalEvidencia(parseInt(this.dataset.dvrId, 10), this.dataset.dvrNome);
        });
    });
}

function fecharModalDvrsSemEvidencia() {
    document.getElementById('modalDvrsSemEvidencia').classList.add('hidden');
}

document.getElementById('modalDvrsSemEvidencia')?.addEventListener('click', function(e) { if (e.target === this) fecharModalDvrsSemEvidencia(); });
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modalDvrsSemEvidencia')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') fecharModalDvrsSemEvidencia();
});

let anexoIdParaRemover = null;

function solicitarRemoverAnexo(anexoId) {
    anexoIdParaRemover = anexoId;
    document.getElementById('modalConfirmarRemoverEvidencia').classList.remove('hidden');
}

function fecharModalConfirmarRemoverEvidencia() {
    document.getElementById('modalConfirmarRemoverEvidencia').classList.add('hidden');
    anexoIdParaRemover = null;
}

function confirmarRemoverEvidencia() {
    if (!anexoIdParaRemover) return;
    const url = '{{ route("cameras.checklists.anexos.destroy", [$checklist, "ANEXO_ID"]) }}'.replace('ANEXO_ID', anexoIdParaRemover);
    fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } })
        .then(r => r.json()).then(d => { if (d.success) location.reload(); });
    fecharModalConfirmarRemoverEvidencia();
}

document.body.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-remover-evidencia');
    if (btn) {
        e.preventDefault();
        e.stopPropagation();
        solicitarRemoverAnexo(parseInt(btn.dataset.anexoId, 10));
    }
});

document.getElementById('modalConfirmarRemoverEvidencia')?.addEventListener('click', function(e) { if (e.target === this) fecharModalConfirmarRemoverEvidencia(); });
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modalConfirmarRemoverEvidencia')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') fecharModalConfirmarRemoverEvidencia();
});

function todosItensVerificados() {
    const rows = document.querySelectorAll('tr.checklist-item-row');
    if (rows.length === 0) return false;
    for (const row of rows) {
        const onlineChecked = row.querySelector('input.online-sim:checked') || row.querySelector('input.online-nao:checked');
        const anguloChecked = row.querySelector('input.angulo-sim:checked') || row.querySelector('input.angulo-nao:checked');
        const gravandoChecked = row.querySelector('input.gravando-sim:checked') || row.querySelector('input.gravando-nao:checked');
        if (!onlineChecked || !anguloChecked || !gravandoChecked) return false;
    }
    return true;
}

function atualizarEstadoBotaoFinalizar() {
    const btn = document.getElementById('btnFinalizarChecklist');
    if (!btn) return;
    btn.disabled = !todosItensVerificados();
}

function finalizarChecklist() {
    if (!todosItensVerificados()) {
        showToastErro('Conclua todas as verificações (Online?, Ângulo Correto?, Gravando?) em cada câmera antes de finalizar.');
        return;
    }
    const camerasSemAcao = getTodasCamerasComProblemaSemAcao(true);
    if (camerasSemAcao.length > 0) {
        abrirModalAcaoCorretiva(camerasSemAcao, null, null, null, 'finalizar');
        return;
    }
    const camerasSolicaoPendente = getCamerasAguardandoCorrecaoComTudoSimSemSolucao();
    if (camerasSolicaoPendente.length > 0) {
        const cam = camerasSolicaoPendente[0];
        window._finalizarAposSolucao = true;
        abrirModalSolucao(cam.itemId, cam.cameraId, cam.cameraNome, true);
        return;
    }
    const dvrsSemEvidencia = getDvrsSemEvidencia();
    if (dvrsSemEvidencia.length > 0) {
        abrirModalDvrsSemEvidencia();
        return;
    }
    document.getElementById('modalConfirmarFinalizar').classList.remove('hidden');
}

function fecharModalConfirmarFinalizar() {
    document.getElementById('modalConfirmarFinalizar').classList.add('hidden');
}

document.getElementById('modalConfirmarFinalizar')?.addEventListener('click', function(e) {
    if (e.target === this) fecharModalConfirmarFinalizar();
});
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('modalConfirmarFinalizar');
    if (modal && !modal.classList.contains('hidden') && e.key === 'Escape') fecharModalConfirmarFinalizar();
});

function confirmarFinalizarChecklist() {
    fecharModalConfirmarFinalizar();
    fetch('{{ route("cameras.checklists.finalizar", $checklist) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
        .then(() => window.location.href = '{{ route("cameras.index") }}');
}

function cancelarChecklist() {
    document.getElementById('modalConfirmarCancelar').classList.remove('hidden');
}

function fecharModalConfirmarCancelar() {
    document.getElementById('modalConfirmarCancelar').classList.add('hidden');
}

document.getElementById('modalConfirmarCancelar')?.addEventListener('click', function(e) {
    if (e.target === this) fecharModalConfirmarCancelar();
});
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('modalConfirmarCancelar');
    if (modal && !modal.classList.contains('hidden') && e.key === 'Escape') fecharModalConfirmarCancelar();
});

function confirmarCancelarChecklist() {
    fecharModalConfirmarCancelar();
    fetch('{{ route("cameras.checklists.cancelar", $checklist) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
        .then(() => window.location.href = '{{ route("cameras.index") }}');
}

let itemIdParaExcluirHistorico = null;
function abrirModalExcluirHistorico(itemId) {
    itemIdParaExcluirHistorico = itemId;
    document.getElementById('modalExcluirHistoricoSenha').value = '';
    document.getElementById('modalExcluirHistoricoErro').classList.add('hidden');
    document.getElementById('modalExcluirHistorico').classList.remove('hidden');
}
function fecharModalExcluirHistorico() {
    itemIdParaExcluirHistorico = null;
    document.getElementById('modalExcluirHistorico').classList.add('hidden');
}
function confirmarExcluirHistorico() {
    const senha = document.getElementById('modalExcluirHistoricoSenha').value;
    const erroEl = document.getElementById('modalExcluirHistoricoErro');
    if (!senha) {
        erroEl.textContent = 'Digite a senha.';
        erroEl.classList.remove('hidden');
        return;
    }
    const url = limparHistoricoBaseUrl + itemIdParaExcluirHistorico + '/limpar-historico';
    const fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('senha', senha);
    fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                fecharModalExcluirHistorico();
                const entrada = document.querySelector(`.problema-historico-entrada[data-entrada-id="${itemIdParaExcluirHistorico}"]`);
                if (entrada) {
                    const container = entrada.closest('.problema-historico-conteudo');
                    entrada.remove();
                    if (container && container.querySelectorAll('.problema-historico-entrada').length === 0) {
                        const row = container.closest('tr.problema-row');
                        if (row) row.remove();
                    }
                }
                if (typeof showToast === 'function') showToast('Histórico excluído.', 'success');
            } else {
                erroEl.textContent = d.message || 'Erro ao excluir.';
                erroEl.classList.remove('hidden');
            }
        })
        .catch(() => {
            erroEl.textContent = 'Erro na requisição.';
            erroEl.classList.remove('hidden');
        });
}
document.getElementById('modalExcluirHistorico')?.addEventListener('click', function(e) { if (e.target === this) fecharModalExcluirHistorico(); });
document.addEventListener('keydown', function(e) {
    const m = document.getElementById('modalExcluirHistorico');
    if (m && !m.classList.contains('hidden') && e.key === 'Escape') fecharModalExcluirHistorico();
});
</script>
@endsection
