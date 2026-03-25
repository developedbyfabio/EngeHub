@props(['title' => null, 'icon' => 'fas fa-cog'])

{{-- Componente padronizado de header para páginas administrativas (referência: Mapas de Rede) --}}
<div class="flex justify-between items-center">
    @isset($left)
        <div>{{ $left }}</div>
    @else
        <div class="flex flex-col">
            @isset($beforeTitle)
                <div class="leading-tight mb-0.5">{{ $beforeTitle }}</div>
            @endisset
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="{{ $icon }} mr-2 flex-shrink-0" style="color: #E9B32C; font-size: 1.25rem;"></i>
                {{ $title ?? '' }}
            </h2>
        </div>
    @endisset
    @isset($actions)
        <div class="flex items-center gap-3 flex-wrap justify-end">
            {{ $actions }}
        </div>
    @endisset
</div>
