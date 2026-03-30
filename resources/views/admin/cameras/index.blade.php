@extends('layouts.app')

@section('header')
    <x-page-header title="Gerenciar Câmeras e DVRs" icon="fas fa-video">
        <x-slot name="actions">
            <button onclick="openCreateDvrModal()" class="page-header-btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo DVR
            </button>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
    @php
        $camerasFlatList = [];
        $dvrFotosByDvrId = [];
        $dvrFotosViewerFlat = [];
        foreach ($dvrs ?? [] as $d) {
            $dvrFotosByDvrId[$d->id] = $d->fotos->map(fn($f) => [
                'fotoUrl' => asset('storage/' . $f->path),
                'data' => $f->created_at->format('d/m/Y H:i'),
                'dvrNome' => $d->nome,
            ])->values()->all();
            $ultimaFotoDvr = $d->fotos->sortByDesc(fn($f) => $f->created_at->timestamp)->first();
            if ($ultimaFotoDvr) {
                $dvrFotosViewerFlat[] = [
                    'dvrId' => $d->id,
                    'dvrNome' => $d->nome,
                    'fotoUrl' => asset('storage/' . $ultimaFotoDvr->path),
                    'data' => $ultimaFotoDvr->created_at->format('d/m/Y H:i'),
                ];
            }
            foreach ($d->cameras ?? [] as $c) {
                $camerasFlatList[] = [
                    'dvrId' => $d->id,
                    'dvrNome' => $d->nome,
                    'cameraId' => $c->id,
                    'cameraNome' => $c->nome,
                    'fotoUrl' => $c->foto ? asset('storage/' . $c->foto) : null,
                ];
            }
        }
    @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            {{-- DVRs com câmeras aninhadas (expansível) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">DVRs e Câmeras</h2>
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
                                    @php $viewerIdx = 0; @endphp
                                    @foreach($dvrs as $dvr)
                                        @php
                                            $dvrFotoHistArr = $dvr->fotos->map(fn($f) => [
                                                'id' => $f->id,
                                                'data' => $f->created_at->format('d/m/Y H:i'),
                                                'arquivo' => $f->original_filename ?: basename($f->path),
                                                'url' => asset('storage/' . $f->path),
                                                'dvrNome' => $dvr->nome,
                                            ])->values()->all();
                                        @endphp
                                        <tr data-dvr-id="{{ $dvr->id }}" class="bg-white hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                <button type="button" onclick="toggleDvrExpand({{ $dvr->id }})" class="text-gray-500 hover:text-gray-700 focus:outline-none" title="Expandir/recolher">
                                                    <i class="fas fa-chevron-right expand-icon transition-transform duration-200" id="expand-icon-{{ $dvr->id }}"></i>
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
                                                    <button type="button" onclick="openDvrFotoViewer({{ $dvr->id }})" class="cursor-pointer hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 rounded" title="Clique para visualizar (Próxima/Anterior troca de DVR)">
                                                        <img src="{{ asset('storage/' . $dvrThumbFoto->path) }}" alt="Foto do DVR {{ $dvr->nome }}" class="h-10 w-auto rounded border border-gray-300 object-cover">
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $dvr->status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ ucfirst($dvr->status) }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button type="button" class="btn-dvr-foto text-purple-600 hover:text-purple-900" title="Anexar foto do DVR" data-dvr-id="{{ $dvr->id }}" data-dvr-nome="{{ e($dvr->nome) }}"><i class="fas fa-camera"></i></button>
                                                    <button type="button" class="btn-historico-dvr text-indigo-600 hover:text-indigo-900" title="Histórico de fotos do DVR" data-dvr-id="{{ $dvr->id }}" data-dvr-nome="{{ e($dvr->nome) }}" data-historical="{{ base64_encode(json_encode($dvrFotoHistArr, JSON_UNESCAPED_UNICODE)) }}"><i class="fas fa-history"></i></button>
                                                    @if(!empty($dvr->acesso_web) && (str_starts_with($dvr->acesso_web, 'http://') || str_starts_with($dvr->acesso_web, 'https://')))
                                                        <a href="{{ e($dvr->acesso_web) }}" target="_blank" rel="noopener noreferrer" class="text-green-600 hover:text-green-900" title="Abrir acesso web do DVR"><i class="fas fa-globe"></i></a>
                                                    @endif
                                                    <button onclick="openEditDvrModal({{ $dvr->id }})" class="text-blue-600 hover:text-blue-900" title="Editar DVR"><i class="fas fa-edit"></i></button>
                                                    <button onclick="openToggleDvrModal({{ $dvr->id }})" class="text-amber-600 hover:text-amber-900" title="{{ $dvr->status === 'ativo' ? 'Inativar' : 'Ativar' }}"><i class="fas fa-{{ $dvr->status === 'ativo' ? 'pause' : 'play' }}"></i></button>
                                                    <button onclick="openDeleteDvrModal({{ $dvr->id }})" class="text-red-600 hover:text-red-900" title="Excluir DVR"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Linhas das câmeras (ocultas por padrão) --}}
                                        <tr data-dvr-cameras="{{ $dvr->id }}" class="dvr-cameras-row hidden bg-gray-50">
                                            <td colspan="7" class="px-6 py-0">
                                                <div class="border-l-4 border-blue-200 pl-4 py-3">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-sm font-medium text-gray-700">Câmeras do DVR {{ $dvr->nome }}</span>
                                                        <div class="flex gap-2">
                                                            <button type="button" onclick="triggerImportCameras({{ $dvr->id }})" class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700">
                                                                <i class="fas fa-folder-open mr-1"></i> Importar Câmeras
                                                            </button>
                                                            <button onclick="openCreateCameraModal({{ $dvr->id }})" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-xs font-medium rounded hover:bg-primary-700">
                                                                <i class="fas fa-plus mr-1"></i> Adicionar câmera
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="cameras-table-wrapper-{{ $dvr->id }}" class="{{ $dvr->cameras->count() > 0 ? '' : 'hidden' }}">
                                                        <div class="overflow-x-auto">
                                                            <table class="min-w-full text-sm">
                                                                <thead>
                                                                    <tr class="border-b border-gray-200">
                                                                        <th class="text-left py-2 pr-2 w-8 font-medium text-gray-600"></th>
                                                                        <th class="text-left py-2 pr-4 font-medium text-gray-600">Nome</th>
                                                                        <th class="text-left py-2 pr-4 font-medium text-gray-600">Canal</th>
                                                                        <th class="text-left py-2 pr-4 font-medium text-gray-600">Foto</th>
                                                                        <th class="text-left py-2 pr-4 font-medium text-gray-600">Status</th>
                                                                        <th class="text-right py-2 font-medium text-gray-600">Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="cameras-tbody-{{ $dvr->id }}" data-dvr-id="{{ $dvr->id }}" class="sortable-cameras">
                                                                    @foreach($dvr->cameras as $camera)
                                                                        <tr data-camera-id="{{ $camera->id }}" class="border-b border-gray-100 hover:bg-white">
                                                                            <td class="py-2 pr-2 w-8 text-gray-400" title="Arrastar para reordenar"><i class="fas fa-grip-vertical drag-handle"></i></td>
                                                                            <td class="py-2 pr-4 font-medium text-gray-900">{{ $camera->nome }}</td>
                                                                            <td class="py-2 pr-4 text-gray-600">{{ $camera->canal ?? '-' }}</td>
                                                                            <td class="py-2 pr-4">
                                                                                @if($camera->foto)
                                                                                    <button type="button" onclick="openCameraViewer({{ $viewerIdx }})" class="cursor-pointer hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 rounded" title="Clique para visualizar">
                                                                                        <img src="{{ asset('storage/' . $camera->foto) }}" alt="{{ $camera->nome }}" class="h-10 w-auto rounded border border-gray-300 object-cover">
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
                                                                                    <button type="button" class="btn-historico-camera text-indigo-600 hover:text-indigo-900" title="Histórico da Câmera" data-camera-nome="{{ e($camera->nome) }}" data-historical="{{ base64_encode(json_encode($histData)) }}"><i class="fas fa-history"></i></button>
                                                                                    @endif
                                                                                    <button onclick="openEditCameraModal({{ $camera->id }})" class="text-blue-600 hover:text-blue-900" title="Editar"><i class="fas fa-edit"></i></button>
                                                                                    <button onclick="openToggleCameraModal({{ $camera->id }})" class="text-amber-600 hover:text-amber-900" title="{{ $camera->status === 'ativo' ? 'Inativar' : 'Ativar' }}"><i class="fas fa-{{ $camera->status === 'ativo' ? 'pause' : 'play' }}"></i></button>
                                                                                    <button onclick="openDeleteCameraModal({{ $camera->id }})" class="text-red-600 hover:text-red-900" title="Excluir"><i class="fas fa-trash"></i></button>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        @php $viewerIdx++; @endphp
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <p id="cameras-empty-{{ $dvr->id }}" class="text-gray-500 text-sm py-2 {{ $dvr->cameras->count() > 0 ? 'hidden' : '' }}">Nenhuma câmera neste DVR. Clique em <strong>Adicionar câmera</strong> ou <strong>Importar Câmeras</strong> para cadastrar.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Nenhum DVR cadastrado. Clique em <strong>Novo DVR</strong> para começar.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Input oculto para importar câmeras (seleção de pasta) --}}
    <input type="file" id="importCamerasInput" class="hidden" webkitdirectory directory multiple accept="image/*" />

    {{-- Container Toast --}}
    <div id="toastContainer" class="toast-container"></div>

    {{-- Modal Novo DVR --}}
    <div id="createDvrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
        <div class="w-full max-w-lg shadow-lg rounded-md bg-white my-4">
            <div class="flex justify-between items-center mb-4 px-6 pt-4">
                <h3 class="text-lg font-medium text-gray-900">Novo DVR</h3>
                <button onclick="closeCreateDvrModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="modal-body px-6 pb-6" id="createDvrModalContent"></div>
        </div>
    </div>

    {{-- Modal Editar DVR --}}
    <div id="editDvrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
        <div class="w-full max-w-lg shadow-lg rounded-md bg-white my-4">
            <div class="flex justify-between items-center mb-4 px-6 pt-4">
                <h3 class="text-lg font-medium text-gray-900">Editar DVR</h3>
                <button onclick="closeEditDvrModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="modal-body px-6 pb-6" id="editDvrModalContent"></div>
        </div>
    </div>

    {{-- Modal Nova Câmera --}}
    <div id="createCameraModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
        <div class="w-full max-w-lg shadow-lg rounded-md bg-white my-4">
            <div class="flex justify-between items-center mb-4 px-6 pt-4">
                <h3 class="text-lg font-medium text-gray-900">Nova Câmera</h3>
                <button onclick="closeCreateCameraModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="modal-body px-6 pb-6" id="createCameraModalContent"></div>
        </div>
    </div>

    {{-- Modal Editar Câmera --}}
    <div id="editCameraModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
        <div class="w-full max-w-lg shadow-lg rounded-md bg-white my-4">
            <div class="flex justify-between items-center mb-4 px-6 pt-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Câmera</h3>
                <button onclick="closeEditCameraModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="modal-body px-6 pb-6" id="editCameraModalContent"></div>
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

    {{-- Modal visualizador de fotos do DVR (navega só entre fotos deste DVR) --}}
    <div id="dvrFotoViewerModal" class="fixed inset-0 bg-gray-900 bg-opacity-90 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 100000;">
        <div class="relative bg-white rounded-lg shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                <div>
                    <p id="dvrFotoViewerContextHint" class="text-xs font-medium text-indigo-600 mb-1 hidden">Histórico deste DVR</p>
                    <h3 class="text-sm font-medium text-gray-500">DVR</h3>
                    <p id="dvrFotoViewerDvrName" class="text-lg font-semibold text-gray-900"></p>
                    <h3 class="text-sm font-medium text-gray-500 mt-2">Data da foto</h3>
                    <p id="dvrFotoViewerDataLabel" class="text-base font-medium text-gray-800"></p>
                </div>
                <button type="button" onclick="closeDvrFotoViewer()" class="text-gray-400 hover:text-gray-600 p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="flex-1 flex items-center justify-center p-6 bg-gray-100 min-h-[300px]">
                <div id="dvrFotoViewerImageWrap" class="relative">
                    <img id="dvrFotoViewerImage" src="" alt="" class="max-w-full max-h-[60vh] object-contain rounded-lg shadow-lg">
                </div>
            </div>
            <div class="flex justify-between items-center p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                <button type="button" id="dvrFotoViewerBtnPrev" onclick="dvrFotoViewerPrev()" title="Anterior" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                    <i class="fas fa-chevron-left mr-2"></i>Anterior
                </button>
                <span id="dvrFotoViewerCounter" class="text-sm text-gray-600"></span>
                <button type="button" id="dvrFotoViewerBtnNext" onclick="dvrFotoViewerNext()" title="Próxima" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                    Próxima<i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal anexar foto do DVR --}}
    <div id="dvrFotoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
        <div class="w-full max-w-md shadow-lg rounded-md bg-white my-4">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-camera text-purple-600 mr-2"></i>Anexar foto do DVR</h3>
                <button type="button" onclick="closeDvrFotoModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-gray-600 mb-4">DVR: <span id="dvrFotoModalDvrNome" class="font-medium text-gray-900"></span></p>
                <form id="dvrFotoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="dvrFotoInput" class="block text-sm font-medium text-gray-700 mb-1">Imagem</label>
                        <input type="file" name="foto" id="dvrFotoInput" accept="image/jpeg,image/png,image/gif,image/webp" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" required>
                        <p class="text-xs text-gray-500 mt-1">JPEG, PNG, GIF ou WebP, até 5 MB. Cada envio fica no histórico.</p>
                    </div>
                    <div id="dvrFotoFormError" class="text-sm text-red-600 mb-3 hidden"></div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeDvrFotoModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</button>
                        <button type="submit" id="dvrFotoSubmitBtn" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal confirmar exclusão de foto do histórico do DVR --}}
    <div id="excluirDvrFotoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 100001;">
        <div class="w-full max-w-md shadow-lg rounded-md bg-white my-4">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-trash-alt mr-2 text-red-600"></i>Remover foto do histórico</h3>
                <button type="button" onclick="closeExcluirDvrFotoModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-gray-600 mb-4">Esta ação apaga o arquivo e o registro. Digite a senha para confirmar:</p>
                <input type="password" id="excluirDvrFotoSenha" placeholder="Senha" class="block w-full border-gray-300 rounded-md shadow-sm mb-4" autocomplete="off">
                <p id="excluirDvrFotoErro" class="text-red-500 text-sm mb-4 hidden"></p>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeExcluirDvrFotoModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="button" onclick="confirmarExcluirDvrFoto()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium">Remover</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal histórico de fotos do DVR --}}
    <div id="historicoDvrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99990;">
        <div class="w-full max-w-2xl shadow-lg rounded-md bg-white my-4 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-images text-indigo-600 mr-2"></i><span id="historicoDvrModalTitulo">Fotos do DVR</span></h3>
                <button type="button" onclick="closeHistoricoDvrModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <ul id="historicoDvrModalLista" class="space-y-4"></ul>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                <button type="button" onclick="closeHistoricoDvrModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Fechar</button>
            </div>
        </div>
    </div>

    {{-- Modal Histórico de Problemas da Câmera --}}
    <div id="historicoCameraModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
        <div class="w-full max-w-2xl shadow-lg rounded-md bg-white my-4 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900"><i class="fas fa-history text-indigo-600 mr-2"></i><span id="historicoCameraModalTitulo">Histórico da Câmera</span></h3>
                <button type="button" onclick="closeHistoricoCameraModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <ul id="historicoCameraModalLista" class="space-y-3"></ul>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                <button type="button" onclick="closeHistoricoCameraModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Fechar</button>
            </div>
        </div>
    </div>

    {{-- Modal Confirmação Exclusão --}}
    <div id="deleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center p-4" style="z-index: 99999;">
        <div class="w-full max-w-sm shadow-lg rounded-md bg-white my-4">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Confirmar Exclusão</h3>
                <p class="text-sm text-gray-500 mt-2 mb-6 text-center" id="deleteConfirmMessage">Tem certeza que deseja excluir?</p>
                <div class="flex justify-center space-x-3">
                    <button onclick="closeDeleteConfirmModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancelar</button>
                    <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-open { overflow: hidden; }
        .toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 99999; pointer-events: none; }
        .toast { background: white; border-radius: 0.5rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); padding: 1rem 1.5rem; margin-bottom: 0.75rem; min-width: 300px; border-left: 4px solid; display: flex; align-items: center; gap: 0.75rem; pointer-events: auto; }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast.success { border-left-color: #10b981; }
        .toast.error { border-left-color: #ef4444; }
        .dvr-cameras-row td { border-bottom: 1px solid #e5e7eb; }
        .expand-icon.expanded { transform: rotate(90deg); }
        .sortable-ghost { opacity: 0.4; background: #e5e7eb; }
        .drag-handle { cursor: grab; }
        .drag-handle:active { cursor: grabbing; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const baseUrl = '{{ url("/admin/cameras") }}';
        const camerasList = @json($camerasFlatList);
        const dvrFotosByDvrId = @json($dvrFotosByDvrId);
        const dvrFotosViewerFlat = @json($dvrFotosViewerFlat);

        let cameraViewerCurrentIndex = 0;
        let dvrFotoViewerIndex = 0;
        let dvrFotoViewerMode = 'cross';
        let dvrFotoViewerHistoricoList = [];
        let historicoDvrContext = { dvrId: null, nome: '', historical: [] };
        let excluirDvrFotoContext = { dvrId: null, fotoId: null };

        function dvrFotoViewerActiveList() {
            return dvrFotoViewerMode === 'historico' ? dvrFotoViewerHistoricoList : dvrFotosViewerFlat;
        }

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

        function openDvrFotoViewer(dvrId) {
            dvrFotoViewerMode = 'cross';
            dvrFotoViewerHistoricoList = [];
            const hint = document.getElementById('dvrFotoViewerContextHint');
            if (hint) hint.classList.add('hidden');
            const idx = dvrFotosViewerFlat.findIndex(function (e) { return e.dvrId === dvrId || e.dvrId === Number(dvrId); });
            if (idx < 0 || !dvrFotosViewerFlat.length) return;
            dvrFotoViewerIndex = idx;
            updateDvrFotoViewerContent();
            document.getElementById('dvrFotoViewerModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function openDvrFotoHistoricoViewer(historicalArr, startIndex, dvrNomeFallback) {
            const arr = Array.isArray(historicalArr) ? historicalArr : [];
            if (!arr.length) return;
            dvrFotoViewerHistoricoList = arr.map(function (it) {
                return {
                    dvrNome: it.dvrNome || dvrNomeFallback || '',
                    data: it.data || '—',
                    fotoUrl: it.fotoUrl || it.url || '',
                    id: it.id,
                };
            });
            dvrFotoViewerMode = 'historico';
            dvrFotoViewerIndex = Math.max(0, Math.min(startIndex || 0, dvrFotoViewerHistoricoList.length - 1));
            const hint = document.getElementById('dvrFotoViewerContextHint');
            if (hint) hint.classList.remove('hidden');
            updateDvrFotoViewerContent();
            document.getElementById('dvrFotoViewerModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function closeDvrFotoViewer() {
            dvrFotoViewerMode = 'cross';
            dvrFotoViewerHistoricoList = [];
            const hint = document.getElementById('dvrFotoViewerContextHint');
            if (hint) hint.classList.add('hidden');
            document.getElementById('dvrFotoViewerModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function updateDvrFotoViewerContent() {
            const list = dvrFotoViewerActiveList();
            const item = list[dvrFotoViewerIndex];
            if (!item) return;
            document.getElementById('dvrFotoViewerDvrName').textContent = item.dvrNome || '';
            document.getElementById('dvrFotoViewerDataLabel').textContent = item.data || '—';
            const imgEl = document.getElementById('dvrFotoViewerImage');
            imgEl.src = item.fotoUrl || item.url || '';
            imgEl.alt = 'Foto do DVR';
            document.getElementById('dvrFotoViewerCounter').textContent = (dvrFotoViewerIndex + 1) + ' / ' + list.length;
            const prevBtn = document.getElementById('dvrFotoViewerBtnPrev');
            const nextBtn = document.getElementById('dvrFotoViewerBtnNext');
            if (dvrFotoViewerMode === 'historico') {
                if (prevBtn) { prevBtn.title = 'Foto anterior'; }
                if (nextBtn) { nextBtn.title = 'Próxima foto'; }
            } else {
                if (prevBtn) { prevBtn.title = 'DVR anterior'; }
                if (nextBtn) { nextBtn.title = 'Próximo DVR'; }
            }
        }

        function dvrFotoViewerNext() {
            const list = dvrFotoViewerActiveList();
            const n = list.length;
            if (n <= 1) return;
            dvrFotoViewerIndex = (dvrFotoViewerIndex + 1) % n;
            updateDvrFotoViewerContent();
        }

        function dvrFotoViewerPrev() {
            const list = dvrFotoViewerActiveList();
            const n = list.length;
            if (n <= 1) return;
            dvrFotoViewerIndex = dvrFotoViewerIndex <= 0 ? n - 1 : dvrFotoViewerIndex - 1;
            updateDvrFotoViewerContent();
        }

        document.getElementById('dvrFotoViewerModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeDvrFotoViewer();
        });

        document.addEventListener('keydown', function(e) {
            const camM = document.getElementById('cameraViewerModal');
            const dvrVM = document.getElementById('dvrFotoViewerModal');
            if (!camM.classList.contains('hidden')) {
                if (e.key === 'Escape') closeCameraViewer();
                if (e.key === 'ArrowRight') cameraViewerNext();
                if (e.key === 'ArrowLeft') cameraViewerPrev();
                return;
            }
            if (!dvrVM.classList.contains('hidden')) {
                if (e.key === 'Escape') { closeDvrFotoViewer(); return; }
                if (e.key === 'ArrowRight') { dvrFotoViewerNext(); return; }
                if (e.key === 'ArrowLeft') { dvrFotoViewerPrev(); return; }
            }
        });

        function openHistoricoCameraModal(nome, historical) {
            document.getElementById('historicoCameraModalTitulo').textContent = 'Histórico da Câmera – ' + nome;
            const lista = document.getElementById('historicoCameraModalLista');
            lista.innerHTML = '';
            const arr = Array.isArray(historical) ? historical : (typeof historical === 'string' ? JSON.parse(historical || '[]') : []);
            if (arr.length === 0) {
                lista.innerHTML = '<li class="text-gray-500">Nenhum registro de problema.</li>';
            } else {
                arr.forEach(function(e) {
                    const li = document.createElement('li');
                    li.className = 'border-l-4 border-amber-400 pl-3 py-1 bg-amber-50 rounded-r text-sm';
                    let html = '<span class="text-gray-600 font-medium">[' + (e.data || '-') + ']</span> ';
                    html += '<span class="text-amber-800">Problema:</span> ' + (e.problema || '-') + ' <span class="text-amber-800">| Ação:</span> ' + (e.acao || '-');
                    if (e.solucao) {
                        html += '<br><span class="text-green-700 font-medium mt-1 block">Solução aplicada: ' + e.solucao + '</span>';
                    }
                    li.innerHTML = html;
                    lista.appendChild(li);
                });
            }
            document.getElementById('historicoCameraModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function closeHistoricoCameraModal() {
            document.getElementById('historicoCameraModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-historico-camera');
            if (!btn) return;
            const nome = btn.getAttribute('data-camera-nome') || 'Câmera';
            const raw = btn.getAttribute('data-historical');
            let historical = [];
            try {
                if (raw) historical = JSON.parse(atob(raw) || '[]');
            } catch (_) {}
            openHistoricoCameraModal(nome, historical);
        });

        document.getElementById('historicoCameraModal')?.addEventListener('click', function(ev) {
            if (ev.target === this) closeHistoricoCameraModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key !== 'Escape') return;
            if (!document.getElementById('excluirDvrFotoModal').classList.contains('hidden')) {
                closeExcluirDvrFotoModal();
                return;
            }
            if (!document.getElementById('dvrFotoViewerModal').classList.contains('hidden')) {
                closeDvrFotoViewer();
                return;
            }
            if (!document.getElementById('historicoCameraModal').classList.contains('hidden')) {
                closeHistoricoCameraModal();
                return;
            }
            if (!document.getElementById('historicoDvrModal').classList.contains('hidden')) {
                closeHistoricoDvrModal();
                return;
            }
            if (!document.getElementById('dvrFotoModal').classList.contains('hidden')) {
                closeDvrFotoModal();
            }
        });

        let currentDvrFotoId = null;

        function openDvrFotoModal(id, nome) {
            currentDvrFotoId = id;
            document.getElementById('dvrFotoModalDvrNome').textContent = nome || '';
            document.getElementById('dvrFotoForm').reset();
            document.getElementById('dvrFotoFormError').classList.add('hidden');
            document.getElementById('dvrFotoFormError').textContent = '';
            document.getElementById('dvrFotoModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function closeDvrFotoModal() {
            currentDvrFotoId = null;
            document.getElementById('dvrFotoModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-dvr-foto');
            if (!btn) return;
            const id = parseInt(btn.getAttribute('data-dvr-id'), 10);
            const nome = btn.getAttribute('data-dvr-nome') || '';
            openDvrFotoModal(id, nome);
        });

        document.getElementById('dvrFotoForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const errEl = document.getElementById('dvrFotoFormError');
            errEl.classList.add('hidden');
            errEl.textContent = '';
            if (!currentDvrFotoId) return;
            const submitBtn = document.getElementById('dvrFotoSubmitBtn');
            submitBtn.disabled = true;
            const fd = new FormData(this);
            try {
                const r = await fetch(baseUrl + '/dvrs/' + currentDvrFotoId + '/fotos', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: fd,
                });
                const data = await r.json().catch(() => ({}));
                if (r.ok && data.success !== false) {
                    showToast(data.message || 'Foto do DVR anexada com sucesso.');
                    closeDvrFotoModal();
                    location.reload();
                    return;
                }
                let msg = data.message || 'Não foi possível enviar a foto.';
                if (data.errors) {
                    msg = Object.values(data.errors).flat().join(' ') || msg;
                }
                errEl.textContent = msg;
                errEl.classList.remove('hidden');
            } catch (_) {
                errEl.textContent = 'Erro de rede. Tente novamente.';
                errEl.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
            }
        });

        document.getElementById('dvrFotoModal')?.addEventListener('click', function(ev) {
            if (ev.target === this) closeDvrFotoModal();
        });

        function openHistoricoDvrModal(nome, historical, dvrId) {
            const arr = Array.isArray(historical) ? historical : (typeof historical === 'string' ? JSON.parse(historical || '[]') : []);
            historicoDvrContext = { dvrId: dvrId != null && dvrId !== '' ? parseInt(dvrId, 10) : null, nome: nome || '', historical: arr };
            document.getElementById('historicoDvrModalTitulo').textContent = 'Fotos do DVR – ' + (nome || '');
            const lista = document.getElementById('historicoDvrModalLista');
            lista.innerHTML = '';
            if (arr.length === 0) {
                lista.innerHTML = '<li class="text-gray-500 text-sm">Nenhuma foto anexada ainda. Use o ícone de câmera nas ações do DVR.</li>';
            } else {
                arr.forEach(function(item, index) {
                    const li = document.createElement('li');
                    li.className = 'flex gap-4 items-start border border-gray-200 rounded-lg p-3 bg-gray-50';
                    let url = item.url || '';
                    try {
                        const u = new URL(url, window.location.origin);
                        if (u.protocol !== 'http:' && u.protocol !== 'https:' && u.protocol !== window.location.protocol) url = '';
                    } catch (_) { url = ''; }
                    const btnThumb = document.createElement('button');
                    btnThumb.type = 'button';
                    btnThumb.className = 'flex-shrink-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded cursor-pointer hover:opacity-90';
                    btnThumb.title = 'Ampliar e navegar pelo histórico deste DVR';
                    const img = document.createElement('img');
                    img.src = url || '';
                    img.alt = '';
                    img.className = 'h-20 w-auto max-w-[120px] object-cover rounded border border-gray-300 pointer-events-none';
                    btnThumb.appendChild(img);
                    btnThumb.addEventListener('click', function() {
                        openDvrFotoHistoricoViewer(arr, index, nome);
                    });
                    const wrap = document.createElement('div');
                    wrap.className = 'text-sm min-w-0 flex-1';
                    const span = document.createElement('span');
                    span.className = 'text-gray-600 font-medium';
                    span.textContent = '[' + (item.data || '-') + ']';
                    const p = document.createElement('p');
                    p.className = 'text-gray-800 mt-1 break-words';
                    p.textContent = item.arquivo || 'foto';
                    const linkOpen = document.createElement('a');
                    linkOpen.href = url || '#';
                    linkOpen.target = '_blank';
                    linkOpen.rel = 'noopener noreferrer';
                    linkOpen.className = 'text-indigo-600 hover:underline text-xs mt-1 inline-block';
                    linkOpen.textContent = 'Abrir em nova aba';
                    wrap.appendChild(span);
                    wrap.appendChild(p);
                    wrap.appendChild(linkOpen);
                    const actions = document.createElement('div');
                    actions.className = 'flex flex-col gap-1 flex-shrink-0';
                    const btnDel = document.createElement('button');
                    btnDel.type = 'button';
                    btnDel.className = 'p-2 text-red-600 hover:bg-red-50 rounded';
                    btnDel.title = 'Remover do histórico';
                    btnDel.innerHTML = '<i class="fas fa-trash-alt"></i>';
                    btnDel.addEventListener('click', function() {
                        if (historicoDvrContext.dvrId && item.id) {
                            openExcluirDvrFotoModal(historicoDvrContext.dvrId, item.id);
                        }
                    });
                    actions.appendChild(btnDel);
                    li.appendChild(btnThumb);
                    li.appendChild(wrap);
                    li.appendChild(actions);
                    lista.appendChild(li);
                });
            }
            document.getElementById('historicoDvrModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function closeHistoricoDvrModal() {
            document.getElementById('historicoDvrModal').classList.add('hidden');
            document.body.classList.remove('modal-open');
        }

        function openExcluirDvrFotoModal(dvrId, fotoId) {
            excluirDvrFotoContext = { dvrId: dvrId, fotoId: fotoId };
            document.getElementById('excluirDvrFotoSenha').value = '';
            document.getElementById('excluirDvrFotoErro').classList.add('hidden');
            document.getElementById('excluirDvrFotoModal').classList.remove('hidden');
        }

        function closeExcluirDvrFotoModal() {
            excluirDvrFotoContext = { dvrId: null, fotoId: null };
            document.getElementById('excluirDvrFotoModal').classList.add('hidden');
        }

        function confirmarExcluirDvrFoto() {
            const { dvrId, fotoId } = excluirDvrFotoContext;
            const erroEl = document.getElementById('excluirDvrFotoErro');
            const senha = document.getElementById('excluirDvrFotoSenha').value;
            if (!dvrId || !fotoId) {
                erroEl.textContent = 'Dados inválidos.';
                erroEl.classList.remove('hidden');
                return;
            }
            if (!senha) {
                erroEl.textContent = 'Digite a senha.';
                erroEl.classList.remove('hidden');
                return;
            }
            erroEl.classList.add('hidden');
            const fd = new FormData();
            fd.append('_token', csrfToken || '');
            fd.append('_method', 'DELETE');
            fd.append('senha', senha);
            fetch(baseUrl + '/dvrs/' + dvrId + '/fotos/' + fotoId, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            })
            .then(function(r) {
                return r.json().then(function(data) { return { ok: r.ok, data: data }; }).catch(function() { return { ok: false, data: {} }; });
            })
            .then(function(res) {
                if (res.ok && res.data.success !== false) {
                    closeExcluirDvrFotoModal();
                    showToast(res.data.message || 'Foto removida.');
                    window.location.reload();
                } else {
                    erroEl.textContent = (res.data && res.data.message) ? res.data.message : 'Não foi possível remover.';
                    erroEl.classList.remove('hidden');
                }
            })
            .catch(function() {
                erroEl.textContent = 'Erro de rede.';
                erroEl.classList.remove('hidden');
            });
        }

        document.getElementById('excluirDvrFotoModal')?.addEventListener('click', function(ev) {
            if (ev.target === this) closeExcluirDvrFotoModal();
        });

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-historico-dvr');
            if (!btn) return;
            const nome = btn.getAttribute('data-dvr-nome') || 'DVR';
            const dvrId = btn.getAttribute('data-dvr-id');
            const raw = btn.getAttribute('data-historical');
            let historical = [];
            try {
                if (raw) historical = JSON.parse(atob(raw) || '[]');
            } catch (_) {}
            openHistoricoDvrModal(nome, historical, dvrId);
        });

        document.getElementById('historicoDvrModal')?.addEventListener('click', function(ev) {
            if (ev.target === this) closeHistoricoDvrModal();
        });

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast show ${type}`;
            toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'times-circle'}"></i><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }

        // --- DVR Modals ---
        function openCreateDvrModal() {
            document.getElementById('createDvrModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            fetch(baseUrl + '/dvrs/create', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('createDvrModalContent').innerHTML = data.html;
                    document.getElementById('createDvrModalContent').querySelector('form')?.addEventListener('submit', function(e) {
                        e.preventDefault();
                        submitDvrForm(this, baseUrl + '/dvrs', 'POST');
                    });
                })
                .catch(err => { console.error(err); showToast('Erro ao carregar formulário', 'error'); });
        }
        function closeCreateDvrModal() { document.getElementById('createDvrModal').classList.add('hidden'); document.body.classList.remove('modal-open'); }

        function openEditDvrModal(id) {
            document.getElementById('editDvrModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            fetch(baseUrl + '/dvrs/' + id + '/edit', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('editDvrModalContent').innerHTML = data.html;
                    const form = document.getElementById('editDvrModalContent').querySelector('form');
                    form?.addEventListener('submit', function(e) {
                        e.preventDefault();
                        submitDvrForm(this, baseUrl + '/dvrs/' + id, 'PUT');
                    });
                })
                .catch(err => { console.error(err); showToast('Erro ao carregar formulário', 'error'); });
        }
        function closeEditDvrModal() { document.getElementById('editDvrModal').classList.add('hidden'); document.body.classList.remove('modal-open'); }

        function submitDvrForm(form, url, method) {
            const fd = new FormData(form);
            if (method === 'PUT') fd.append('_method', 'PUT');
            fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.ok ? r.json() : r.json().then(j => { throw new Error(j.message || j.errors ? Object.values(j.errors || {}).flat().join(', ') : 'Erro'); }).catch(e => { if (e.message) throw e; throw new Error('Erro ao salvar'); }))
            .then(data => {
                if (data.success) { showToast(data.message); closeCreateDvrModal(); closeEditDvrModal(); setTimeout(() => location.reload(), 800); }
                else showToast(data.message || 'Erro', 'error');
            })
            .catch(e => showToast(e.message || 'Erro ao salvar', 'error'));
        }

        function openDeleteDvrModal(id) {
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
            document.getElementById('deleteConfirmMessage').textContent = 'Tem certeza que deseja excluir este DVR? Todas as câmeras vinculadas também serão removidas.';
            document.getElementById('confirmDeleteBtn').onclick = () => confirmDeleteDvr(id);
        }
        function confirmDeleteDvr(id) {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
            fetch(baseUrl + '/dvrs/' + id, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    document.querySelector(`tr[data-dvr-id="${id}"]`)?.remove();
                    document.querySelector(`tr[data-dvr-cameras="${id}"]`)?.remove();
                } else showToast('Erro ao excluir', 'error');
            })
            .catch(() => showToast('Erro ao excluir', 'error'));
        }

        function toggleDvrExpand(dvrId) {
            const row = document.querySelector(`tr[data-dvr-cameras="${dvrId}"]`);
            const icon = document.getElementById(`expand-icon-${dvrId}`);
            if (row && icon) {
                row.classList.toggle('hidden');
                icon.classList.toggle('expanded');
            }
        }

        let importDvrId = null;
        function triggerImportCameras(dvrId) {
            importDvrId = dvrId;
            document.getElementById('importCamerasInput').click();
        }
        const IMPORT_CHUNK_SIZE = 20; // PHP max_file_uploads padrão
        document.getElementById('importCamerasInput').addEventListener('change', function(e) {
            const files = Array.from(e.target.files || []);
            e.target.value = '';
            if (!importDvrId || files.length === 0) return;
            const imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'];
            const images = files.filter(f => imageTypes.includes(f.type));
            if (images.length === 0) {
                showToast('Nenhuma imagem encontrada na pasta.', 'error');
                return;
            }
            if (images.length > 40) {
                showToast('Máximo 40 fotos por vez. Selecione até 40 imagens.', 'error');
                return;
            }
            showToast('Importando ' + images.length + ' câmera(s)...', 'success');
            const chunks = [];
            for (let i = 0; i < images.length; i += IMPORT_CHUNK_SIZE) {
                chunks.push(images.slice(i, i + IMPORT_CHUNK_SIZE));
            }
            (async function() {
                let totalImportadas = 0;
                for (const chunk of chunks) {
                    const fd = new FormData();
                    fd.append('dvr_id', importDvrId);
                    chunk.forEach(f => fd.append('fotos[]', f));
                    const r = await fetch(baseUrl + '/dvrs/' + importDvrId + '/import-cameras', {
                        method: 'POST',
                        body: fd,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                    });
                    const data = await r.json();
                    if (!data.success) { showToast(data.message || 'Erro ao importar', 'error'); return; }
                    totalImportadas += (data.importadas || chunk.length);
                }
                showToast((totalImportadas === 1 ? '1 câmera' : totalImportadas + ' câmeras') + ' importada(s) com sucesso.');
                const expanded = Array.from(document.querySelectorAll('tr[data-dvr-cameras]')).filter(tr => !tr.classList.contains('hidden')).map(tr => tr.dataset.dvrCameras);
                sessionStorage.setItem('adminCamerasExpanded', JSON.stringify(expanded));
                setTimeout(() => location.reload(), 800);
            })();
        });

        // Restaurar DVRs expandidos após reload (ex.: após importar câmeras)
        (function() {
            try {
                const expanded = JSON.parse(sessionStorage.getItem('adminCamerasExpanded') || '[]');
                expanded.forEach(id => { toggleDvrExpand(id); });
                sessionStorage.removeItem('adminCamerasExpanded');
            } catch (e) {}
        })();

        // Inicializar Sortable para reordenação de câmeras
        document.querySelectorAll('.sortable-cameras').forEach(tbody => {
            const dvrId = tbody.dataset.dvrId;
            if (typeof Sortable !== 'undefined' && dvrId) {
                new Sortable(tbody, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onEnd: function() {
                        const ids = Array.from(tbody.querySelectorAll('tr[data-camera-id]')).map(tr => parseInt(tr.dataset.cameraId, 10));
                        fetch(baseUrl + '/dvrs/' + dvrId + '/reorder-cameras', {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' },
                            body: JSON.stringify({ camera_ids: ids })
                        })
                        .then(r => r.json())
                        .then(data => { if (data.success) showToast('Ordem salva.'); })
                        .catch(() => showToast('Erro ao salvar ordem.', 'error'));
                    }
                });
            }
        });

        function openToggleDvrModal(id) {
            fetch(baseUrl + '/dvrs/' + id + '/toggle-status', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { showToast('Status atualizado'); setTimeout(() => location.reload(), 800); }
            })
            .catch(() => showToast('Erro ao alterar status', 'error'));
        }

        // --- Camera Modals ---
        function openCreateCameraModal(dvrId) {
            document.getElementById('createCameraModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            const url = dvrId ? baseUrl + '/cameras/create?dvr_id=' + dvrId : baseUrl + '/cameras/create';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('createCameraModalContent').innerHTML = data.html;
                    document.getElementById('createCameraModalContent').querySelector('form')?.addEventListener('submit', function(e) {
                        e.preventDefault();
                        submitCameraForm(this, baseUrl + '/cameras', 'POST');
                    });
                })
                .catch(err => { console.error(err); showToast('Erro ao carregar formulário', 'error'); });
        }
        function closeCreateCameraModal() { document.getElementById('createCameraModal').classList.add('hidden'); document.body.classList.remove('modal-open'); }

        function openEditCameraModal(id) {
            document.getElementById('editCameraModal').classList.remove('hidden');
            document.body.classList.add('modal-open');
            fetch(baseUrl + '/cameras/' + id + '/edit', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('editCameraModalContent').innerHTML = data.html;
                    document.getElementById('editCameraModalContent').querySelector('form')?.addEventListener('submit', function(e) {
                        e.preventDefault();
                        submitCameraForm(this, baseUrl + '/cameras/' + id, 'PUT');
                    });
                })
                .catch(err => { console.error(err); showToast('Erro ao carregar formulário', 'error'); });
        }
        function closeEditCameraModal() { document.getElementById('editCameraModal').classList.add('hidden'); document.body.classList.remove('modal-open'); }

        function submitCameraForm(form, url, method) {
            const fd = new FormData(form);
            if (method === 'PUT') fd.append('_method', 'PUT');
            fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.ok ? r.json() : r.json().then(j => { throw new Error(j.message || j.errors ? Object.values(j.errors || {}).flat().join(', ') : 'Erro'); }).catch(e => { if (e.message) throw e; throw new Error('Erro ao salvar'); }))
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    closeCreateCameraModal();
                    closeEditCameraModal();
                    if (data.camera) {
                        if (method === 'PUT') updateCameraRow(data.camera);
                        else {
                            const dvrId = form.querySelector('[name="dvr_id"]')?.value;
                            if (dvrId) addCameraRow(parseInt(dvrId), data.camera);
                        }
                    }
                } else showToast(data.message || 'Erro', 'error');
            })
            .catch(e => showToast(e.message || 'Erro ao salvar', 'error'));
        }

        function updateCameraRow(cam) {
            const tr = document.querySelector(`tr[data-camera-id="${cam.id}"]`);
            if (!tr) return;
            tr.querySelector('td:nth-child(2)').textContent = cam.nome;
            tr.querySelector('td:nth-child(3)').textContent = cam.canal || '-';
            const fotoTd = tr.querySelector('td:nth-child(4)');
            fotoTd.innerHTML = cam.foto ? '<img src="{{ rtrim(asset("storage"), "/") }}/' + cam.foto + '" alt="" class="h-10 w-auto rounded border border-gray-300 object-cover">' : '<span class="text-gray-400">-</span>';
            const statusSpan = tr.querySelector('td:nth-child(5) span');
            statusSpan.textContent = cam.status === 'ativo' ? 'Ativo' : 'Inativo';
            statusSpan.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' + (cam.status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800');
            const toggleBtn = tr.querySelector('button[onclick*="openToggleCameraModal"]');
            if (toggleBtn) {
                toggleBtn.setAttribute('onclick', 'openToggleCameraModal(' + cam.id + ')');
                toggleBtn.setAttribute('title', cam.status === 'ativo' ? 'Inativar' : 'Ativar');
                toggleBtn.innerHTML = '<i class="fas fa-' + (cam.status === 'ativo' ? 'pause' : 'play') + '"></i>';
            }
        }

        function addCameraRow(dvrId, cam) {
            const tbody = document.getElementById('cameras-tbody-' + dvrId);
            const emptyEl = document.getElementById('cameras-empty-' + dvrId);
            const wrapper = document.getElementById('cameras-table-wrapper-' + dvrId);
            if (!tbody) return;
            const fotoHtml = cam.foto ? '<img src="{{ rtrim(asset("storage"), "/") }}/' + cam.foto + '" alt="" class="h-10 w-auto rounded border border-gray-300 object-cover">' : '<span class="text-gray-400">-</span>';
            const statusClass = cam.status === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
            const tr = document.createElement('tr');
            tr.dataset.cameraId = cam.id;
            tr.className = 'border-b border-gray-100 hover:bg-white';
            tr.innerHTML = '<td class="py-2 pr-2 w-8 text-gray-400" title="Arrastar para reordenar"><i class="fas fa-grip-vertical drag-handle"></i></td><td class="py-2 pr-4 font-medium text-gray-900">' + (cam.nome || '').replace(/</g, '&lt;') + '</td><td class="py-2 pr-4 text-gray-600">' + (cam.canal || '-') + '</td><td class="py-2 pr-4">' + fotoHtml + '</td><td class="py-2 pr-4"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' + statusClass + '">' + (cam.status === 'ativo' ? 'Ativo' : 'Inativo') + '</span></td><td class="py-2 text-right"><div class="flex justify-end space-x-2"><button onclick="openEditCameraModal(' + cam.id + ')" class="text-blue-600 hover:text-blue-900" title="Editar"><i class="fas fa-edit"></i></button><button onclick="openToggleCameraModal(' + cam.id + ')" class="text-amber-600 hover:text-amber-900" title="' + (cam.status === 'ativo' ? 'Inativar' : 'Ativar') + '"><i class="fas fa-' + (cam.status === 'ativo' ? 'pause' : 'play') + '"></i></button><button onclick="openDeleteCameraModal(' + cam.id + ')" class="text-red-600 hover:text-red-900" title="Excluir"><i class="fas fa-trash"></i></button></div></td>';
            tbody.appendChild(tr);
            if (wrapper) wrapper.classList.remove('hidden');
            if (emptyEl) emptyEl.classList.add('hidden');
            updateDvrCameraCount(dvrId, 1);
        }

        function updateDvrCameraCount(dvrId, delta) {
            const dvrRow = document.querySelector('tr[data-dvr-id="' + dvrId + '"]');
            if (!dvrRow) return;
            const span = dvrRow.querySelector('td:nth-child(4) span');
            if (!span) return;
            const m = span.textContent.match(/(\d+)\s*câmeras/);
            if (m) {
                const n = Math.max(0, parseInt(m[1], 10) + delta);
                span.textContent = n + ' câmera' + (n !== 1 ? 's' : '');
            }
        }

        function openDeleteCameraModal(id) {
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
            document.getElementById('deleteConfirmMessage').textContent = 'Tem certeza que deseja excluir esta câmera?';
            document.getElementById('confirmDeleteBtn').onclick = () => confirmDeleteCamera(id);
        }
        function confirmDeleteCamera(id) {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
            fetch(baseUrl + '/cameras/' + id, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    const tr = document.querySelector('tr[data-camera-id="' + id + '"]');
                    if (tr) {
                        const tbody = tr.closest('tbody');
                        const dvrId = tbody ? tbody.dataset.dvrId : null;
                        tr.remove();
                        if (tbody && tbody.querySelectorAll('tr[data-camera-id]').length === 0) {
                            const wrapper = document.getElementById('cameras-table-wrapper-' + dvrId);
                            const emptyEl = document.getElementById('cameras-empty-' + dvrId);
                            if (wrapper) wrapper.classList.add('hidden');
                            if (emptyEl) emptyEl.classList.remove('hidden');
                        }
                        if (dvrId) updateDvrCameraCount(dvrId, -1);
                    }
                } else showToast('Erro ao excluir', 'error');
            })
            .catch(() => showToast('Erro ao excluir', 'error'));
        }
        function closeDeleteConfirmModal() { document.getElementById('deleteConfirmModal').classList.add('hidden'); }

        function openToggleCameraModal(id) {
            fetch(baseUrl + '/cameras/' + id + '/toggle-status', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Status atualizado');
                    const tr = document.querySelector('tr[data-camera-id="' + id + '"]');
                    if (tr) {
                        const statusSpan = tr.querySelector('td:nth-child(5) span');
                        const newStatus = data.status;
                        statusSpan.textContent = newStatus === 'ativo' ? 'Ativo' : 'Inativo';
                        statusSpan.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ' + (newStatus === 'ativo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800');
                        const toggleBtn = tr.querySelector('button[onclick*="openToggleCameraModal"]');
                        if (toggleBtn) {
                            toggleBtn.setAttribute('title', newStatus === 'ativo' ? 'Inativar' : 'Ativar');
                            toggleBtn.innerHTML = '<i class="fas fa-' + (newStatus === 'ativo' ? 'pause' : 'play') + '"></i>';
                        }
                    }
                }
            })
            .catch(() => showToast('Erro ao alterar status', 'error'));
        }
    </script>
@endsection
