<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Checklist Câmeras - {{ $mostrarTodosDvrs ?? false ? 'Todos os DVRs' : ($checklist->dvrs->count() > 0 ? $checklist->dvrs->pluck('nome')->join(', ') : ($checklist->dvr?->nome ?? '')) }}</title>
    <style>
        @page { margin: 0; }
        html, body { margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        .cabecalho-img { margin: 0; padding: 0; width: 100%; max-width: 100%; height: auto; display: block; vertical-align: top; }
        .page-content { padding: 12px 15px; }
        h1 { font-size: 14px; margin: 0 0 8px 0; }
        h2 { font-size: 12px; margin: 12px 0 6px 0; }
        .dvr-page { page-break-after: always; }
        .dvr-page:last-child { page-break-after: auto; }
        .header { margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #333; }
        .header p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; vertical-align: top; }
        th { background: #e8e8e8; font-size: 9px; }
        td { font-size: 9px; }
        .img-thumb { max-width: 80px; max-height: 50px; object-fit: cover; }
        .img-evidencia { max-width: 120px; max-height: 80px; object-fit: cover; margin: 2px; border: 1px solid #ccc; }
        .evidencias-dvr { margin-top: 12px; padding-top: 8px; border-top: 1px dashed #999; }
        .evidencias-dvr h3 { font-size: 10px; margin: 0 0 6px 0; }
        .wrap-evidencias img { display: inline-block; vertical-align: top; }
        .sem-dados { color: #666; font-style: italic; }
    </style>
</head>
<body>
@php
    $itensPorDvr = $checklist->itens->groupBy(fn($i) => $i->camera->dvr_id);
    $dvrIds = $itensPorDvr->keys();
    $dvrs = \App\Models\Dvr::whereIn('id', $dvrIds)->get()->keyBy('id');
    $temPaginasDvr = false;
    $cabecalhoPath = public_path('cabecalho.png');
    $cabecalhoData = (file_exists($cabecalhoPath)) ? 'data:image/png;base64,' . base64_encode(file_get_contents($cabecalhoPath)) : '';
@endphp

@foreach($itensPorDvr as $dvrId => $itens)
    @php
        $itensComProblemaOuSolucao = $itens->filter(fn($i) => $i->problema || $i->descricao_problema || $i->acao_corretiva_necessaria || $i->acao_corretiva_realizada);
        $temPaginasDvr = true;
        $dvr = $dvrs[$dvrId] ?? null;
        $dvrNome = $dvr?->nome ?? "DVR {$dvrId}";
    @endphp
    <div class="dvr-page">
        @if($cabecalhoData)
        <img src="{{ $cabecalhoData }}" alt="" class="cabecalho-img" />
        @endif
        <div class="page-content">
        <div class="header">
            <h1>Checklist Câmeras - DVR: {{ $dvrNome }}</h1>
            <p><strong>Início:</strong> {{ $checklist->iniciado_em->format('d/m/Y H:i') }} | <strong>Finalização:</strong> {{ $checklist->finalizado_em?->format('d/m/Y H:i') ?? '-' }} | <strong>Responsável:</strong> {{ $checklist->responsavel }}</p>
        </div>

        <h2>Câmeras com problema ou solucionadas</h2>
        @if($itensComProblemaOuSolucao->isEmpty())
        <p class="sem-dados">Nenhuma câmera com problema encontrada.</p>
        @else
        <table>
            <thead>
                <tr>
                    <th>Câmera</th>
                    <th>Foto</th>
                    <th>Online?</th>
                    <th>Ângulo Correto?</th>
                    <th>Gravando?</th>
                    <th>Descrição Problema</th>
                    <th>Ação Corretiva</th>
                    <th>Solução</th>
                    <th>Evidência</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itensComProblemaOuSolucao as $item)
                @php
                    $fotoCamPath = $item->camera->foto ? storage_path('app/public/' . $item->camera->foto) : null;
                    $fotoCamData = ($fotoCamPath && file_exists($fotoCamPath)) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($fotoCamPath)) : '';
                    $anexosCamera = $checklist->anexos->where('camera_id', $item->camera_id);
                @endphp
                <tr>
                    <td>{{ $item->camera->nome }}</td>
                    <td>
                        @if($fotoCamData)
                        <img src="{{ $fotoCamData }}" alt="" class="img-thumb" />
                        @else
                        <span class="sem-dados">-</span>
                        @endif
                    </td>
                    <td>{{ $item->online === true ? 'Sim' : ($item->online === false ? 'Não' : '-') }}</td>
                    <td>{{ $item->angulo_correto === true ? 'Sim' : ($item->angulo_correto === false ? 'Não' : '-') }}</td>
                    <td>{{ $item->gravando === true ? 'Sim' : ($item->gravando === false ? 'Não' : '-') }}</td>
                    <td>{{ $item->descricao_problema ?? '-' }}</td>
                    <td>{{ $item->acao_corretiva_necessaria ?? '-' }}</td>
                    <td>{{ $item->acao_corretiva_realizada ?? '-' }}</td>
                    <td>
                        @if($anexosCamera->isNotEmpty())
                        <div class="wrap-evidencias">
                            @foreach($anexosCamera as $ax)
                                @php
                                    $imgPath = storage_path('app/public/' . $ax->caminho_arquivo);
                                    $mime = $ax->tipo_arquivo ?? 'image/jpeg';
                                    $imgData = file_exists($imgPath) ? 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($imgPath)) : '';
                                @endphp
                                @if($imgData)
                                <img src="{{ $imgData }}" alt="Evidência" class="img-evidencia" />
                                @endif
                            @endforeach
                        </div>
                        @else
                        <span class="sem-dados">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @php $anexosDvr = $checklist->anexos->where('dvr_id', $dvrId)->whereNull('camera_id'); @endphp
        @if($anexosDvr->isNotEmpty())
        <div class="evidencias-dvr">
            <h3>Evidências do DVR {{ $dvrNome }}</h3>
            <div class="wrap-evidencias" style="margin: 4px 0;">
                @foreach($anexosDvr as $anexo)
                    @php
                        $imgPath = storage_path('app/public/' . $anexo->caminho_arquivo);
                        $mime = $anexo->tipo_arquivo ?? 'image/jpeg';
                        $imgData = file_exists($imgPath) ? 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($imgPath)) : '';
                    @endphp
                    @if($imgData)
                    <img src="{{ $imgData }}" alt="Evidência DVR" style="max-width:100%; width:100%; height:auto; max-height:400px; object-fit:contain; margin:8px 0; border:1px solid #999;" />
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        </div>
    </div>
@endforeach

@if(!$temPaginasDvr)
@if($cabecalhoData)
<img src="{{ $cabecalhoData }}" alt="" class="cabecalho-img" />
@endif
<div class="page-content">
<div class="header">
    <h1>Checklist Câmeras - DVR: {{ $mostrarTodosDvrs ?? false ? 'Todos os DVRs' : ($checklist->dvrs->count() > 0 ? $checklist->dvrs->pluck('nome')->join(', ') : ($checklist->dvr?->nome ?? '-')) }}</h1>
    <p><strong>Início:</strong> {{ $checklist->iniciado_em->format('d/m/Y H:i') }} | <strong>Finalização:</strong> {{ $checklist->finalizado_em?->format('d/m/Y H:i') ?? '-' }} | <strong>Responsável:</strong> {{ $checklist->responsavel }}</p>
</div>
<p class="sem-dados">Nenhuma câmera com problema ou solucionada neste checklist.</p>
</div>
@endif
</body>
</html>
