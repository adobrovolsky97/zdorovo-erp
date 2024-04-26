<?php

namespace App\Jobs;

use App\Exports\PackageExport;
use App\Mail\ExcelMail;
use App\Models\Package\Package;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class SendXlsFileToManagerJob
 */
class SendXlsFileToManagerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Package
     */
    private Package $package;

    /**
     * Create a new job instance.
     * @param Package $package
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (empty(config('bimpsoft.package_mails'))) {
            return;
        }

        $mails = explode(',', config('bimpsoft.package_mails'));

        $filePath = 'public/exports/' . $this->package->id . '/Фасовка за ' . Carbon::today()->format('d.m.Y') . '.xlsx';

        Excel::store(new PackageExport($this->package), $filePath);

        Mail::to($mails)->send(new ExcelMail($filePath));

        Storage::deleteDirectory('public/exports/' . $this->package->id);
    }
}
