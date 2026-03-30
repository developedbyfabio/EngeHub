<?php

namespace App\Console\Commands;

use App\Models\DvrFoto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearDvrFotos extends Command
{
    protected $signature = 'dvr-fotos:clear {--force : Executa sem pedir confirmação}';

    protected $description = 'Remove todos os registros e arquivos de fotos anexadas aos DVRs (não altera DVRs, câmeras ou outras tabelas)';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Apagar todas as fotos dos DVRs (arquivos em disco + registros)?')) {
            $this->warn('Operação cancelada.');

            return self::SUCCESS;
        }

        $total = DvrFoto::count();
        $this->info("Registros encontrados: {$total}.");

        DvrFoto::query()->orderBy('id')->each(function (DvrFoto $foto) {
            $disk = $foto->disk ?: 'public';
            if ($foto->path && Storage::disk($disk)->exists($foto->path)) {
                Storage::disk($disk)->delete($foto->path);
            }
        });

        $removed = DvrFoto::query()->delete();
        $this->info("Concluído: {$removed} registro(s) removido(s) e arquivos apagados quando existiam.");

        return self::SUCCESS;
    }
}
