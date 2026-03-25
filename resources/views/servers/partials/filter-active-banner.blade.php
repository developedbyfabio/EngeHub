@if($selectedDatacenter || $selectedOperatingSystem || $selectedServerGroup)
    @php
        $totalServers = $servers->count();
        $filters = [];
        if ($selectedDatacenter) {
            $selectedDatacenterName = $datacenters->firstWhere('id', $selectedDatacenter)->name ?? 'Datacenter';
            $filters[] = "datacenter <strong>{$selectedDatacenterName}</strong>";
        }
        if ($selectedOperatingSystem) {
            $filters[] = "sistema <strong>{$selectedOperatingSystem}</strong>";
        }
        if ($selectedServerGroup) {
            $selectedGroupName = $serverGroups->firstWhere('id', $selectedServerGroup)->name ?? 'Grupo';
            $filters[] = "grupo <strong>{$selectedGroupName}</strong>";
        }
        $filtersText = implode(' e ', $filters);
    @endphp
    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            <span class="text-sm text-blue-800">
                Mostrando <strong>{{ $totalServers }} {{ $totalServers === 1 ? 'servidor' : 'servidores' }}</strong>
                do {!! $filtersText !!}
            </span>
        </div>
    </div>
@endif
