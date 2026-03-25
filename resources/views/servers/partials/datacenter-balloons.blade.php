{{-- $headingPrefix: '' ou 'fs-' para ids de cabeçalho acessíveis únicos --}}
@php
    $hp = $headingPrefix ?? '';
@endphp
@foreach($serversByDatacenter as $dcBlock)
    @php
        $fsDcId = array_key_exists('id', $dcBlock) && $dcBlock['id'] !== null ? (string) $dcBlock['id'] : '';
    @endphp
    <section class="servers-fs-dc-section mb-14 last:mb-4 border border-gray-200 rounded-2xl p-6 sm:p-8 bg-white shadow-sm"
             data-fs-dc-id="{{ $fsDcId }}"
             aria-labelledby="{{ $hp }}dc-heading-{{ $loop->index }}">
        <h2 id="{{ $hp }}dc-heading-{{ $loop->index }}" class="text-xl sm:text-2xl font-bold text-gray-900 mb-8 flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 border border-gray-300 text-gray-700">
                <i class="fas fa-server text-lg"></i>
            </span>
            {{ $dcBlock['name'] }}
        </h2>

        <div class="server-balloon-groups-sortable flex flex-wrap gap-10 justify-start items-start"
             data-dc-sort-key="{{ md5($dcBlock['name']) }}"
             title="Arraste pelo ícone no topo de cada balão para mover o grupo (ordem salva neste navegador)">
            @foreach($dcBlock['groups'] as $groupName => $groupServers)
                <div class="balloon-cluster server-balloon-cluster-item flex w-full flex-col sm:w-auto sm:min-w-[260px] sm:max-w-[520px] flex-1 sm:flex-none"
                     data-balloon-key="{{ md5($dcBlock['name'] . '|' . $groupName) }}">
                    <div class="balloon-body flex w-full flex-col overflow-hidden rounded-[1.75rem] border-2 border-gray-800 bg-gray-50/90 shadow-inner">
                        <div class="balloon-inner-header flex items-center gap-2 border-b border-gray-800/25 bg-white/95 px-3 py-2.5">
                            <button type="button"
                                    class="balloon-cluster-drag-handle flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-500 shadow-sm hover:border-amber-500 hover:text-amber-700 cursor-grab active:cursor-grabbing"
                                    aria-label="Arrastar balão do grupo"
                                    title="Arrastar balão do grupo">
                                <i class="fas fa-grip-vertical text-xs" aria-hidden="true"></i>
                            </button>
                            <h3 class="balloon-label min-w-0 flex-1 text-center text-sm font-bold leading-snug text-gray-900">
                                {{ $groupName }}
                            </h3>
                            <span class="h-8 w-8 flex-shrink-0" aria-hidden="true"></span>
                        </div>
                        <div class="server-sortable-list flex flex-wrap gap-3 justify-center px-3 py-4 sm:px-4 sm:py-5"
                             data-sort-key="{{ md5($dcBlock['name'] . '|' . $groupName) }}"
                             title="Arraste pelo ícone ⋮⋮ em cada servidor para reordenar (ordem salva neste navegador)">
                            @foreach($groupServers as $server)
                                <div class="server-tile-sortable-item relative w-[118px] sm:w-[128px] flex-shrink-0"
                                     data-server-id="{{ $server->id }}"
                                     data-fs-dc-id="{{ $server->data_center_id ?? '' }}"
                                     data-fs-os="{{ $server->operating_system ?? '' }}"
                                     data-fs-group-id="{{ $server->server_group_id ?? '' }}">
                                    <button type="button"
                                            class="server-tile-drag-handle absolute right-1 top-1 z-10 flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-white/95 text-gray-400 shadow-sm hover:border-amber-300 hover:text-amber-700 cursor-grab active:cursor-grabbing"
                                            aria-label="Arrastar para reordenar"
                                            title="Arrastar para reordenar">
                                        <i class="fas fa-grip-vertical text-xs" aria-hidden="true"></i>
                                    </button>
                                    <button type="button"
                                            onclick="openServerDetailModal({{ $server->id }})"
                                            class="server-tile group flex w-full flex-col items-center rounded-xl border border-gray-300 bg-white p-3 text-center shadow-sm transition hover:border-amber-500/80 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2">
                                        @if($server->logo_url)
                                            <img src="{{ $server->logo_url }}"
                                                 alt=""
                                                 class="mb-2 h-14 w-14 object-contain">
                                        @else
                                            <div class="mb-2 flex h-14 w-14 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                                                <i class="fas fa-server text-2xl"></i>
                                            </div>
                                        @endif
                                        <span class="line-clamp-2 text-xs font-bold leading-tight text-gray-900 group-hover:text-gray-800">{{ $server->name }}</span>
                                        <span class="mt-1.5 font-mono text-[11px] text-gray-600">{{ $server->ip_address }}</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endforeach
