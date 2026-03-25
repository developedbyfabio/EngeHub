<?php

namespace App\Console\Commands;

use App\Models\Server;
use Illuminate\Console\Command;

class CheckServerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servers:check-status {--server-id= : ID específico do servidor}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica o status online/offline dos servidores via ping';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $serverId = $this->option('server-id');
        
        if ($serverId) {
            $servers = Server::where('id', $serverId)->where('monitor_status', true)->get();
        } else {
            $servers = Server::where('monitor_status', true)->get();
        }

        if ($servers->isEmpty()) {
            $this->info('Nenhum servidor configurado para monitoramento encontrado.');
            return;
        }

        $this->info("Verificando status de {$servers->count()} servidor(es)...");
        
        $bar = $this->output->createProgressBar($servers->count());
        $bar->start();

        $onlineCount = 0;
        $offlineCount = 0;

        foreach ($servers as $server) {
            try {
                // Timeout de 10 segundos por servidor para evitar travamentos
                set_time_limit(10);
                $status = $server->checkStatus();
                
                if ($server->status === 'online') {
                    $onlineCount++;
                } else {
                    $offlineCount++;
                }
                
            } catch (\Exception $e) {
                // Se der erro, marcar como offline e continuar
                $server->update([
                    'status' => 'offline',
                    'last_status_check' => now(),
                    'response_time' => null
                ]);
                $offlineCount++;
                
                $this->newLine();
                $this->error("Erro ao verificar servidor {$server->name}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        // Resumo dos resultados
        $this->info("=== RESUMO DO MONITORAMENTO ===");
        $this->info("Total de servidores verificados: {$servers->count()}");
        $this->info("Servidores online: {$onlineCount}");
        $this->info("Servidores offline: {$offlineCount}");
        
        if ($offlineCount > 0) {
            $this->newLine();
            $this->warn("ATENÇÃO: {$offlineCount} servidor(es) offline detectado(s)!");
            
            $offlineServers = $servers->where('status', 'offline');
            foreach ($offlineServers as $server) {
                $this->warn("- {$server->name} ({$server->ip_address}) - Grupo: " . ($server->group_name ?: 'N/A'));
            }
        }
        
        $this->newLine();
        $this->info('Verificação de status concluída!');
        
        return $offlineCount > 0 ? 1 : 0; // Retorna 1 se houver servidores offline
    }
}