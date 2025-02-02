<?php

namespace App\Jobs;

use App\Services\UploadFile\Adapter\ProcessCsvFileAdapter;
use App\Services\UploadFile\Adapter\ProcessXlsFileAdapter;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FileUploadJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $data, 
        private string $type
    )
    {
        $this->onQueue('file_upload_queue');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->type === 'csv' ? $process = app(ProcessCsvFileAdapter::class) : $process = app(ProcessXlsFileAdapter::class);
            $process->processItem($this->data['fileName'], $this->data['uploadFileId']);
        } catch (Exception $e) {
            Log::error("Erro ao processar o arquivo: " . $e->getMessage());
        }
    }
}
