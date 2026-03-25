@extends('layouts.app')

@section('header')
    <x-page-header>
        <x-slot name="left">
            <a href="{{ route('admin.forms.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center gap-2 font-medium">
                <i class="fas fa-arrow-left"></i> Voltar para Checklists e Formulários
            </a>
        </x-slot>
        <x-slot name="actions">
            <button type="button" onclick="openLinksModal()" class="page-header-btn-secondary mr-2">
                <i class="fas fa-link mr-2"></i> Links
            </button>
            <button type="button" onclick="openLogModal()" class="page-header-btn-secondary mr-2">
                <i class="fas fa-history mr-2"></i> Log
            </button>
            <button type="button" onclick="openPesosPadraoModal()" class="page-header-btn-secondary mr-2">
                <i class="fas fa-balance-scale mr-2"></i> Pesos Padrão
            </button>
            <a href="{{ route('admin.forms.stats', $form) }}" class="page-header-btn-secondary mr-2">
                <i class="fas fa-chart-bar mr-2"></i> Estatísticas
            </a>
            <button type="button" onclick="openLimparModal()" class="page-header-btn-secondary mr-2">
                <i class="fas fa-eraser mr-2"></i> Limpar
            </button>
            <a href="{{ route('admin.forms.edit', $form) }}" class="page-header-btn-primary">
                <i class="fas fa-edit mr-2"></i> Editar
            </a>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">
        {{-- Título do formulário --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <h2 class="font-semibold text-xl text-gray-800 flex items-center">
                <i class="fas fa-clipboard-list mr-2 flex-shrink-0" style="color: #E9B32C; font-size: 1.25rem;"></i>
                {{ $form->title }}
            </h2>
        </div>

        {{-- Temas e Perguntas --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Temas e Perguntas</h2>
                    <div class="flex gap-2">
                        <button type="button" onclick="document.getElementById('add-theme-form').classList.toggle('hidden')" class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white text-sm rounded hover:bg-purple-700">
                            <i class="fas fa-layer-group mr-1"></i> Adicionar tema
                        </button>
                    </div>
                </div>

                <div id="add-theme-form" class="hidden mb-6 p-4 bg-purple-50 rounded-lg border border-purple-100">
                    <form action="{{ route('admin.forms.themes.store', $form) }}" method="POST">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="title" placeholder="Título do tema (ex: Escala de Avaliação do Contexto do Trabalho - EACT)" required class="w-full rounded-md border-gray-300">
                            <textarea name="description" placeholder="Instrução/descrição (ex: Leia os itens e escolha a alternativa que melhor corresponde à avaliação...)" rows="2" class="w-full rounded-md border-gray-300"></textarea>
                            <div class="flex gap-2">
                                <input type="number" name="order" placeholder="Ordem" value="{{ ($form->themes->max('order') ?? 0) + 1 }}" min="0" class="w-24 rounded-md border-gray-300">
                                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Criar tema</button>
                            </div>
                        </div>
                    </form>
                </div>

                @forelse($form->themes as $themeIndex => $theme)
                    <div class="border border-gray-200 rounded-lg mb-6 overflow-hidden">
                        <div class="p-4 bg-gray-100 border-b border-gray-200 flex justify-between items-start gap-2">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <button type="button" onclick="toggleThemeQuestions({{ $theme->id }})" class="text-gray-600 hover:text-gray-900 shrink-0 p-1 rounded hover:bg-gray-200" title="Expandir/recolher perguntas">
                                        <i id="theme-toggle-icon-{{ $theme->id }}" class="fas fa-chevron-down text-xs transition-transform"></i>
                                    </button>
                                    <h3 class="font-semibold text-gray-900">{{ $themeIndex + 1 }}. {{ $theme->title }}</h3>
                                </div>
                                @if($theme->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($theme->description, 150) }}</p>
                                @endif
                                <span class="text-xs text-gray-500 mt-1 inline-block">{{ $theme->questions->count() }} perguntas</span>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <button type="button" onclick="toggleThemeEdit({{ $theme->id }})" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('admin.forms.themes.destroy', [$form, $theme]) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este tema e todas as perguntas?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <div id="theme-edit-{{ $theme->id }}" class="hidden p-4 bg-gray-50 border-b border-gray-200">
                            <form action="{{ route('admin.forms.themes.update', [$form, $theme]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="space-y-3">
                                    <input type="text" name="title" value="{{ $theme->title }}" required class="w-full rounded-md border-gray-300">
                                    <textarea name="description" rows="2" class="w-full rounded-md border-gray-300">{{ $theme->description }}</textarea>
                                    <input type="number" name="order" value="{{ $theme->order }}" min="0" class="w-24 rounded-md border-gray-300">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Salvar</button>
                                </div>
                            </form>
                        </div>
                        <div id="theme-questions-{{ $theme->id }}" class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-gray-700">Perguntas deste tema</span>
                                <button type="button" onclick="document.getElementById('add-question-{{ $theme->id }}').classList.toggle('hidden')" class="inline-flex items-center px-2 py-1 bg-primary-600 text-white text-xs rounded hover:bg-primary-700">
                                    <i class="fas fa-plus mr-1"></i> Adicionar pergunta
                                </button>
                            </div>
                            <div id="add-question-{{ $theme->id }}" class="hidden mb-4 p-3 bg-gray-50 rounded-lg">
                                <form action="{{ route('admin.forms.questions.store', $form) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                                    <div class="flex gap-2 flex-wrap">
                                        <input type="text" name="question_text" placeholder="Texto da pergunta" required class="flex-1 min-w-[200px] rounded-md border-gray-300 text-sm">
                                        <input type="number" name="order" value="{{ $theme->questions->max('order') + 1 }}" min="0" class="w-20 rounded-md border-gray-300 text-sm">
                                        <button type="submit" class="px-3 py-1 bg-primary-600 text-white text-sm rounded hover:bg-primary-700">Salvar</button>
                                    </div>
                                </form>
                            </div>

                            @foreach($theme->questions as $qIndex => $question)
                                <div class="border border-gray-100 rounded-lg mb-3 overflow-hidden">
                                    <div class="p-3 bg-white flex justify-between items-start">
                                        <span class="text-sm font-medium text-gray-900">{{ $qIndex + 1 }}. {{ $question->question_text }}</span>
                                        <form action="{{ route('admin.forms.questions.destroy', [$form, $question]) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta pergunta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                    <div class="p-3 bg-gray-50">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-xs font-medium text-gray-600">Opções (peso)</span>
                                            @if($form->standardWeightProfiles->isNotEmpty())
                                                <form action="{{ route('admin.forms.questions.apply-standard-weights', [$form, $question]) }}" method="POST" class="inline-flex gap-2 items-center">
                                                    @csrf
                                                    <select name="profile_id" required class="rounded border-gray-300 text-xs py-1">
                                                        <option value="">Usar Pesos Padrão...</option>
                                                        @foreach($form->standardWeightProfiles as $profile)
                                                            <option value="{{ $profile->id }}">{{ $profile->name }} ({{ $profile->options->count() }} opções)</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="px-2 py-1 bg-amber-600 text-white text-xs rounded hover:bg-amber-700 whitespace-nowrap">
                                                        <i class="fas fa-magic mr-1"></i> Aplicar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="space-y-1 mb-2" id="options-list-{{ $question->id }}" data-question-id="{{ $question->id }}" data-reorder-url="{{ route('admin.forms.options.reorder', [$form, $question]) }}">
                                            @foreach($question->options as $option)
                                                <div class="grid grid-cols-[auto_1fr_5rem_auto] gap-2 items-center text-sm options-row" data-option-id="{{ $option->id }}">
                                                    <span class="cursor-grab active:cursor-grabbing text-gray-400 hover:text-gray-600 options-drag-handle" title="Arrastar para reordenar">
                                                        <i class="fas fa-grip-vertical"></i>
                                                    </span>
                                                    <span class="min-w-0 truncate">{{ $option->option_text }}</span>
                                                    <span class="text-gray-500 text-right w-20 shrink-0">Peso: {{ $option->weight }}</span>
                                                    <form action="{{ route('admin.forms.options.destroy', [$form, $question, $option]) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta opção?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700"><i class="fas fa-times"></i></button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                        <form action="{{ route('admin.forms.options.store', [$form, $question]) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            <input type="text" name="option_text" placeholder="Nova opção (ex: Nunca, Raramente...)" required class="flex-1 rounded border-gray-300 text-sm">
                                            <input type="number" name="weight" placeholder="Peso" required class="w-20 rounded border-gray-300 text-sm">
                                            <button type="submit" class="px-2 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">+</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                            @if($theme->questions->isEmpty())
                                <p class="text-gray-500 text-sm italic">Nenhuma pergunta neste tema. Clique em "Adicionar pergunta" acima.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Nenhum tema cadastrado. Clique em <strong>Adicionar tema</strong> para começar e organize as perguntas por blocos (ex: EACT, ECHT, EADRT).</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal Limpar dados --}}
<div id="modal-limpar" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">Limpar dados de teste</h3>
            <button type="button" onclick="closeLimparModal()" class="px-3 py-1.5 text-gray-400 hover:text-white rounded">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-sm text-gray-400 mb-4">Esta ação remove logs e respostas preenchidas deste formulário. Use apenas para testes.</p>
        <form action="{{ route('admin.forms.clear-data', $form) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-300 mb-2">O que deseja apagar?</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="clear_option" value="logs" required checked>
                            <span class="text-gray-200">Apagar todos os logs (registros de preenchimento)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="clear_option" value="responses">
                            <span class="text-gray-200">Apagar todas as respostas</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-2">Senha de confirmação *</label>
                    <input type="password" name="password" required placeholder="Digite a senha" autocomplete="off" class="w-full rounded-lg border-gray-600 bg-gray-700 text-white px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="flex gap-2 justify-end pt-2">
                    <button type="button" onclick="closeLimparModal()" class="px-4 py-2 text-gray-400 hover:text-white rounded">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                        <i class="fas fa-trash mr-2"></i> Apagar dados
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Log de respostas --}}
<div id="modal-log" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">Log de respostas</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.forms.show', $form) }}?open_log=1" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white text-sm rounded hover:bg-primary-700">
                    <i class="fas fa-sync-alt mr-2"></i> Atualizar
                </a>
                <button type="button" onclick="closeLogModal()" class="px-3 py-1.5 text-gray-400 hover:text-white rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <p class="text-sm text-gray-400 mb-4">Registro de formulários preenchidos por link: ID → Data e Hora → Filial → Tempo de preenchimento</p>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-600 text-sm">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">ID</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Data e Hora</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Filial</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase">Tempo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-600">
                    @forelse($responseLogs as $r)
                        <tr>
                            <td class="px-3 py-2 text-gray-200 font-mono">{{ $r->id }}</td>
                            <td class="px-3 py-2 text-gray-200">{{ $r->submitted_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="px-3 py-2 text-gray-200">{{ $r->branch->name ?? '-' }}</td>
                            <td class="px-3 py-2 text-gray-200">
                                @if($r->completion_time_seconds !== null)
                                    @php
                                        $s = (int) $r->completion_time_seconds;
                                        $min = floor($s / 60);
                                        $sec = $s % 60;
                                        if ($min > 0 && $sec > 0) echo "{$min} min {$sec} s";
                                        elseif ($min > 0) echo "{$min} min";
                                        else echo "{$sec} s";
                                    @endphp
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-8 text-center text-gray-400">Nenhum formulário preenchido ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Links --}}
<div id="modal-links" class="fixed inset-0 bg-black bg-opacity-50 z-[10050] hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-3xl w-full max-h-[75vh] overflow-y-auto" @click.stop>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">Links</h3>
            <button type="button" onclick="closeLinksModal()" class="px-3 py-1.5 text-gray-400 hover:text-white rounded">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modal-links-body">
            @include('admin.forms.partials.modal-links-body', ['form' => $form])
        </div>
    </div>
</div>

{{-- Modal Pesos Padrão (deste formulário) --}}
<div id="modal-pesos-padrao" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">Pesos Padrão deste formulário</h3>
            <div class="flex gap-2">
                <button type="button" onclick="openPesosPadraoCreateModal()" class="page-header-btn-primary text-xs py-1.5">
                    <i class="fas fa-plus mr-1"></i> Novo perfil
                </button>
                <button type="button" onclick="closePesosPadraoModal()" class="px-3 py-1.5 text-gray-400 hover:text-white rounded">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div id="modal-pesos-body">
            @include('admin.forms.partials.modal-pesos-body', ['form' => $form])
        </div>
    </div>
</div>

{{-- Modal Criar Perfil de Pesos Padrão --}}
<div id="modal-pesos-padrao-create" class="fixed inset-0 bg-black bg-opacity-60 z-[60] hidden items-center justify-center p-4">
    <div class="modal-dark rounded-xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto" @click.stop>
        <h3 class="text-lg font-semibold text-white mb-4">Novo perfil de Pesos Padrão</h3>
        @include('admin.forms.partials.modal-pesos-create-form', ['form' => $form])
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
function copyFormLink(btn) {
    const url = btn.getAttribute('data-copy-url');
    if (!url) return;

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(() => {
            showCopyFeedback(btn);
        }).catch(() => {
            fallbackCopy(url, btn);
        });
    } else {
        fallbackCopy(url, btn);
    }
}

function fallbackCopy(text, btn) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        showCopyFeedback(btn);
    } catch (e) {
        alert('Não foi possível copiar. Link: ' + text);
    }
    document.body.removeChild(textarea);
}

