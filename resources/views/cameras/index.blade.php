@extends('layouts.app')

@section('header')
    <x-page-header title="Câmeras" icon="fas fa-video">
        <x-slot name="actions">
            @if($canUseCameraChecklist ?? false)
            <button type="button" onclick="openIniciarChecklistModal()" class="page-header-btn-primary mr-2">
                <i class="fas fa-play mr-2"></i>
                Iniciar Novo Checklist
            </button>
            @php $checklistsAndamentoCount = $checklistsEmAndamento->count(); @endphp
            <button type="button" onclick="openChecklistsEmAndamentoModal()" class="relative page-header-btn-secondary mr-2 pr-3 overflow-visible">
                <i class="fas fa-sync-alt mr-2"></i>
                Em andamento
                @if($checklistsAndamentoCount > 0)
                    <span class="checklist-andamento-badge">{{ $checklistsAndamentoCount }}</span>
                @endif
            </button>
            @endif
            <button type="button" onclick="openHistoricoChecklistsModal()" class="page-header-btn-secondary mr-2">
                <i class="fas fa-history mr-2"></i>
                Checklists
            </button>
            @if(auth()->user()?->canAccessNav(\App\Support\NavPermission::ADMIN_CAMERAS) ?? false)
                <a href="{{ route('admin.cameras.index') }}" class="page-header-btn-secondary">
                    <i class="fas fa-cog mr-2"></i>
                    Gerenciar Câmeras
                </a>
            @endif
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        @if(session('success') || request()->query('success'))
            <div class="p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') ?? request()->query('success') }}
            </div>
        @endif

        @include('cameras.partials.dvrs-e-cameras-consulta', ['dvrs' => $dvrs, 'problemaHistoricoPorCamera' => $problemaHistoricoPorCamera])

    </div>
</div>

