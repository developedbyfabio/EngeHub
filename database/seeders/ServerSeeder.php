<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Server;
use App\Models\DataCenter;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar alguns data centers existentes
        $datacenters = DataCenter::all();
        $datacenterIds = $datacenters->pluck('id')->toArray();

        // Servidores de exemplo
        $servers = [
            // Grupo Yelll
            [
                'name' => 'Yelll-APP',
                'ip_address' => '10.40.132.2',
                'group_name' => 'Yelll',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Servidor principal da aplicação Yelll',
                'monitor_status' => true,
            ],
            [
                'name' => 'Yelll-DB',
                'ip_address' => '10.40.132.3',
                'group_name' => 'Yelll',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Banco de dados do sistema Yelll',
                'monitor_status' => true,
            ],
            [
                'name' => 'DEV-Yelll',
                'ip_address' => '10.40.132.19',
                'group_name' => 'Yelll',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Servidor de desenvolvimento Yelll',
                'monitor_status' => true,
            ],

            // Grupo Hardness
            [
                'name' => 'Hardness-APP',
                'ip_address' => '10.40.132.14',
                'group_name' => 'Hardness',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Aplicação principal do sistema Hardness',
                'monitor_status' => true,
            ],
            [
                'name' => 'Hardness-DB',
                'ip_address' => '10.40.132.20',
                'group_name' => 'Hardness',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Banco de dados do sistema Hardness',
                'monitor_status' => true,
            ],
            [
                'name' => 'Banco-Replica',
                'ip_address' => '10.40.132.28',
                'group_name' => 'Hardness',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Réplica do banco de dados Hardness',
                'monitor_status' => true,
            ],

            // Grupo Ecommerce
            [
                'name' => 'APP-Ecommerce',
                'ip_address' => '10.40.132.22',
                'group_name' => 'Ecommerce',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'Aplicação principal do e-commerce',
                'monitor_status' => true,
            ],
            [
                'name' => 'DB-Ecommerce',
                'ip_address' => '10.40.132.21',
                'group_name' => 'Ecommerce',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'Banco de dados do e-commerce',
                'monitor_status' => true,
            ],
            [
                'name' => 'API-Ecommerce',
                'ip_address' => '10.40.132.7',
                'group_name' => 'Ecommerce',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'API do sistema de e-commerce',
                'monitor_status' => true,
            ],
            [
                'name' => 'DEV-API-Ecommerce',
                'ip_address' => '10.40.132.13',
                'group_name' => 'Ecommerce',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'API de desenvolvimento do e-commerce',
                'monitor_status' => true,
            ],
            [
                'name' => 'Homolog Ecommerce',
                'ip_address' => '10.40.132.17',
                'group_name' => 'Ecommerce',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'Ambiente de homologação do e-commerce',
                'monitor_status' => true,
            ],
            [
                'name' => 'Elastic-Ecommerce',
                'ip_address' => '10.40.132.4',
                'group_name' => 'Ecommerce',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'Elasticsearch para busca no e-commerce',
                'monitor_status' => true,
            ],

            // Grupo EngChat
            [
                'name' => 'EngChat-DB',
                'ip_address' => '10.40.132.5',
                'group_name' => 'EngChat',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Banco de dados do sistema EngChat',
                'monitor_status' => true,
            ],
            [
                'name' => 'EngChat-APP',
                'ip_address' => '10.40.132.6',
                'group_name' => 'EngChat',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Aplicação do sistema de chat interno',
                'monitor_status' => true,
            ],

            // Infraestrutura
            [
                'name' => 'Fileserver',
                'ip_address' => '10.40.132.16',
                'group_name' => 'Infraestrutura',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Servidor de arquivos da empresa',
                'monitor_status' => true,
            ],
            [
                'name' => 'Gitlab',
                'ip_address' => '10.40.132.18',
                'group_name' => 'Infraestrutura',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Servidor GitLab para controle de versão',
                'monitor_status' => true,
            ],
            [
                'name' => 'NFe',
                'ip_address' => '10.40.132.27',
                'group_name' => 'Infraestrutura',
                'data_center_id' => $datacenterIds[0] ?? null,
                'description' => 'Servidor para processamento de NFe',
                'monitor_status' => true,
            ],

            // SAP
            [
                'name' => 'APP-SAP',
                'ip_address' => '10.40.132.9',
                'group_name' => 'SAP',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'Servidor de aplicação SAP',
                'monitor_status' => true,
            ],
            [
                'name' => 'API-SAP',
                'ip_address' => '10.40.132.10',
                'group_name' => 'SAP',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'API de integração com SAP',
                'monitor_status' => true,
            ],
            [
                'name' => 'DB-SAP',
                'ip_address' => '10.40.132.11',
                'group_name' => 'SAP',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'Banco de dados SAP',
                'monitor_status' => true,
            ],
            [
                'name' => 'Integração-SAP',
                'ip_address' => '10.40.132.23',
                'group_name' => 'SAP',
                'data_center_id' => $datacenterIds[1] ?? null,
                'description' => 'Servidor de integração SAP',
                'monitor_status' => true,
            ],
        ];

        foreach ($servers as $serverData) {
            Server::create($serverData);
        }

        $this->command->info('Servidores de exemplo criados com sucesso!');
        $this->command->info('Total de servidores criados: ' . count($servers));
    }
}