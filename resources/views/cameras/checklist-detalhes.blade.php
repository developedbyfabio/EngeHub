@extends('layouts.app')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <i class="fas fa-clipboard-check mr-2" style="color: #E9B32C;"></i>
            Detalhes do Checklist — {{ $mostrarTodosDvrs ?? false ? 'Todos os DVRs' : ($checklist->dvrs->count() > 0 ? $checklist->dvrs->pluck('nome')->join(', ') : ($checklist->dvr?->nome ?? '')) }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">
            Responsável: {{ $checklist->responsavel }} | Iniciado em {{ $checklist->iniciado_em->format('d/m/Y H:i') }}
            @if($checklist->finalizado_em)
                | Finalizado em {{ $checklist->finalizado_em->format('d/m/Y H:i') }}
            @endif
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('cameras.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
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
@endphp
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evidências</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $viewerIdx = 0; @endphp
                            @foreach($itensPorDvr as $dvrId => $itens)
                                @php
                                    $dvr = $itens->first()->camera->dvr;
                                    $dvrIndex = $loop->index;
                                @endphp
                                <tr data-dvr-id="{{ $dvr->id }}" data-dvr-index="{{ $dvrIndex }}" class="hover:bg-gray-50 checklist-dvr-row">
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
                                        @php $anexosDvr = $checklist->anexos->where('dvr_id', $dvr->id); @endphp
                                        @if($anexosDvr->count() > 0)
                                            <div class="grid grid-cols-3 gap-2 w-52">
                                                @foreach($anexosDvr as $anexo)
                                                <div class="relative">
                                                    <img src="{{ asset('storage/' . $anexo->caminho_arquivo) }}" alt="Evidência" class="h-14 w-full rounded border object-cover cursor-pointer evidencia-thumb aspect-video" data-dvr-id="{{ $dvr->id }}" data-dvr-nome="{{ addslashes($dvr->nome) }}" data-url="{{ asset('storage/' . $anexo->caminho_arquivo) }}" data-anexo-id="{{ $anexo->id }}" data-evidencia-index="{{ $evidenciaIndexMap[$anexo->id] ?? 0 }}" title="Clique para ampliar">
                                                </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr data-dvr-cameras="{{ $dvr->id }}" class="dvr-cameras-row hidden bg-gray-50">
                                    <td colspan="5" class="px-6 py-0">
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
                                                            <th class="text-left py-2 font-medium text-gray-600">Observação / Problema / Solução</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($itens as $item)
                                                        <tr data-item-id="{{ $item->id }}" class="border-b border-gray-100 hover:bg-white">
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
                                                                <span class="text-gray-700">
                                                                    @if($item->online === true)
                                                                        <span class="text-green-600 font-medium">Sim</span>
                                                                    @elseif($item->online === false)
                                                                        <span class="text-red-600 font-medium">Não</span>
                                                                    @else
                                                                        <span class="text-gray-400">-</span>
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td class="py-2 pr-4">
                                                                <span class="text-gray-700">
                                                                    @if($item->angulo_correto === true)
                                                                        <span class="text-green-600 font-medium">Sim</span>
                                                                    @elseif($item->angulo_correto === false)
                                                                        <span class="text-red-600 font-medium">Não</span>
                                                                    @else
                                                                        <span class="text-gray-400">-</span>
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td class="py-2 pr-4">
                                                                <span class="text-gray-700">
                                                                    @if($item->gravando === true)
                                                                        <span class="text-green-600 font-medium">Sim</span>
                                                                    @elseif($item->gravando === false)
                                                                        <span class="text-red-600 font-medium">Não</span>
                                                                    @else
                                                                        <span class="text-gray-400">-</span>
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td class="py-2">
                                                                @php
                                                                        $temProblemaNesteChecklist = $item->problema || $item->descricao_problema || $item->acao_corretiva_necessaria;
                                                                        $anexosSolucao = $checklist->anexos->where('camera_id', $item->camera_id);
                                                                    @endphp
                                                                @if($item->observacao || $temProblemaNesteChecklist || $item->acao_corretiva_realizada || $anexosSolucao->isNotEmpty())
                                                                    <div class="space-y-2 text-xs max-w-xs">
                                                                        @if($item->observacao)
                                                                            <p class="text-gray-700"><strong>Obs:</strong> {{ $item->observacao }}</p>
                                                                        @endif
                                                                        @if($temProblemaNesteChecklist)
                                                                            <div class="border-l-2 border-amber-400 pl-2 bg-amber-50 rounded-r py-1">
                                                                                <p class="text-amber-800">
                                                                                    <strong>Problema:</strong> {{ $item->descricao_problema ?? '-' }}
                                                                                </p>
                                                                                <p class="text-amber-800 mt-0.5">
                                                                                    <strong>Ação:</strong> {{ $item->acao_corretiva_necessaria ?? '-' }}
                                                                                </p>
                                                                            </div>
                                                                        @endif
                                                                        @if($item->acao_corretiva_realizada || $anexosSolucao->isNotEmpty())
                                                                            <div class="border-l-2 border-green-300 pl-2 bg-green-50 rounded-r py-1">
                                                                                <p class="text-green-800 font-medium"><i class="fas fa-check-circle mr-1"></i>Solução aplicada</p>
                                                                                @if($item->acao_corretiva_realizada)
                                                                                <p class="text-green-700 mt-0.5">{{ $item->acao_corretiva_realizada }}</p>
                                                                                @endif
                                                                                @if($anexosSolucao->isNotEmpty())
                                                                                <div class="flex flex-wrap gap-1 mt-2">
                                                                                    @foreach($anexosSolucao as $ax)
                                                                                    @php $evIdx = $evidenciaIndexMap[$ax->id] ?? 0; @endphp
                                                                                    <img src="{{ asset('storage/' . $ax->caminho_arquivo) }}" alt="Evidência solução" class="h-12 w-auto rounded border border-green-200 object-cover cursor-pointer hover:opacity-90 hover:ring-2 hover:ring-green-400 transition evidencia-thumb evidencia-solucao-thumb" title="Clique para ampliar" data-evidencia-index="{{ $evIdx }}" data-dvr-id="{{ $dvr->id }}" data-dvr-nome="{{ addslashes($dvr->nome) }}" data-url="{{ asset('storage/' . $ax->caminho_arquivo) }}" data-anexo-id="{{ $ax->id }}">
                                                                                    @endforeach
                                                                                </div>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span class="text-gray-400">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
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

<style>
.expand-icon.expanded { transform: rotate(90deg); }
.dvr-cameras-row td { border-bottom: 1px solid #e5e7eb; }
</style>

<script>
const camerasList = @json($camerasFlatList ?? []);
const evidenciaList = @json($evidenciaFlatList ?? []);
const dvrIds = @json(array_keys($itensPorDvr->toArray() ?? []));

let cameraViewerCurrentIndex = 0;
let evidenciaViewerIndex = 0;

const STORAGE_KEY = 'engehub_checklist_detalhes_{{ $checklist->id }}_expanded_dvrs';

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
    row.classList.toggle('hidden');
    icon.classList.toggle('expanded');
    saveExpandedDvrs();
}

document.addEventListener('DOMContentLoaded', function() {
    restoreExpandedDvrs();
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
document.addEventListener('keydown', function(e) {
    if (document.getElementById('cameraViewerModal')?.classList.contains('hidden')) return;
    if (e.key === 'Escape') closeCameraViewer();
    if (e.key === 'ArrowRight') cameraViewerNext();
    if (e.key === 'ArrowLeft') cameraViewerPrev();
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
    if (thumb) {
        e.preventDefault();
        const idx = parseInt(thumb.dataset.evidenciaIndex, 10);
        if (!isNaN(idx)) openEvidenciaViewer(idx);
    }
});
</script>
@endsection