function toggleThemeEdit(themeId) {
    const el = document.getElementById('theme-edit-' + themeId);
    el.classList.toggle('hidden');
}
const FORM_THEMES_STORAGE_KEY = 'form-{{ $form->id }}-collapsed-themes';

function toggleThemeQuestions(themeId) {
    const content = document.getElementById('theme-questions-' + themeId);
    const icon = document.getElementById('theme-toggle-icon-' + themeId);
    if (!content || !icon) return;
    const isHidden = content.classList.contains('hidden');
    let collapsed = JSON.parse(localStorage.getItem(FORM_THEMES_STORAGE_KEY) || '[]');
    if (isHidden) {
        content.classList.remove('hidden');
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
        collapsed = collapsed.filter(id => id !== themeId);
    } else {
        content.classList.add('hidden');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
        if (!collapsed.includes(themeId)) collapsed.push(themeId);
    }
    localStorage.setItem(FORM_THEMES_STORAGE_KEY, JSON.stringify(collapsed));
}

function restoreThemeQuestionsState() {
    const collapsed = JSON.parse(localStorage.getItem(FORM_THEMES_STORAGE_KEY) || '[]');
    collapsed.forEach(function(themeId) {
        const content = document.getElementById('theme-questions-' + themeId);
        const icon = document.getElementById('theme-toggle-icon-' + themeId);
        if (content && icon) {
            content.classList.add('hidden');
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        }
    });
}

