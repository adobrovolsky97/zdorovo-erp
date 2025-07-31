<?php

namespace App\Console\Commands;

use App\Imports\OrderImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class FillPreviousOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'previous-orders:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Filling prev orders from xls file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(!file_exists(public_path('xls/90 днів.xlsx'))){
            $this->error('File not found: ' . public_path('xls/90 днів.xlsx'));
            return;
        }


        Excel::import(new OrderImport(), public_path('xls/90 днів.xlsx'));
        $this->info('Finished importing previous orders from xls file.');
    }
}