@push('body-modals')
        @if($canUseCameraChecklist ?? false)
        {{-- Modal Iniciar Novo Checklist (único) — z-index acima da navbar (z-40) e fora de main --}}
        {{-- Modal Checklists em Andamento --}}
        <div id="modalChecklistsEmAndamento" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10045] flex items-center justify-center p-4">
            <div class="w-full max-w-2xl shadow-lg rounded-md bg-white max-h-[90vh] flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-sync-alt mr-2 text-amber-500"></i>
                        Checklists em Andamento
                    </h3>
                    <button type="button" onclick="closeChecklistsEmAndamentoModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="px-6 py-4 overflow-y-auto flex-1 min-h-0">
                    <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-4">
                        <i class="fas fa-info-circle mr-1"></i>
                        Checklists não finalizados após <strong>9 horas</strong> do início são excluídos automaticamente do sistema.
                    </p>
                    @if($checklistsEmAndamento->count() > 0)
                        <ul id="checklists-em-andamento" class="space-y-3">
                            @foreach($checklistsEmAndamento as $c)
                                <li class="flex items-center justify-between gap-4 p-3 rounded-lg border border-gray-100 hover:bg-gray-50">
                                    <a href="{{ route('cameras.checklists.show', $c) }}" class="inline-flex items-center text-blue-600 hover:underline font-medium flex-1 min-w-0">
                                        @php $totalDvrs = $dvrs->count(); $mostrarTodos = $totalDvrs > 0 && $c->dvrs->count() >= $totalDvrs; $dvrNomes = $mostrarTodos ? 'Todos os DVRs' : ($c->dvrs->count() > 0 ? $c->dvrs->pluck('nome')->join(', ') : ($c->dvr?->nome ?? '-')); @endphp
                                        <span class="truncate">{{ $dvrNomes }} — iniciado {{ $c->iniciado_em->format('d/m/Y H:i') }}</span>
                                        <i class="fas fa-arrow-right ml-2 text-sm flex-shrink-0"></i>
                                    </a>
                                    <button type="button" onclick="abandonarChecklist(this)" data-cancel-url="{{ route('cameras.checklists.cancelar', $c) }}" data-dvr-nome="{{ e($dvrNomes) }}" class="text-red-600 hover:text-red-800 text-sm font-medium whitespace-nowrap flex-shrink-0" title="Abandonar checklist">
                                        <i class="fas fa-times-circle mr-1"></i> Abandonar
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <p class="text-sm text-gray-500 mt-3">Clique na linha para continuar de onde parou.</p>
                    @else
                        <p class="text-gray-500 py-2">Nenhum checklist em andamento.</p>
                    @endif
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex-shrink-0">
                    <button type="button" onclick="closeChecklistsEmAndamentoModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Fechar</button>
                </div>
            </div>
        </div>

        <div id="iniciarChecklistModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10040] flex items-center justify-center">
            <div class="w-11/12 md:w-2/3 max-w-2xl shadow-lg rounded-md bg-white m-4">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-play mr-2 text-green-600"></i>
                        Iniciar Novo Checklist
                    </h3>
                    <button type="button" onclick="closeIniciarChecklistModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="px-6 pb-6">
                    <form action="{{ route('cameras.checklists.store') }}" method="POST" id="formIniciarChecklist" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-2">DVR e Câmeras *</label>
                            <p class="text-xs text-gray-500 mb-2">Selecione os DVRs. Clique na seta para escolher câmeras específicas.</p>
                            <div class="space-y-1 max-h-80 overflow-y-auto border border-gray-200 rounded-md p-3 bg-gray-50">
                                <label class="flex items-center cursor-pointer hover:bg-gray-100 p-2 rounded -m-1">
                                    <input type="checkbox" id="dvr_todos" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" onchange="toggleTodosDvrs(this)">
                                    <span class="ml-2 font-medium text-gray-900">Todos os DVRs</span>
                                </label>
                                <hr class="border-gray-200">
                                @foreach($dvrs as $dvr)
                                <div class="dvr-block" data-dvr-id="{{ $dvr->id }}">
                                    <div class="flex items-center gap-1 p-2 rounded -m-1 hover:bg-gray-100">
                                        <button type="button" class="expand-dvr-btn text-gray-500 hover:text-gray-700 p-0.5" data-dvr-id="{{ $dvr->id }}" title="Expandir para selecionar câmeras">
                                            <i class="fas fa-chevron-right expand-dvr-icon transition-transform duration-200"></i>
                                        </button>
                                        <label class="flex-1 cursor-pointer flex items-center">
                                            <input type="checkbox" name="dvr_ids[]" value="{{ $dvr->id }}" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dvr-checkbox">
                                            <span class="ml-2 text-gray-800">{{ $dvr->nome }} ({{ $dvr->cameras_count ?? $dvr->cameras->count() }} câmeras)</span>
                                        </label>
                                    </div>
                                    <div class="dvr-cameras-list hidden pl-6 pr-2 pb-2 space-y-1 ml-6 border-l-2 border-gray-200 mt-1" data-dvr-id="{{ $dvr->id }}">
                                        @foreach($dvr->cameras ?? [] as $cam)
                                        <label class="flex items-center cursor-pointer hover:bg-gray-100 p-2 rounded -m-1 camera-checkbox-label" data-dvr-id="{{ $dvr->id }}">
                                            <input type="checkbox" name="camera_ids[]" value="{{ $cam->id }}" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 camera-checkbox" data-dvr-id="{{ $dvr->id }}">
                                            <span class="ml-2 text-sm {{ $cam->status === 'aguardando_correcao' ? 'text-amber-700 font-medium' : 'text-gray-700' }}">{{ $cam->nome }}</span>
                                            @if($cam->status === 'aguardando_correcao')
                                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-amber-100 text-amber-800">Aguardando correção</span>
                                            @endif
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <p id="dvr_ids_error" class="text-red-500 text-xs mt-1 hidden">Selecione ao menos um DVR.</p>
                        </div>
                        <div>
                            <label for="modal_responsavel_nome" class="block font-medium text-sm text-gray-700">Responsável</label>
                            <input type="text" id="modal_responsavel_nome" name="responsavel_nome" value="{{ auth()->user()?->name ?? auth()->guard('system')->user()?->name ?? 'Operador' }}"
                                   class="block mt-1 w-full border-gray-300 bg-gray-100 rounded-md shadow-sm cursor-not-allowed" readonly>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" onclick="closeIniciarChecklistModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium">
                                <i class="fas fa-play mr-2"></i> Iniciar Checklist
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Confirmação Abandonar Checklist --}}
        {{-- z-index acima dos modais que o abrem (em andamento 10045, histórico 10050); abaixo dos viewers de foto (10100+) --}}
        <div id="modalConfirmarAbandonar" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10150] flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-900">Abandonar Checklist</h3>
                        <p id="modalAbandonarMensagem" class="text-sm text-gray-600 mt-0.5">Deseja realmente abandonar este checklist? O progresso será perdido.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="fecharModalConfirmarAbandonar()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Não, manter</button>
                    <button type="button" id="btnConfirmarAbandonar" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Sim, abandonar</button>
                </div>
            </div>
        </div>
        @endif

        {{-- Modal Histórico de Checklists Finalizados --}}
        <div id="modalHistoricoChecklists" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10050] flex items-center justify-center p-4">
            <div class="w-full max-w-6xl shadow-lg rounded-md bg-white my-4 max-h-[92vh] flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-history mr-2 text-blue-600"></i>
                        Histórico de Checklists Finalizados
                    </h3>
                    <button type="button" onclick="closeHistoricoChecklistsModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="flex-1 overflow-y-auto px-6 py-4">
                    <div class="flex flex-wrap gap-4 mb-6">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">Período:</label>
                            <select id="histChecklistPeriod" class="border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="today" {{ ($period ?? 'week') == 'today' ? 'selected' : '' }}>Hoje</option>
                                <option value="week" {{ ($period ?? 'week') == 'week' ? 'selected' : '' }}>Últimos 7 dias</option>
                                <option value="month" {{ ($period ?? '') == 'month' ? 'selected' : '' }}>Últimos 30 dias</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">DVR:</label>
                            <select id="histChecklistDvr" class="border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Todos</option>
                                @foreach($dvrs ?? [] as $d)
                                    <option value="{{ $d->id }}" {{ ($dvrFilter ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" onclick="aplicarFiltrosHistoricoChecklists()" class="px-3 py-1.5 bg-gray-700 text-white text-sm rounded-md hover:bg-gray-800">Aplicar</button>
                    </div>

                    @if(isset($checklistsFinalizados) && $checklistsFinalizados->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">DVR</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Início</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fim</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Responsável</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Online</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Problema</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($checklistsFinalizados as $c)
                                        @php
                                            $online = $c->itens->filter(fn($i) => $i->online === true || $i->status_operacional === 'online')->count();
                                            $problema = $c->itens->where('problema', true)->count();
                                            $totalDvrsAtivosHist = ($dvrs ?? collect())->count();
                                            $mostrarTodosHist = $totalDvrsAtivosHist > 0 && $c->dvrs->count() >= $totalDvrsAtivosHist;
                                            $dvrNomesHist = $mostrarTodosHist
                                                ? 'Todos os DVRs'
                                                : ($c->dvrs->count() > 0 ? $c->dvrs->pluck('nome')->join(', ') : ($c->dvr?->nome ?? '-'));
                                        @endphp
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $dvrNomesHist }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $c->iniciado_em->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $c->finalizado_em?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $c->responsavel }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $c->itens->count() }}</td>
                                            <td class="px-4 py-3 text-sm"><span class="text-green-600 font-medium">{{ $online }}</span></td>
                                            <td class="px-4 py-3 text-sm"><span class="text-red-600 font-medium">{{ $problema }}</span></td>
                                            <td class="px-4 py-3 text-sm text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('cameras.checklists.detalhes', $c) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded" title="Detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($c->finalizado_em)
                                                        <a href="{{ route('cameras.checklists.pdf', $c) }}" target="_blank" class="p-2 text-red-600 hover:bg-red-50 rounded" title="Visualizar PDF">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                        <a href="{{ route('cameras.checklists.pdf.download', $c) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded" title="Baixar PDF">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    @if($canUseCameraChecklist ?? false)
                                                    <button type="button"
                                                        class="p-2 text-red-700 hover:bg-red-50 rounded btn-apagar-um-historico"
                                                        title="Apagar histórico"
                                                        data-apagar-url="{{ route('cameras.checklists.apagar-historico', $c) }}"
                                                        data-dvr-rotulo="{{ e(\Illuminate\Support\Str::limit($dvrNomesHist, 80)) }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">Nenhum checklist finalizado no período selecionado.</p>
                    @endif
                </div>
                <div class="px-6 py-4 border-t border-gray-200 flex-shrink-0">
                    <button type="button" onclick="closeHistoricoChecklistsModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Fechar</button>
                </div>
            </div>
        </div>

        {{-- Modal Apagar um checklist do histórico --}}
        <div id="apagarHistoricoItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10150] flex items-center justify-center">
            <div class="w-11/12 md:w-96 shadow-lg rounded-md bg-white m-4">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-trash-alt mr-2 text-red-600"></i>
                        Apagar histórico
                    </h3>
                    <button type="button" onclick="closeApagarHistoricoItemModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="px-6 pb-6">
                    <p class="text-sm text-gray-600 mb-4">Remove <strong>somente este</strong> checklist do histórico (incluindo anexos). Os demais registros não são alterados. Digite a senha para confirmar:</p>
                    <p id="apagarHistoricoItemRotulo" class="text-xs text-gray-500 mb-2"></p>
                    <input type="password" id="apagarHistoricoItemSenha" placeholder="Senha de confirmação" class="block w-full border-gray-300 rounded-md shadow-sm mb-4" autocomplete="off">
                    <p id="apagarHistoricoItemErro" class="text-red-500 text-sm mb-4 hidden"></p>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeApagarHistoricoItemModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</button>
                        <button type="button" onclick="confirmarApagarHistoricoItem()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium">
                            <i class="fas fa-trash-alt mr-2"></i> Apagar
                        </button>
                    </div>
                </div>
            </div>
        </div>

