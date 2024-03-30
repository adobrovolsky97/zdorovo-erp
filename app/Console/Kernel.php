<?php

namespace App\Console;

use App\Console\Commands\FetchProductsFromFeedCommand;
use App\Console\Commands\SyncProductsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(FetchProductsFromFeedCommand::class)->hourly();
        $schedule->call(SyncProductsCommand::class)->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
