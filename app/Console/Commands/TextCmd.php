<?php

namespace App\Console\Commands;

use App\Jobs\SendXlsFileToManagerJob;
use App\Models\Package\Package;
use Illuminate\Console\Command;

class TextCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Package::query()->whereIn('id', [275, 277, 278]) as $package) {
            $this->info('sending package ' . $package->id);
            SendXlsFileToManagerJob::dispatch($package);
        }
    }
}
