{{-- Seletor de mapa de rede (Filiais). $compact: estilo barra do modal tela cheia (como Servidores). --}}
@php
    $p = $prefix ?? '';
    $isCompact = !empty($compact);
    $selectId = $p . 'filialMapSelect';
    $labelClass = $isCompact ? 'mr-1.5 text-xs font-medium text-gray-600' : 'text-sm font-medium text-gray-700 mr-2';
    $selectClass = $isCompact
        ? 'max-w-[14rem] border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm text-xs py-1.5'
        : 'min-w-[12rem] max-w-md border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm text-sm';
    $ctx = $context ?? 'main';
@endphp
@if(isset($maps) && $maps->count() > 1)
    <div class="flex flex-shrink-0 items-center" role="group" aria-label="Mapa de rede">
        <label for="{{ $selectId }}" class="{{ $labelClass }}">
            <i class="fas fa-map-marked-alt {{ $isCompact ? 'mr-0.5 sm:mr-1 text-amber-600' : 'mr-1 text-amber-600' }}"></i>
            @if($isCompact)
                <span class="hidden sm:inline">Mapa</span>
            @else
                Mapa de rede
            @endif
        </label>
        <select id="{{ $selectId }}"
                data-filiais-context="{{ $ctx }}"
                class="{{ $selectClass }}"
                onchange="window.filiaisNavigateMap(this)">
            @foreach($maps as $m)
                <option value="{{ $m->id }}" @selected((int) ($selectedMapId ?? 0) === (int) $m->id)>{{ $m->name }}</option>
            @endforeach
        </select>
    </div>
@endif
