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
        $schedule->command(FetchProductsFromFeedCommand::class)->everyTenMinutes()->withoutOverlapping();
        $schedule->command(SyncProductsCommand::class)->everyTenMinutes()->withoutOverlapping();
        $schedule->command('telescope:prune')->weekly();
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
