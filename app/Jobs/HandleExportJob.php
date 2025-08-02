<?php

namespace App\Jobs;

use App\Enum\ImportExport\Status;
use App\Models\Export\Export;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

/**
 * Class HandleExportJob
 */
class HandleExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const EXPORT_PATH = 'exports';

    protected Export $export;

    /**
     * Create a new job instance.
     *
     * @param Export $export
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->export->update(['status' => Status::PENDING]);

        $exportClass = $this->export->type->getExportClass();

        $export = new $exportClass($this->export->params);

        try {
            $path = self::EXPORT_PATH.'/'.$this->export->getKey().'/'.$this->export->name;
            Excel::store($export, $path, 'public');
            $this->export->update([
                'status'    => Status::FINISHED,
                'file_path' => $path
            ]);
        } catch (Throwable $exception) {
            $this->export->update([
                'status' => Status::FAILED,
                'error'  => $exception->getMessage().' at '.$exception->getFile().' on line '.$exception->getLine()
            ]);
        }
    }
}