function showCopyFeedback(btn) {
    btn.innerHTML = '<i class="fas fa-check text-green-600"></i><span class="text-xs text-green-600">Copiado!</span>';
    btn.disabled = true;
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-copy"></i><span class="text-xs">Copiar</span>';
        btn.disabled = false;
    }, 2000);
}

function openLinksModal() {
    document.getElementById('modal-links').classList.remove('hidden');
    document.getElementById('modal-links').classList.add('flex');
}
function openLimparModal() {
    document.getElementById('modal-limpar').classList.remove('hidden');
    document.getElementById('modal-limpar').classList.add('flex');
}
function closeLimparModal() {
    document.getElementById('modal-limpar').classList.add('hidden');
    document.getElementById('modal-limpar').classList.remove('flex');
}
function openLogModal() {
    document.getElementById('modal-log').classList.remove('hidden');
    document.getElementById('modal-log').classList.add('flex');
}
function closeLogModal() {
    document.getElementById('modal-log').classList.add('hidden');
    document.getElementById('modal-log').classList.remove('flex');
}
function closeLinksModal() {
    document.getElementById('modal-links').classList.add('hidden');
    document.getElementById('modal-links').classList.remove('flex');
}
function openPesosPadraoModal() {
    document.getElementById('modal-pesos-padrao').classList.remove('hidden');
    document.getElementById('modal-pesos-padrao').classList.add('flex');
}
function closePesosPadraoModal() {
    document.getElementById('modal-pesos-padrao').classList.add('hidden');
    document.getElementById('modal-pesos-padrao').classList.remove('flex');
}
function openPesosPadraoCreateModal() {
    document.getElementById('modal-pesos-padrao-create').classList.remove('hidden');
    document.getElementById('modal-pesos-padrao-create').classList.add('flex');
}
function closePesosPadraoCreateModal() {
    document.getElementById('modal-pesos-padrao-create').classList.add('hidden');
    document.getElementById('modal-pesos-padrao-create').classList.remove('flex');
}
function addPesoOptionRow() {
    const tbody = document.getElementById('pesos-options-tbody');
    const n = tbody.querySelectorAll('tr').length;
    const tr = document.createElement('tr');
    tr.innerHTML = `<td class="px-2 py-1"><input type="text" name="options[${n}][option_text]" placeholder="Ex: Opção" required class="w-full rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
        <td class="px-2 py-1"><input type="number" name="options[${n}][weight]" placeholder="0" required class="w-16 rounded border-gray-600 bg-gray-700 text-white px-2 py-1 text-sm"></td>
        <td class="px-2 py-1"><button type="button" onclick="this.closest('tr').remove()" class="text-red-400 hover:text-red-300"><i class="fas fa-times"></i></button></td>`;
    tbody.appendChild(tr);
}
document.getElementById('modal-limpar')?.addEventListener('click', function(e) { if (e.target === this) closeLimparModal(); });
document.getElementById('modal-log')?.addEventListener('click', function(e) { if (e.target === this) closeLogModal(); });
document.getElementById('modal-links')?.addEventListener('click', function(e) { if (e.target === this) closeLinksModal(); });
document.getElementById('modal-pesos-padrao')?.addEventListener('click', function(e) { if (e.target === this) closePesosPadraoModal(); });
document.getElementById('modal-pesos-padrao-create')?.addEventListener('click', function(e) { if (e.target === this) closePesosPadraoCreateModal(); });

 document.addEventListener('DOMContentLoaded', function() {
    restoreThemeQuestionsState();
    @if($openLog ?? false)
        openLogModal();
    @endif
    initOptionsSortable();
    initAjaxForms();
});

