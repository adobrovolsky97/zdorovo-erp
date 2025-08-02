<?php

namespace App\Jobs;

use App\Enum\ImportExport\Status;
use App\Models\Import\Import;
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
class HandleImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const IMPORT_PATH = 'imports';

    protected Import $import;

    /**
     * Create a new job instance.
     *
     * @param Import $import
     */
    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->import->update(['status' => Status::PENDING]);

        $importClass = $this->import->type->getImportClass();

        $import = new $importClass($this->import->params);

        try {
            $path = self::IMPORT_PATH.'/'.$this->import->getKey().'/'.$this->import->name;
            Excel::import($import, $this->import->file_path);
            $this->import->update([
                'status'    => Status::FINISHED,
                'file_path' => $path
            ]);
        } catch (Throwable $exception) {
            $this->import->update([
                'status' => Status::FAILED,
                'error'  => $exception->getMessage().' at '.$exception->getFile().' on line '.$exception->getLine()
            ]);
        }
    }
}
