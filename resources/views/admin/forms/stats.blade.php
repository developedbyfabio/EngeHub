@extends('layouts.app')

@section('header')
    <x-page-header title="Estatísticas: {{ $form->title }}" icon="fas fa-chart-bar">
        <x-slot name="actions">
            <a href="{{ route('admin.forms.show', $form) }}" class="text-gray-600 hover:text-gray-900 font-medium mr-4">
                <i class="fas fa-arrow-left mr-2"></i> Voltar
            </a>
            <button type="button" onclick="openExportPdfModal()" class="page-header-btn-primary">
                <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
            </button>
        </x-slot>
    </x-page-header>
@endsection

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
        {{-- Filtros --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Filtros</h3>
            <form action="{{ route('admin.forms.stats', $form) }}" method="GET" class="flex flex-wrap gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Filial</label>
                    <select name="branch_id" class="mt-1 rounded-md border-gray-300 shadow-sm">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                            <option value="{{ $b->id }}" {{ (string)($branchId ?? '') === (string)$b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 font-medium">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        {{-- Cards resumo --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Total de respostas</div>
                <div class="text-2xl font-bold text-gray-900">{{ $stats['total_responses'] }}</div>
                <p class="text-xs text-gray-400 mt-1">Formulários finalizados</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Média geral</div>
                <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['general_average'] ?? 0, 2) }}</div>
                <p class="text-xs text-gray-400 mt-1">Escala 1 a 5</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Classificação de risco</div>
                @php $risk = $stats['risk_classification'] ?? ['label' => '-', 'color' => 'gray', 'icon' => '']; @endphp
                @if($risk['color'] === 'green')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-1">
                        🟢 {{ $risk['label'] }}
                    </span>
                @elseif($risk['color'] === 'yellow')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 mt-1">
                        🟡 {{ $risk['label'] }}
                    </span>
                @elseif($risk['color'] === 'red')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 mt-1">
                        🔴 {{ $risk['label'] }}
                    </span>
                @else
                    <span class="text-gray-400 text-sm mt-1">-</span>
                @endif
                <p class="text-xs text-gray-400 mt-2" title="1-2: Baixo | 2.01-3.5: Moderado | 3.51-5: Alto">Ver legenda</p>
            </div>
        </div>

        {{-- Barra de progresso visual (média geral) --}}
        @if(($stats['general_average'] ?? 0) > 0)
            @php
                $avg = $stats['general_average'] ?? 0;
                $percentual = min(100, max(0, (($avg - 1) / 4) * 100));
                $riskColor = $stats['risk_classification']['color'] ?? 'gray';
                $fillBg = match($riskColor) {
                    'green' => 'bg-green-500',
                    'yellow' => 'bg-amber-500',
                    'red' => 'bg-red-500',
                    default => 'bg-gray-400',
                };
            @endphp
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Indicador de risco geral (escala 1 a 5)</h3>
                <div class="flex items-start gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="h-6 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full rounded-l-full transition-all duration-300 {{ $fillBg }}" style="width: {{ $percentual }}%;"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1 px-0">
                            <span>1</span>
                            <span>2</span>
                            <span>3</span>
                            <span>4</span>
                            <span>5</span>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-700 w-14 shrink-0 pt-0.5">{{ number_format($avg, 2) }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1" title="1-2: Baixo risco | 2.01-3.5: Moderado | 3.51-5: Alto risco">
                    <i class="fas fa-info-circle mr-1"></i> Posição na escala indica o nível de risco psicossocial
                </p>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1"></i> Legenda de classificação de risco
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">🟢 Baixo risco</span>
                            <span class="text-gray-500">Média 1,00 a 2,00</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">🟡 Risco moderado</span>
                            <span class="text-gray-500">Média 2,01 a 3,50</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">🔴 Alto risco</span>
                            <span class="text-gray-500">Média 3,51 a 5,00</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Resultado por tema --}}
        @if(count($stats['by_theme'] ?? []) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Resultado por tema</h2>
                    <p class="text-sm text-gray-500 mb-4">Média e classificação de risco por escala/tema (EACT, ECHT, EADRT, etc.)</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tema</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Perguntas</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pontos</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Média (1-5)</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Classificação de risco</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($stats['by_theme'] as $index => $data)
                                    @php $rc = $data['risk_classification'] ?? []; @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-left">{{ $index + 1 }}. {{ $data['theme']->title ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 text-center">{{ $data['questions_count'] ?? 0 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 font-medium text-center">{{ number_format($data['points'] ?? 0, 0) }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">{{ number_format($data['average'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if(($rc['color'] ?? '') === 'green')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">🟢 {{ $rc['label'] ?? 'Baixo risco' }}</span>
                                            @elseif(($rc['color'] ?? '') === 'yellow')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">🟡 {{ $rc['label'] ?? 'Risco moderado' }}</span>
                                            @elseif(($rc['color'] ?? '') === 'red')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">🔴 {{ $rc['label'] ?? 'Alto risco' }}</span>
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
            </div>
        @endif

        {{-- Respostas por filial e tema (tabela pivot) --}}
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Respostas por filial e tema</h2>
                <p class="text-sm text-gray-500 mb-4">Média por filial e por tema (escala 1 a 5). Permite comparar filiais e identificar quais temas são mais críticos em cada uma.</p>
                @if(count($stats['branch_theme_matrix'] ?? []) > 0 && ($stats['themes'] ?? collect())->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase sticky left-0 bg-gray-50">Filial</th>
                                    @foreach($stats['themes'] as $theme)
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap">{{ $theme->code ?? $theme->title }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($stats['branch_theme_matrix'] as $row)
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900 sticky left-0 bg-white">{{ $row['branch']->name ?? '-' }}</td>
                                        @foreach($stats['themes'] as $theme)
                                            @php
                                                $cell = $row['themes'][$theme->id] ?? null;
                                            @endphp
                                            <td class="px-4 py-3 text-center">
                                                @if($cell)
                                                    @php $rc = $cell['risk_classification'] ?? []; @endphp
                                                    @if(($rc['color'] ?? '') === 'green')
                                                        <span class="inline-flex items-center justify-center min-w-[4rem] px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800" title="{{ $rc['label'] ?? '' }}">{{ number_format($cell['average'] ?? 0, 2) }}</span>
                                                    @elseif(($rc['color'] ?? '') === 'yellow')
                                                        <span class="inline-flex items-center justify-center min-w-[4rem] px-2 py-1 rounded text-xs font-semibold bg-amber-100 text-amber-800" title="{{ $rc['label'] ?? '' }}">{{ number_format($cell['average'] ?? 0, 2) }}</span>
                                                    @elseif(($rc['color'] ?? '') === 'red')
                                                        <span class="inline-flex items-center justify-center min-w-[4rem] px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800" title="{{ $rc['label'] ?? '' }}">{{ number_format($cell['average'] ?? 0, 2) }}</span>
                                                    @else
                                                        <span class="text-gray-600 font-medium">{{ number_format($cell['average'] ?? 0, 2) }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Nenhuma resposta no período filtrado.</p>
                @endif
            </div>
        </div>

        {{-- TOP 10 perguntas críticas --}}
        @if(count($stats['critical_questions'] ?? []) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">TOP 10 perguntas críticas</h2>
                    <p class="text-sm text-gray-500 mb-4">Top 10 perguntas com maior média (maior indicador de risco)</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pergunta</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tema</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Média</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indicador de risco</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($stats['critical_questions'] as $idx => $data)
                                    @php $rc = $data['risk_classification'] ?? []; @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-500">{{ $idx + 1 }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ Str::limit($data['question']->question_text ?? '-', 80) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $data['theme']->title ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ number_format($data['average'] ?? 0, 2) }}</td>
                                        <td class="px-4 py-3">
                                            @if(($rc['color'] ?? '') === 'green')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">🟢 {{ $rc['label'] ?? 'Baixo risco' }}</span>
                                            @elseif(($rc['color'] ?? '') === 'yellow')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">🟡 {{ $rc['label'] ?? 'Risco moderado' }}</span>
                                            @elseif(($rc['color'] ?? '') === 'red')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">🔴 {{ $rc['label'] ?? 'Alto risco' }}</span>
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
            </div>
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">TOP 10 perguntas críticas</h2>
                    <p class="text-gray-500 text-sm">Nenhuma pergunta respondida no período filtrado. Preencha o formulário para ver as perguntas com maior média.</p>
                </div>
            </div>
        @endif

    </div>
</div>

{{-- Modal Exportar PDF --}}
<div id="modal-export-pdf" class="fixed inset-0 bg-black bg-opacity-50 z-[110] hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full max-h-[90vh] overflow-hidden flex flex-col" @click.stop>
        <div class="p-5 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Exportar PDF</h3>
                <button type="button" onclick="closeExportPdfModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-1">Selecione as filiais para gerar o relatório. Uma página será gerada para cada filial.</p>
        </div>
        <div class="p-5 overflow-y-auto flex-1">
            <form id="form-export-pdf" action="{{ route('admin.forms.export-pdf', $form) }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="export_all" id="input-export-all" value="0">
                <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="checkbox-todas" name="export_all_check" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" onchange="toggleTodasCheckbox(this)">
                        <span class="text-sm font-medium text-gray-800">Todas</span>
                    </label>
                    <p class="text-xs text-gray-600 mt-1 ml-6">Gerar PDF com dados agregados de todas as filiais (Filial: Todas)</p>
                </div>
                <div class="flex gap-2 mb-3">
                    <button type="button" onclick="selectAllBranches()" class="text-sm px-3 py-1.5 bg-gray-200 hover:bg-gray-300 rounded-md">Selecionar todas</button>
                    <button type="button" onclick="deselectAllBranches()" class="text-sm px-3 py-1.5 bg-gray-200 hover:bg-gray-300 rounded-md">Desmarcar todas</button>
                </div>
                <div class="space-y-2">
                    @foreach(\App\Models\Branch::orderBy('name')->get() as $b)
                        <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-gray-50">
                            <input type="checkbox" name="branch_ids[]" value="{{ $b->id }}" class="branch-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-800">{{ $b->name }}</span>
                        </label>
                    @endforeach
                </div>
            </form>
        </div>
        <div class="p-5 border-t border-gray-200 flex justify-end gap-2">
            <button type="button" onclick="closeExportPdfModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancelar</button>
            <button type="button" onclick="submitExportPdf()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium">
                <i class="fas fa-file-pdf mr-2"></i> Gerar PDF
            </button>
        </div>
    </div>
</div>

<script>
function openExportPdfModal() {
    document.getElementById('modal-export-pdf').classList.remove('hidden');
    document.getElementById('modal-export-pdf').classList.add('flex');
}
function closeExportPdfModal() {
    document.getElementById('modal-export-pdf').classList.add('hidden');
    document.getElementById('modal-export-pdf').classList.remove('flex');
}
function toggleTodasCheckbox(checkbox) {
    const inputAll = document.getElementById('input-export-all');
    inputAll.value = checkbox.checked ? '1' : '0';
    if (checkbox.checked) {
        document.querySelectorAll('.branch-checkbox').forEach(function(cb) { cb.checked = false; });
    }
}
function selectAllBranches() {
    document.getElementById('checkbox-todas').checked = false;
    document.getElementById('input-export-all').value = '0';
    document.querySelectorAll('.branch-checkbox').forEach(function(cb) { cb.checked = true; });
}
function deselectAllBranches() {
    document.querySelectorAll('.branch-checkbox').forEach(function(cb) { cb.checked = false; });
}
function submitExportPdf() {
    const todasChecked = document.getElementById('checkbox-todas').checked;
    const branchChecked = document.querySelectorAll('.branch-checkbox:checked');
    if (!todasChecked && branchChecked.length === 0) {
        alert('Selecione "Todas" ou ao menos uma filial para gerar o PDF.');
        return;
    }
    document.getElementById('form-export-pdf').submit();
    closeExportPdfModal();
}
document.getElementById('modal-export-pdf')?.addEventListener('click', function(e) {
    if (e.target === this) closeExportPdfModal();
});
</script>
@endsection
