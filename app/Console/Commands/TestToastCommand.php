<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

class TestToastCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toast:test {--message= : Mensagem personalizada para o toast}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o sistema de toast notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = $this->option('message') ?: 'Logado com sucesso como Administrador!';
        
        // Simular uma sessão de sucesso
        Session::put('success', $message);
        
        $this->info('Mensagem de sucesso adicionada à sessão:');
        $this->line('"' . $message . '"');
        $this->newLine();
        $this->info('Agora acesse a página principal para ver o toast funcionando!');
        $this->line('URL: ' . url('/'));
    }
}
