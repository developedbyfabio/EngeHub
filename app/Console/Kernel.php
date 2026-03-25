<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // Verificar status dos cards a cada 1 minuto
        $schedule->command('cards:check-status')
            ->everyMinute()
            ->runInBackground();
            
        // Verificar status dos servidores a cada 1 minuto
        $schedule->command('servers:check-status')
            ->everyMinute()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 