@include('cameras.partials.dvr-consulta-modals')
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('formIniciarChecklist')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const selected = form.querySelectorAll('input[name="dvr_ids[]"]:checked');
        if (selected.length === 0) {
            document.getElementById('dvr_ids_error').classList.remove('hidden');
            return false;
        }
        selected.forEach(dvrCb => {
            const dvrId = dvrCb.value;
            const list = document.querySelector(`.dvr-cameras-list[data-dvr-id="${dvrId}"]`);
            if (list && list.classList.contains('hidden')) {
                document.querySelectorAll(`.camera-checkbox[data-dvr-id="${dvrId}"]`).forEach(cc => cc.checked = true);
            }
        });
        document.getElementById('dvr_ids_error').classList.add('hidden');
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json().catch(() => ({})))
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.reload();
            }
        })
        .catch(() => window.location.reload());
        return false;
    });

    document.querySelectorAll('.dvr-checkbox').forEach(cb => cb.addEventListener('change', sincronizarCheckboxTodos));
    document.querySelectorAll('.expand-dvr-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const dvrId = this.dataset.dvrId;
            const list = document.querySelector(`.dvr-cameras-list[data-dvr-id="${dvrId}"]`);
            const icon = this.querySelector('.expand-dvr-icon');
            if (list && !list.classList.contains('hidden')) {
                list.classList.add('hidden');
                if (icon) icon.classList.remove('rotate-90');
            } else if (list) {
                list.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-90');
            }
        });
    });
    document.querySelectorAll('.dvr-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const dvrId = this.value;
            if (this.checked) {
                document.querySelectorAll(`.camera-checkbox[data-dvr-id="${dvrId}"]`).forEach(cc => cc.checked = true);
            } else {
                document.querySelectorAll(`.camera-checkbox[data-dvr-id="${dvrId}"]`).forEach(cc => cc.checked = false);
            }
        });
    });
    document.querySelectorAll('.camera-checkbox').forEach(cc => {
        cc.addEventListener('change', function() {
            const dvrId = this.dataset.dvrId;
            if (this.checked && dvrId) {
                const dvrCb = document.querySelector(`input.dvr-checkbox[value="${dvrId}"]`);
                if (dvrCb && !dvrCb.checked) dvrCb.checked = true;
                sincronizarCheckboxTodos();
            }
        });
    });

    @if(!empty($checklistsModalOpen))
    openHistoricoChecklistsModal();
    @endif

    document.getElementById('modalHistoricoChecklists')?.addEventListener('click', function(e) {
        if (e.target === this) closeHistoricoChecklistsModal();
    });
    document.getElementById('modalChecklistsEmAndamento')?.addEventListener('click', function(e) {
        if (e.target === this) closeChecklistsEmAndamentoModal();
    });
    document.getElementById('apagarHistoricoItemModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeApagarHistoricoItemModal();
    });

    document.getElementById('modalConfirmarAbandonar')?.addEventListener('click', function(e) {
        if (e.target === this) fecharModalConfirmarAbandonar();
    });
    document.getElementById('btnConfirmarAbandonar')?.addEventListener('click', function() {
        const { url, btn } = _abandonarContext;
        if (!url) return;
        fecharModalConfirmarAbandonar();
        if (btn) btn.disabled = true;
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const fd = new FormData();
        fd.append('_token', csrf);
        fetch(url, {
            method: 'POST',
            body: fd,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
        })
            .then(r => r.json().catch(() => ({})))
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect || '{{ route("cameras.index") }}';
                } else {
                    alert(data.message || 'Erro ao abandonar.');
                    if (btn) btn.disabled = false;
                }
            })
            .catch(() => {
                alert('Erro ao abandonar.');
                if (btn) btn.disabled = false;
            });
    });
    document.addEventListener('keydown', function escAbandonar(e) {
        const modal = document.getElementById('modalConfirmarAbandonar');
        if (modal && !modal.classList.contains('hidden') && e.key === 'Escape') fecharModalConfirmarAbandonar();
    });
});