function initAjaxForms() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const formTargetMap = { links: 'modal-links-body', pesos: 'modal-pesos-body', 'pesos-create': 'modal-pesos-body' };

    function handleSubmit(e) {
        const form = e.target.closest('form[data-ajax-form]');
        if (!form) return;

        const formType = form.getAttribute('data-ajax-form');
        if (!formType) return;

        e.preventDefault();

        const confirmMsg = form.getAttribute('data-ajax-confirm');
        if (confirmMsg && !confirm(confirmMsg)) return;

        const targetId = formTargetMap[formType];
        const bodyEl = targetId ? document.getElementById(targetId) : null;

        const formData = new FormData(form);
        const method = (form.querySelector('[name="_method"]')?.value || form.method || 'POST').toUpperCase();
        const url = form.action;

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(r) {
            if (!r.ok) return r.json().then(function(data) { throw data; });
            return r.json();
        })
        .then(function(data) {
            if (data.success && data.html && bodyEl) {
                bodyEl.innerHTML = data.html;
            }
            if (data.message) {
                if (typeof window.showFlashMessage === 'function') {
                    window.showFlashMessage(data.message, 'success');
                } else {
                    alert(data.message);
                }
            }
            if (formType === 'pesos-create') {
                form.reset();
                closePesosPadraoCreateModal();
            }
        })
        .catch(function(err) {
            let msg = 'Erro ao processar.';
            if (err) {
                if (err.message) msg = err.message;
                else if (err.errors && typeof err.errors === 'object') {
                    const first = Object.values(err.errors).flat()[0];
                    if (first) msg = first;
                }
            }
            if (typeof window.showFlashMessage === 'function') {
                window.showFlashMessage(msg, 'error');
            } else {
                alert(msg);
            }
        });
    }

    document.getElementById('modal-links')?.addEventListener('submit', handleSubmit, true);
    document.getElementById('modal-pesos-padrao')?.addEventListener('submit', handleSubmit, true);
    document.getElementById('modal-pesos-padrao-create')?.addEventListener('submit', handleSubmit, true);
}

function initOptionsSortable() {
    if (typeof Sortable === 'undefined') return;
    document.querySelectorAll('[id^="options-list-"]').forEach(function(el) {
        if (el._sortable) return;
        el._sortable = Sortable.create(el, {
            handle: '.options-drag-handle',
            animation: 150,
            onEnd: function() {
                const url = el.dataset.reorderUrl;
                const optionIds = Array.from(el.querySelectorAll('.options-row')).map(function(row) { return row.dataset.optionId; });
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ option_ids: optionIds })
                }).then(function(r) { return r.json(); })
                .then(function(data) { if (data.success) {} })
                .catch(function() { /* fallback: reload? */ });
            }
        });
    });
}
</script>
@endsection
