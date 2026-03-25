<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Estatísticas do Formulário - {{ $form->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { font-size: 16px; margin: 0 0 8px 0; }
        p { font-size: 10px; margin: 0 0 8px 0; }
        h2 { font-size: 13px; margin: 12px 0 6px 0; }
        h3 { font-size: 11px; margin: 8px 0 4px 0; }
        table { width: 100%; border-collapse: collapse; margin: 8px 0; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; }
        th { background: #f0f0f0; font-weight: 600; }
        .page-section { page-break-after: always; padding-bottom: 20px; }
        .page-section:last-child { page-break-after: auto; }
        .card { margin: 8px 0; padding: 8px; border: 1px solid #ddd; background: #f9f9f9; }
        .grid-3 { display: table; width: 100%; }
        .grid-3 > div { display: table-cell; width: 33%; padding: 4px 8px; }
        .risk-green { background: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 4px; }
        .risk-yellow { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; }
        .risk-red { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 4px; }
        .bar-track { height: 10px; background: #e5e7eb; border-radius: 5px; overflow: hidden; margin: 4px 0; }
        .bar-fill { height: 100%; border-radius: 5px; }
        .legend-line { margin-top: 10px; padding-top: 8px; border-top: 1px solid #ddd; font-size: 9px; }
    </style>
</head>
<body>
    @foreach($pages as $idx => $page)
    @php $branch = $page['branch']; $stats = $page['stats']; @endphp
    <div class="page-section">
        <h1>Estatísticas do Formulário: {{ $form->title }}</h1>
        <p style="margin: 0 0 4px 0; font-weight: 600; font-size: 15px;">Filial: {{ $branch->name }}</p>
        <p style="margin: 0 0 12px 0; color: #666;">Gerado em {{ now()->format('d/m/Y H:i') }}</p>

        {{-- Resumo --}}
        <div class="grid-3">
            <div class="card">
                <strong>Total de respostas</strong><br>{{ $stats['total_responses'] ?? 0 }} formulários
            </div>
            <div class="card">
                <strong>Média geral</strong><br>{{ number_format($stats['general_average'] ?? 0, 2) }} (escala 1-5)
            </div>
            <div class="card">
                <strong>Classificação</strong><br>
                @php $r = $stats['risk_classification'] ?? ['label' => '-', 'color' => 'gray']; @endphp
                @if(($r['color'] ?? '') === 'green')<span class="risk-green">{{ $r['label'] }}</span>
                @elseif(($r['color'] ?? '') === 'yellow')<span class="risk-yellow">{{ $r['label'] }}</span>
                @elseif(($r['color'] ?? '') === 'red')<span class="risk-red">{{ $r['label'] }}</span>
                @else<span>{{ $r['label'] }}</span>@endif
            </div>
        </div>

        {{-- Indicador de risco --}}
        @if(($stats['general_average'] ?? 0) > 0)
        @php
            $avg = $stats['general_average'] ?? 0;
            $pct = min(100, max(0, (($avg - 1) / 4) * 100));
            $bg = match($stats['risk_classification']['color'] ?? '') {
                'green' => '#22c55e', 'yellow' => '#f59e0b', 'red' => '#ef4444', default => '#9ca3af'
            };
        @endphp
        <h3>Indicador de risco geral (escala 1 a 5)</h3>
        <div class="bar-track">
            <div class="bar-fill" style="width: {{ $pct }}%; background: {{ $bg }};"></div>
        </div>
        <table cellpadding="0" cellspacing="0" style="width: 100%; border: 0; table-layout: fixed; margin: 4px 0;">
            <tr>
                <td style="width: 20%; text-align: center; font-size: 9px;">1</td>
                <td style="width: 20%; text-align: center; font-size: 9px;">2</td>
                <td style="width: 20%; text-align: center; font-size: 9px;">3</td>
                <td style="width: 20%; text-align: center; font-size: 9px;">4</td>
                <td style="width: 20%; text-align: center; font-size: 9px;">5</td>
            </tr>
        </table>
        <p style="font-size: 9px; margin: 0;">Valor: {{ number_format($avg, 2) }} — Posição na escala indica o nível de risco psicossocial</p>
        <div class="legend-line">
            <strong>Legenda:</strong>
            <span class="risk-green">Baixo risco (1,00-2,00)</span> |
            <span class="risk-yellow">Moderado (2,01-3,50)</span> |
            <span class="risk-red">Alto (3,51-5,00)</span>
        </div>
        @endif

        {{-- Resultado por tema --}}
        @if(count($stats['by_theme'] ?? []) > 0)
        <h2>Resultado por tema</h2>
        <table>
            <thead>
                <tr>
                    <th>Tema</th>
                    <th style="text-align:center">Perguntas</th>
                    <th style="text-align:center">Pontos</th>
                    <th style="text-align:center">Média</th>
                    <th style="text-align:center">Risco</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['by_theme'] as $i => $d)
                @php $rc = $d['risk_classification'] ?? []; @endphp
                <tr>
                    <td>{{ $i + 1 }}. {{ $d['theme']->title ?? '-' }}</td>
                    <td style="text-align:center">{{ $d['questions_count'] ?? 0 }}</td>
                    <td style="text-align:center">{{ number_format($d['points'] ?? 0, 0) }}</td>
                    <td style="text-align:center">{{ number_format($d['average'] ?? 0, 2) }}</td>
                    <td style="text-align:center">
                        @if(($rc['color'] ?? '') === 'green')<span class="risk-green">{{ $rc['label'] ?? 'Baixo' }}</span>
                        @elseif(($rc['color'] ?? '') === 'yellow')<span class="risk-yellow">{{ $rc['label'] ?? 'Moderado' }}</span>
                        @elseif(($rc['color'] ?? '') === 'red')<span class="risk-red">{{ $rc['label'] ?? 'Alto' }}</span>
                        @else - @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- Perguntas críticas --}}
        @if(count($stats['critical_questions'] ?? []) > 0)
        <h2>{{ ($branch->name ?? '') === 'Todas' ? 'Todas as perguntas críticas' : 'TOP 10 perguntas críticas' }}</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pergunta</th>
                    <th>Tema</th>
                    <th style="text-align:center">Média</th>
                    <th>Indicador de risco</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats['critical_questions'] as $i => $d)
                @php $rc = $d['risk_classification'] ?? []; @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ Str::limit($d['question']->question_text ?? '-', 50) }}</td>
                    <td>{{ $d['theme']->title ?? '-' }}</td>
                    <td style="text-align:center">{{ number_format($d['average'] ?? 0, 2) }}</td>
                    <td>
                        @if(($rc['color'] ?? '') === 'green')<span class="risk-green">{{ $rc['label'] ?? 'Baixo' }}</span>
                        @elseif(($rc['color'] ?? '') === 'yellow')<span class="risk-yellow">{{ $rc['label'] ?? 'Moderado' }}</span>
                        @elseif(($rc['color'] ?? '') === 'red')<span class="risk-red">{{ $rc['label'] ?? 'Alto' }}</span>
                        @else - @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if(count($stats['by_theme'] ?? []) == 0 && count($stats['critical_questions'] ?? []) == 0)
        <p style="color: #666;">Nenhuma resposta registrada para esta filial.</p>
        @endif
    </div>
    @endforeach
</body>
</html>
