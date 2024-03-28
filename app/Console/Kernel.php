<?php

namespace App\Console;

use App\Console\Commands\HandlePullRequestUpdates;
use App\Console\Commands\ServiceCommands\FetchIssueComments;
use App\Console\Commands\ServiceCommands\FetchNewIssues;
use App\Console\Commands\ServiceCommands\FetchOpenPullRequest;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(FetchNewIssues::class)->everyTenMinutes();
        $schedule->command(FetchIssueComments::class)->everyTenMinutes();

        $schedule->command(FetchOpenPullRequest::class)->everyTenMinutes();
        $schedule->command(HandlePullRequestUpdates::class)->everyTenMinutes();
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