function toggleTodosDvrs(checkbox) {
    document.querySelectorAll('.dvr-checkbox').forEach(cb => {
        cb.checked = checkbox.checked;
        const dvrId = cb.value;
        if (checkbox.checked) {
            document.querySelectorAll(`.camera-checkbox[data-dvr-id="${dvrId}"]`).forEach(cc => cc.checked = true);
        } else {
            document.querySelectorAll(`.camera-checkbox[data-dvr-id="${dvrId}"]`).forEach(cc => cc.checked = false);
        }
    });
}

function sincronizarCheckboxTodos() {
    const todos = document.querySelectorAll('.dvr-checkbox');
    const todosChecked = document.getElementById('dvr_todos');
    const allChecked = Array.from(todos).every(cb => cb.checked);
    todosChecked.checked = allChecked && todos.length > 0;
}

function openIniciarChecklistModal() {
    document.getElementById('iniciarChecklistModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    document.getElementById('dvr_todos').checked = true;
    document.querySelectorAll('.dvr-checkbox').forEach(cb => {
        cb.checked = true;
        const dvrId = cb.value;
        document.querySelectorAll(`.camera-checkbox[data-dvr-id="${dvrId}"]`).forEach(cc => cc.checked = true);
    });
    document.querySelectorAll('.dvr-cameras-list').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.expand-dvr-icon').forEach(el => el.classList.remove('rotate-90'));
    document.getElementById('dvr_ids_error').classList.add('hidden');
}
function closeIniciarChecklistModal() {
    document.getElementById('iniciarChecklistModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openChecklistsEmAndamentoModal() {
    document.getElementById('modalChecklistsEmAndamento').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}
function closeChecklistsEmAndamentoModal() {
    document.getElementById('modalChecklistsEmAndamento').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openHistoricoChecklistsModal() {
    document.getElementById('modalHistoricoChecklists').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}
function closeHistoricoChecklistsModal() {
    document.getElementById('modalHistoricoChecklists').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}
function aplicarFiltrosHistoricoChecklists() {
    const period = document.getElementById('histChecklistPeriod')?.value || 'week';
    const dvrId = document.getElementById('histChecklistDvr')?.value || '';
    const params = new URLSearchParams();
    params.set('period', period);
    if (dvrId) params.set('dvr_id', dvrId);
    params.set('checklists_open', '1');
    window.location.href = '{{ route("cameras.index") }}?' + params.toString();
}

let _apagarUmHistoricoUrl = null;

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-apagar-um-historico');
    if (!btn || !btn.dataset.apagarUrl) return;
    _apagarUmHistoricoUrl = btn.dataset.apagarUrl;
    const rotulo = btn.dataset.dvrRotulo || '';
    document.getElementById('apagarHistoricoItemRotulo').textContent = rotulo ? 'Checklist: ' + rotulo : '';
    document.getElementById('apagarHistoricoItemSenha').value = '';
    document.getElementById('apagarHistoricoItemErro').classList.add('hidden');
    document.getElementById('apagarHistoricoItemModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
});

function closeApagarHistoricoItemModal() {
    _apagarUmHistoricoUrl = null;
    document.getElementById('apagarHistoricoItemModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function confirmarApagarHistoricoItem() {
    const erroEl = document.getElementById('apagarHistoricoItemErro');
    const senha = document.getElementById('apagarHistoricoItemSenha').value;
    if (!_apagarUmHistoricoUrl) {
        erroEl.textContent = 'Nenhum checklist selecionado.';
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
    fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    fd.append('senha', senha);
    fetch(_apagarUmHistoricoUrl, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeApagarHistoricoItemModal();
            if (data.message) {
                const u = new URL(window.location.href);
                u.searchParams.set('success', data.message);
                u.searchParams.set('checklists_open', '1');
                window.location.href = u.toString();
            } else {
                window.location.href = '{{ route("cameras.index") }}?checklists_open=1';
            }
        } else {
            erroEl.textContent = data.message || 'Não foi possível apagar.';
            erroEl.classList.remove('hidden');
        }
    })
    .catch(() => {
        erroEl.textContent = 'Erro ao apagar.';
        erroEl.classList.remove('hidden');
    });
}

document.addEventListener('keydown', function(e) {
    const m = document.getElementById('apagarHistoricoItemModal');
    if (m && !m.classList.contains('hidden') && e.key === 'Escape') closeApagarHistoricoItemModal();
});
document.addEventListener('keydown', function(e) {
    const mh = document.getElementById('modalHistoricoChecklists');
    if (e.key === 'Escape' && mh && !mh.classList.contains('hidden')) closeHistoricoChecklistsModal();
});
document.addEventListener('keydown', function(e) {
    const ma = document.getElementById('modalChecklistsEmAndamento');
    if (e.key === 'Escape' && ma && !ma.classList.contains('hidden')) closeChecklistsEmAndamentoModal();
});

let _abandonarContext = { url: null, btn: null };

function abandonarChecklist(btn) {
    const url = btn.dataset.cancelUrl;
    const nome = btn.dataset.dvrNome || 'este checklist';
    if (!url) return;
    _abandonarContext = { url, btn };
    document.getElementById('modalAbandonarMensagem').textContent = 'Deseja realmente abandonar o checklist "' + nome + '"? O progresso será perdido.';
    document.getElementById('modalConfirmarAbandonar').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function fecharModalConfirmarAbandonar() {
    document.getElementById('modalConfirmarAbandonar').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    _abandonarContext = { url: null, btn: null };
}
</script>

<script>
(function() {
    const camerasListConsulta = @json($camerasFlatList ?? []);
    const dvrFotosViewerFlatConsulta = @json($dvrFotosViewerFlat ?? []);

    let cameraViewerCurrentIndexConsulta = 0;
    let dvrFotoViewerIndexConsulta = 0;
    let dvrFotoViewerModeConsulta = 'cross';
    let dvrFotoViewerHistoricoListConsulta = [];

    function dvrFotoViewerActiveListConsulta() {
        return dvrFotoViewerModeConsulta === 'historico' ? dvrFotoViewerHistoricoListConsulta : dvrFotosViewerFlatConsulta;
    }

    window.toggleDvrExpandConsulta = function(dvrId) {
        const row = document.querySelector(`tr[data-dvr-cameras-consulta="${dvrId}"]`);
        const icon = document.getElementById(`expand-icon-consulta-${dvrId}`);
        if (!row) return;
        row.classList.toggle('hidden');
        if (icon) icon.classList.toggle('expanded');
    };

    window.openCameraViewerConsulta = function(index) {
        if (index < 0 || index >= camerasListConsulta.length) return;
        cameraViewerCurrentIndexConsulta = index;
        updateCameraViewerConsulta();
        document.getElementById('cameraViewerModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };
    window.closeCameraViewerConsulta = function() {
        document.getElementById('cameraViewerModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };
    function updateCameraViewerConsulta() {
        const item = camerasListConsulta[cameraViewerCurrentIndexConsulta];
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
        document.getElementById('cameraViewerCounter').textContent = (cameraViewerCurrentIndexConsulta + 1) + ' / ' + camerasListConsulta.length;
    }
    window.cameraViewerNextConsulta = function() {
        cameraViewerCurrentIndexConsulta = (cameraViewerCurrentIndexConsulta + 1) % camerasListConsulta.length;
        updateCameraViewerConsulta();
    };
    window.cameraViewerPrevConsulta = function() {
        cameraViewerCurrentIndexConsulta = cameraViewerCurrentIndexConsulta <= 0 ? camerasListConsulta.length - 1 : cameraViewerCurrentIndexConsulta - 1;
        updateCameraViewerConsulta();
    };

    document.getElementById('cameraViewerModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeCameraViewerConsulta();
    });

    window.openDvrFotoViewerConsulta = function(dvrId) {
        dvrFotoViewerModeConsulta = 'cross';
        dvrFotoViewerHistoricoListConsulta = [];
        const hint = document.getElementById('dvrFotoViewerContextHintConsulta');
        if (hint) hint.classList.add('hidden');
        const idx = dvrFotosViewerFlatConsulta.findIndex(function(e) { return e.dvrId === dvrId || e.dvrId === Number(dvrId); });
        if (idx < 0 || !dvrFotosViewerFlatConsulta.length) return;
        dvrFotoViewerIndexConsulta = idx;
        updateDvrFotoViewerConsulta();
        document.getElementById('dvrFotoViewerModalConsulta').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    function openDvrFotoHistoricoViewerConsulta(historicalArr, startIndex, dvrNomeFallback) {
        const arr = Array.isArray(historicalArr) ? historicalArr : [];
        if (!arr.length) return;
        dvrFotoViewerHistoricoListConsulta = arr.map(function(it) {
            return {
                dvrNome: it.dvrNome || dvrNomeFallback || '',
                data: it.data || '—',
                fotoUrl: it.fotoUrl || it.url || '',
            };
        });
        dvrFotoViewerModeConsulta = 'historico';
        dvrFotoViewerIndexConsulta = Math.max(0, Math.min(startIndex || 0, dvrFotoViewerHistoricoListConsulta.length - 1));
        const hint = document.getElementById('dvrFotoViewerContextHintConsulta');
        if (hint) hint.classList.remove('hidden');
        updateDvrFotoViewerConsulta();
        document.getElementById('dvrFotoViewerModalConsulta').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    window.closeDvrFotoViewerConsulta = function() {
        dvrFotoViewerModeConsulta = 'cross';
        dvrFotoViewerHistoricoListConsulta = [];
        const hint = document.getElementById('dvrFotoViewerContextHintConsulta');
        if (hint) hint.classList.add('hidden');
        document.getElementById('dvrFotoViewerModalConsulta').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    function updateDvrFotoViewerConsulta() {
        const list = dvrFotoViewerActiveListConsulta();
        const item = list[dvrFotoViewerIndexConsulta];
        if (!item) return;
        document.getElementById('dvrFotoViewerDvrNameConsulta').textContent = item.dvrNome || '';
        document.getElementById('dvrFotoViewerDataLabelConsulta').textContent = item.data || '—';
        document.getElementById('dvrFotoViewerImageConsulta').src = item.fotoUrl || item.url || '';
        document.getElementById('dvrFotoViewerCounterConsulta').textContent = (dvrFotoViewerIndexConsulta + 1) + ' / ' + list.length;
        const prevBtn = document.getElementById('dvrFotoViewerBtnPrevConsulta');
        const nextBtn = document.getElementById('dvrFotoViewerBtnNextConsulta');
        if (dvrFotoViewerModeConsulta === 'historico') {
            if (prevBtn) prevBtn.title = 'Foto anterior';
            if (nextBtn) nextBtn.title = 'Próxima foto';
        } else {
            if (prevBtn) prevBtn.title = 'DVR anterior';
            if (nextBtn) nextBtn.title = 'Próximo DVR';
        }
    }

    window.dvrFotoViewerNextConsulta = function() {
        const list = dvrFotoViewerActiveListConsulta();
        const n = list.length;
        if (n <= 1) return;
        dvrFotoViewerIndexConsulta = (dvrFotoViewerIndexConsulta + 1) % n;
        updateDvrFotoViewerConsulta();
    };
    window.dvrFotoViewerPrevConsulta = function() {
        const list = dvrFotoViewerActiveListConsulta();
        const n = list.length;
        if (n <= 1) return;
        dvrFotoViewerIndexConsulta = dvrFotoViewerIndexConsulta <= 0 ? n - 1 : dvrFotoViewerIndexConsulta - 1;
        updateDvrFotoViewerConsulta();
    };

    document.getElementById('dvrFotoViewerModalConsulta')?.addEventListener('click', function(e) {
        if (e.target === this) closeDvrFotoViewerConsulta();
    });

    function openHistoricoDvrModalConsulta(nome, historical) {
        document.getElementById('historicoDvrModalTituloConsulta').textContent = 'Fotos do DVR – ' + (nome || '');
        const lista = document.getElementById('historicoDvrModalListaConsulta');
        lista.innerHTML = '';
        const arr = Array.isArray(historical) ? historical : [];
        if (arr.length === 0) {
            lista.innerHTML = '<li class="text-gray-500 text-sm">Nenhuma foto.</li>';
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
                btnThumb.title = 'Ampliar';
                const img = document.createElement('img');
                img.src = url || '';
                img.alt = '';
                img.className = 'h-20 w-auto max-w-[120px] object-cover rounded border border-gray-300 pointer-events-none';
                btnThumb.appendChild(img);
                btnThumb.addEventListener('click', function() {
                    openDvrFotoHistoricoViewerConsulta(arr, index, nome);
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
                li.appendChild(btnThumb);
                li.appendChild(wrap);
                lista.appendChild(li);
            });
        }
        document.getElementById('historicoDvrModalConsulta').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    window.closeHistoricoDvrModalConsulta = function() {
        document.getElementById('historicoDvrModalConsulta').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-historico-dvr-consulta');
        if (!btn) return;
        const nome = btn.getAttribute('data-dvr-nome') || 'DVR';
        const raw = btn.getAttribute('data-historical');
        let historical = [];
        try {
            if (raw) historical = JSON.parse(atob(raw) || '[]');
        } catch (_) {}
        openHistoricoDvrModalConsulta(nome, historical);
    });

    document.getElementById('historicoDvrModalConsulta')?.addEventListener('click', function(ev) {
        if (ev.target === this) closeHistoricoDvrModalConsulta();
    });

    function openHistoricoCameraModalConsulta(nome, historical) {
        document.getElementById('historicoCameraModalTituloConsulta').textContent = 'Histórico da Câmera – ' + nome;
        const lista = document.getElementById('historicoCameraModalListaConsulta');
        lista.innerHTML = '';
        const arr = Array.isArray(historical) ? historical : [];
        if (arr.length === 0) {
            lista.innerHTML = '<li class="text-gray-500">Nenhum registro.</li>';
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
        document.getElementById('historicoCameraModalConsulta').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    window.closeHistoricoCameraModalConsulta = function() {
        document.getElementById('historicoCameraModalConsulta').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-historico-camera-consulta');
        if (!btn) return;
        const nome = btn.getAttribute('data-camera-nome') || 'Câmera';
        const raw = btn.getAttribute('data-historical');
        let historical = [];
        try {
            if (raw) historical = JSON.parse(atob(raw) || '[]');
        } catch (_) {}
        openHistoricoCameraModalConsulta(nome, historical);
    });

    document.getElementById('historicoCameraModalConsulta')?.addEventListener('click', function(ev) {
        if (ev.target === this) closeHistoricoCameraModalConsulta();
    });

    document.addEventListener('keydown', function(e) {
        const camM = document.getElementById('cameraViewerModal');
        const dvrVM = document.getElementById('dvrFotoViewerModalConsulta');
        if (camM && !camM.classList.contains('hidden')) {
            if (e.key === 'Escape') closeCameraViewerConsulta();
            if (e.key === 'ArrowRight') cameraViewerNextConsulta();
            if (e.key === 'ArrowLeft') cameraViewerPrevConsulta();
            return;
        }
        if (dvrVM && !dvrVM.classList.contains('hidden')) {
            if (e.key === 'Escape') { closeDvrFotoViewerConsulta(); return; }
            if (e.key === 'ArrowRight') { dvrFotoViewerNextConsulta(); return; }
            if (e.key === 'ArrowLeft') { dvrFotoViewerPrevConsulta(); return; }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Escape') return;
        const dvrEl = document.getElementById('dvrFotoViewerModalConsulta');
        const camEl = document.getElementById('cameraViewerModal');
        if (dvrEl && !dvrEl.classList.contains('hidden')) return;
        if (camEl && !camEl.classList.contains('hidden')) return;
        const hCam = document.getElementById('historicoCameraModalConsulta');
        if (hCam && !hCam.classList.contains('hidden')) {
            closeHistoricoCameraModalConsulta();
            return;
        }
        const hDvr = document.getElementById('historicoDvrModalConsulta');
        if (hDvr && !hDvr.classList.contains('hidden')) {
            closeHistoricoDvrModalConsulta();
        }
    });
})();
</script>
<style>
.expand-dvr-icon.rotate-90 { transform: rotate(90deg); }
.expand-icon-consulta.expanded { transform: rotate(90deg); }

/* Badge piscante: quantidade de checklists em andamento */
.checklist-andamento-badge {
    position: absolute;
    top: -0.35rem;
    right: -0.35rem;
    min-width: 1.35rem;
    height: 1.35rem;
    padding: 0 0.35rem;
    font-size: 0.7rem;
    font-weight: 700;
    line-height: 1.35rem;
    color: #fff;
    text-align: center;
    background: #ef4444;
    border-radius: 9999px;
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.95);
    animation: checklist-andamento-badge-blink 1.1s ease-in-out infinite;
    pointer-events: none;
    z-index: 2;
}
@keyframes checklist-andamento-badge-blink {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.95), 0 0 0 0 rgba(239, 68, 68, 0.45);
    }
    50% {
        opacity: 0.9;
        transform: scale(1.14);
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.95), 0 0 14px 4px rgba(239, 68, 68, 0.5);
    }
}
</style>
@endsection
