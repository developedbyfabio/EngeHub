<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\DataCenter;
use App\Models\ServerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ServerController extends Controller
{
    /**
     * Exibe a página pública de servidores
     */
    public function index(Request $request)
    {
        $datacenters = DataCenter::orderBy('name')->get();
        $serverGroups = ServerGroup::active()->ordered()->get();

        $query = Server::with(['dataCenter', 'serverGroup'])
            ->where('monitor_status', true);

        if ($request->filled('datacenter_id')) {
            $query->where('data_center_id', $request->datacenter_id);
        }
        if ($request->filled('operating_system')) {
            $query->where('operating_system', $request->operating_system);
        }
        if ($request->filled('server_group_id')) {
            $query->where('server_group_id', $request->server_group_id);
        }

        $servers = $query->orderBy('name')->get();
        $serversByDatacenter = $this->buildServersByDatacenter($servers, $datacenters);

        $serversAll = Server::with(['dataCenter', 'serverGroup'])
            ->where('monitor_status', true)
            ->orderBy('name')
            ->get();
        $serversByDatacenterFullscreen = $this->buildServersByDatacenter($serversAll, $datacenters);
        $serversAllCount = $serversAll->count();

        $serversJson = [];
        foreach ($serversAll as $s) {
            $serversJson[(string) $s->id] = [
                'id' => $s->id,
                'name' => $s->name,
                'ip_address' => $s->ip_address,
                'logo_url' => $s->logo_url,
                'status_text' => $s->status_text,
                'status_class' => $s->status_class,
                'data_center' => $s->dataCenter?->name,
                'group' => $s->serverGroup?->name,
                'operating_system' => $s->operating_system,
                'description' => $s->description,
                'webmin_url' => $s->webmin_url,
                'nginx_url' => $s->nginx_url,
                'response_time' => $s->response_time,
                'last_status_check' => $s->last_status_check?->format('d/m/Y H:i'),
                'monitor_status' => (bool) $s->monitor_status,
            ];
        }

        if (count($serversJson) === 0) {
            $serversJson = new \stdClass();
        }

        $selectedDatacenter = $request->datacenter_id;
        $selectedOperatingSystem = $request->operating_system;
        $selectedServerGroup = $request->server_group_id;

        return view('servers.index', compact(
            'servers',
            'serversAllCount',
            'serversByDatacenter',
            'serversByDatacenterFullscreen',
            'serversJson',
            'datacenters',
            'serverGroups',
            'selectedDatacenter',
            'selectedOperatingSystem',
            'selectedServerGroup'
        ));
    }

    /**
     * Agrupa servidores por data center e por nome de grupo (balões).
     *
     * @return array<int, array{id: int|null, name: string, groups: Collection<string, Collection<int, Server>>}>
     */
    private function buildServersByDatacenter(Collection $servers, Collection $datacenters): array
    {
        $serversByDatacenter = [];
        $shownDataCenterIds = [];

        foreach ($datacenters as $dc) {
            $bucket = $servers->where('data_center_id', $dc->id);
            if ($bucket->isEmpty()) {
                continue;
            }
            $shownDataCenterIds[] = (int) $dc->id;
            $groups = $bucket->groupBy(function (Server $server) {
                return $server->serverGroup ? $server->serverGroup->name : 'Outros';
            })->sortKeys(SORT_NATURAL | SORT_FLAG_CASE);

            $serversByDatacenter[] = [
                'id' => (int) $dc->id,
                'name' => $dc->name,
                'groups' => $groups,
            ];
        }

        $orphanByDc = $servers
            ->filter(function (Server $server) use ($shownDataCenterIds) {
                return $server->data_center_id && ! in_array((int) $server->data_center_id, $shownDataCenterIds, true);
            })
            ->groupBy('data_center_id')
            ->sortKeys();

        foreach ($orphanByDc as $dcId => $bucket) {
            $name = $bucket->first()->dataCenter?->name ?? ('Data center #' . $dcId);
            $groups = $bucket->groupBy(function (Server $server) {
                return $server->serverGroup ? $server->serverGroup->name : 'Outros';
            })->sortKeys(SORT_NATURAL | SORT_FLAG_CASE);
            $serversByDatacenter[] = [
                'id' => (int) $dcId,
                'name' => $name,
                'groups' => $groups,
            ];
        }

        $noDataCenter = $servers->whereNull('data_center_id');
        if ($noDataCenter->isNotEmpty()) {
            $groups = $noDataCenter->groupBy(function (Server $server) {
                return $server->serverGroup ? $server->serverGroup->name : 'Outros';
            })->sortKeys(SORT_NATURAL | SORT_FLAG_CASE);

            $serversByDatacenter[] = [
                'id' => null,
                'name' => 'Sem Data Center',
                'groups' => $groups,
            ];
        }

        return $serversByDatacenter;
    }

    /**
     * Verifica o status de um servidor específico
     */
    public function checkStatus(Server $server)
    {
        try {
            $result = $server->checkStatus();

            return response()->json([
                'success' => true,
                'status' => $server->status,
                'status_text' => $server->status_text,
                'status_class' => $server->status_class,
                'response_time' => $server->response_time,
                'last_check' => $server->last_status_check?->format('d/m/Y H:i'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao verificar status do servidor',
            ], 500);
        }
    }
}
