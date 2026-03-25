<?php

namespace App\Console\Commands;

use App\Models\Card;
use Illuminate\Console\Command;

class CheckCardStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cards:check-status {--card-id= : ID específico do card}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica o status online/offline dos cards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cardId = $this->option('card-id');
        
        if ($cardId) {
            $cards = Card::where('id', $cardId)->where('monitor_status', true)->get();
        } else {
            $cards = Card::where('monitor_status', true)->get();
        }

        if ($cards->isEmpty()) {
            $this->info('Nenhum card configurado para monitoramento encontrado.');
            return;
        }

        $this->info("Verificando status de {$cards->count()} card(s)...");
        
        $bar = $this->output->createProgressBar($cards->count());
        $bar->start();

        foreach ($cards as $card) {
            try {
                // Timeout de 5 segundos por card para evitar travamentos
                set_time_limit(5);
                $status = $card->checkStatus();
            } catch (\Exception $e) {
                // Se der erro, marcar como offline e continuar
                $card->update([
                    'status' => 'offline',
                    'last_status_check' => now(),
                    'response_time' => null
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Verificação de status concluída!');
    }
} 