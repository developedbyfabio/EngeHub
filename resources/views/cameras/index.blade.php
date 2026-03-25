@extends('layouts.app')

@section('header')
    <x-page-header title="Câmeras" icon="fas fa-video">
        <x-slot name="actions">
            <button type="button" onclick="openIniciarChecklistModal()" class="page-header-btn-primary mr-2">
                <i class="fas fa-play mr-2"></i>
                Iniciar Novo Checklist
            </button>
            <button type="button" onclick="openApagarHistoricoModal()" class="page-header-btn-secondary mr-2">
                <i class="fas fa-trash-alt mr-2"></i>
                Apagar Histórico
            </button>
            @if(auth()->user()?->hasFullAccess() ?? false)
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

        {{-- Checklists em Andamento --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">
                    <i class="fas fa-sync-alt mr-2 text-amber-500"></i>
                    Checklists em Andamento
                </h2>
                <p class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-4">
                    <i class="fas fa-info-circle mr-1"></i>
                    Checklists não finalizados após <strong>9 horas</strong> do início são excluídos automaticamente do sistema.
                </p>
                @if($checklistsEmAndamento->count() > 0)
                    <ul id="checklists-em-andamento" class="space-y-3">
                        @foreach($checklistsEmAndamento as $c)
                            <li class="flex items-center justify-between gap-4 p-3 rounded-lg hover:bg-gray-50">
                                <a href="{{ route('cameras.checklists.show', $c) }}" class="inline-flex items-center text-blue-600 hover:underline font-medium flex-1">
                                    @php $totalDvrs = $dvrs->count(); $mostrarTodos = $totalDvrs > 0 && $c->dvrs->count() >= $totalDvrs; $dvrNomes = $mostrarTodos ? 'Todos os DVRs' : ($c->dvrs->count() > 0 ? $c->dvrs->pluck('nome')->join(', ') : ($c->dvr?->nome ?? '-')); @endphp
                                    {{ $dvrNomes }} — iniciado {{ $c->iniciado_em->format('d/m/Y H:i') }}
                                    <i class="fas fa-arrow-right ml-2 text-sm"></i>
                                </a>
                                <button type="button" onclick="abandonarChecklist(this)" data-cancel-url="{{ route('cameras.checklists.cancelar', $c) }}" data-dvr-nome="{{ e($dvrNomes) }}" class="text-red-600 hover:text-red-800 text-sm font-medium" title="Abandonar checklist">
                                    <i class="fas fa-times-circle mr-1"></i> Abandonar
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-sm text-gray-500 mt-2">Clique para continuar de onde parou.</p>
                @else
                    <p class="text-gray-500">Nenhum checklist em andamento.</p>
                @endif
            </div>
        </div>

        {{-- Modal Iniciar Novo Checklist (único) --}}
        <div id="iniciarChecklistModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
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
        <div id="modalConfirmarAbandonar" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
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

        {{-- Modal Apagar Histórico --}}
        <div id="apagarHistoricoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
            <div class="w-11/12 md:w-96 shadow-lg rounded-md bg-white m-4">
                <div class="flex justify-between items-center mb-4 px-6 pt-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-trash-alt mr-2 text-red-600"></i>
                        Apagar Histórico
                    </h3>
                    <button type="button" onclick="closeApagarHistoricoModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="px-6 pb-6">
                    <p class="text-sm text-gray-600 mb-4">Esta ação irá excluir <strong>todos</strong> os históricos de checklists finalizados ou cancelados. Digite a senha para confirmar:</p>
                    <input type="password" id="apagarHistoricoSenha" placeholder="Senha de confirmação" class="block w-full border-gray-300 rounded-md shadow-sm mb-4" autocomplete="off">
                    <p id="apagarHistoricoErro" class="text-red-500 text-sm mb-4 hidden"></p>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeApagarHistoricoModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</button>
                        <button type="button" onclick="confirmarApagarHistorico()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium">
                            <i class="fas fa-trash-alt mr-2"></i> Apagar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Histórico de Checklists Finalizados --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-semibold mb-4">
                    <i class="fas fa-history mr-2 text-blue-600"></i>
                    Histórico de Checklists Finalizados
                </h2>

                <form method="GET" action="{{ route('cameras.index') }}" class="flex flex-wrap gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Período:</label>
                        <select name="period" class="border-gray-300 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                            <option value="today" {{ ($period ?? 'week') == 'today' ? 'selected' : '' }}>Hoje</option>
                            <option value="week" {{ ($period ?? 'week') == 'week' ? 'selected' : '' }}>Últimos 7 dias</option>
                            <option value="month" {{ ($period ?? '') == 'month' ? 'selected' : '' }}>Últimos 30 dias</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">DVR:</label>
                        <select name="dvr_id" class="border-gray-300 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            @foreach($dvrs ?? [] as $d)
                                <option value="{{ $d->id }}" {{ ($dvrFilter ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

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
                                        $dvrNomesHist = $c->dvrs->count() > 0 ? $c->dvrs->pluck('nome')->join(', ') : ($c->dvr?->nome ?? '-');
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
        </div>
    </div>
</div>

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

function openApagarHistoricoModal() {
    document.getElementById('apagarHistoricoModal').classList.remove('hidden');
    document.getElementById('apagarHistoricoSenha').value = '';
    document.getElementById('apagarHistoricoErro').classList.add('hidden');
    document.body.classList.add('overflow-hidden');
}
function closeApagarHistoricoModal() {
    document.getElementById('apagarHistoricoModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function confirmarApagarHistorico() {
    const senha = document.getElementById('apagarHistoricoSenha').value;
    const erroEl = document.getElementById('apagarHistoricoErro');
    if (!senha) {
        erroEl.textContent = 'Digite a senha.';
        erroEl.classList.remove('hidden');
        return;
    }
    erroEl.classList.add('hidden');
    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    fd.append('senha', senha);
    fetch('{{ route("cameras.historico.apagar") }}', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            closeApagarHistoricoModal();
            if (data.redirect) {
                const sep = data.redirect.includes('?') ? '&' : '?';
                window.location.href = data.redirect + (data.message ? sep + 'success=' + encodeURIComponent(data.message) : '');
            } else {
                window.location.reload();
            }
        } else {
            erroEl.textContent = data.message || 'Erro ao apagar histórico.';
            erroEl.classList.remove('hidden');
        }
    })
    .catch(() => {
        erroEl.textContent = 'Erro ao apagar histórico.';
        erroEl.classList.remove('hidden');
    });
}

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

document.getElementById('modalConfirmarAbandonar')?.addEventListener('click', function(e) {
    if (e.target === this) fecharModalConfirmarAbandonar();
});
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('modalConfirmarAbandonar');
    if (modal && !modal.classList.contains('hidden') && e.key === 'Escape') fecharModalConfirmarAbandonar();
});

document.getElementById('btnConfirmarAbandonar')?.addEventListener('click', function() {
    const { url, btn } = _abandonarContext;
    if (!url) return;
    fecharModalConfirmarAbandonar();
    if (btn) btn.disabled = true;
    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    fetch(url, {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json().catch(() => ({})))
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Erro ao abandonar.');
            if (btn) btn.disabled = false;
        }
    })
    .catch(() => { alert('Erro ao abandonar.'); if (btn) btn.disabled = false; });
});
</script>
<style>
.expand-dvr-icon.rotate-90 { transform: rotate(90deg); }
</style>
@endsection
