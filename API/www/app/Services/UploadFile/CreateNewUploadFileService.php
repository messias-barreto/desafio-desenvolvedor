<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;

class CreateNewUploadFileService
{
    public function __construct(
        private readonly UploadFileContract $repository
    ) {}

    public function execute(array $data): array 
    {
        return [];
    }
}
