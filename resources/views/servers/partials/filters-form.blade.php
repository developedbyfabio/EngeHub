{{-- Filtros GET (página) ou só cliente (modal tela cheia). $prefix: '' ou 'fs_' --}}
@php
    $p = $prefix ?? '';
    $clientFs = !empty($clientSideOnlyFullscreen);
    $formId = $p === 'fs_' ? 'serversFiltersFormFs' : 'serversFiltersForm';
    $labelClass = $clientFs ? 'mr-1.5 text-xs font-medium text-gray-600' : 'text-sm font-medium text-gray-700 mr-2';
    $selectClass = $clientFs
        ? 'max-w-[10rem] border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-xs py-1.5'
        : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm';
    $onchangeAttr = $clientFs ? 'onchange="window.applyServersFullscreenFilters?.()"' : 'onchange="this.form.submit()"';
    $optAllDc = $clientFs ? 'Todos' : 'Todos os Datacenters';
    $optAllOs = $clientFs ? 'Todos' : 'Todos os Sistemas';
    $optAllGrp = $clientFs ? 'Todos' : 'Todos os Grupos';
@endphp
@if($clientFs)
<div id="{{ $formId }}" role="group" aria-label="Filtros da vista em tela cheia" class="flex flex-wrap items-center gap-x-2 gap-y-1.5 sm:gap-x-3">
@else
<form method="GET" action="{{ route('servers.index') }}" id="{{ $formId }}" class="flex min-w-0 flex-wrap items-end gap-4 sm:items-center">
@endif
    @if($datacenters->count() > 0)
        <div class="flex flex-shrink-0 items-center">
            <label for="{{ $p }}datacenter_id" class="{{ $labelClass }}">
                <i class="fas fa-building {{ $clientFs ? 'mr-0.5 sm:mr-1' : 'mr-1' }}"></i>
                @if($clientFs)<span class="hidden sm:inline">DC</span>@else Datacenter @endif
            </label>
            <select @unless($clientFs) name="datacenter_id" @endunless
                    id="{{ $p }}datacenter_id"
                    {!! $onchangeAttr !!}
                    class="{{ $selectClass }}">
                <option value="">{{ $optAllDc }}</option>
                @foreach($datacenters as $datacenter)
                    <option value="{{ $datacenter->id }}"
                            {{ $selectedDatacenter == $datacenter->id ? 'selected' : '' }}>
                        {{ $datacenter->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="flex flex-shrink-0 items-center">
        <label for="{{ $p }}operating_system" class="{{ $labelClass }}">
            <i class="fas fa-desktop {{ $clientFs ? 'mr-0.5 sm:mr-1' : 'mr-1' }}"></i>
            @if($clientFs)<span class="hidden sm:inline">SO</span>@else Sistema Operacional @endif
        </label>
        <select @unless($clientFs) name="operating_system" @endunless
                id="{{ $p }}operating_system"
                {!! $onchangeAttr !!}
                class="{{ $selectClass }}">
            <option value="">{{ $optAllOs }}</option>
            <option value="Linux" {{ $selectedOperatingSystem == 'Linux' ? 'selected' : '' }}>Linux</option>
            <option value="Windows" {{ $selectedOperatingSystem == 'Windows' ? 'selected' : '' }}>Windows</option>
            <option value="Outros" {{ $selectedOperatingSystem == 'Outros' ? 'selected' : '' }}>Outros</option>
        </select>
    </div>

    @if($serverGroups->count() > 0)
        <div class="flex flex-shrink-0 items-center">
            <label for="{{ $p }}server_group_id" class="{{ $labelClass }}">
                <i class="fas fa-folder {{ $clientFs ? 'mr-0.5 sm:mr-1' : 'mr-1' }}"></i>
                Grupo
            </label>
            <select @unless($clientFs) name="server_group_id" @endunless
                    id="{{ $p }}server_group_id"
                    {!! $onchangeAttr !!}
                    class="{{ $selectClass }}">
                <option value="">{{ $optAllGrp }}</option>
                @foreach($serverGroups as $group)
                    <option value="{{ $group->id }}"
                            {{ $selectedServerGroup == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    @if($clientFs)
        <button type="button" id="serversFsClearFiltersBtn"
                class="whitespace-nowrap rounded-md border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1 sm:px-3 sm:py-2 sm:text-sm">
            Limpar Filtros
        </button>
        <span id="serversFsFilterSummary" class="hidden text-xs text-gray-500"></span>
    @endif
@if($clientFs)
</div>
@else
</form>
@endif
