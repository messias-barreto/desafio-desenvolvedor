<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileStatusContract;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class FindUploadFileByCreatedDateService
{
    public function __construct(
        private readonly UploadFileContract $repository,
        private readonly UploadFileStatusContract $uploadFileStatusRepository,
        private readonly Carbon $validateDate
    ) {}

    public function execute(string $createdDate): array 
    {
        $dateIsValid = $this->isValidDate($createdDate);
        if(empty($dateIsValid)) {
            throw new BadRequestException('A Data Passada Não é Válida, ou Não está no formato Solicitado Y-m-d');
        }

        $uploadFileAlreadyExists = $this->repository->findByCreatedDate($createdDate)->toArray();
        if(empty($uploadFileAlreadyExists)) {
            throw new NotFoundException('Não foi Encontrado Nenhum Documento Adicionando no Dia Solicitado');
        }

        $uploadFileStatus = $this->uploadFileStatusRepository->getAll()->toArray();
        $formatedUploadFile = [];

        foreach ($uploadFileAlreadyExists as $uploadFile) {
            $formatedCreatedAt = $this->validateDate->parse($uploadFile['created_at']);
            $statusName = array_filter($uploadFileStatus, function ($status) use ($uploadFile) {
                return $status['id'] === $uploadFile['upload_file_status_id'];
            });

            $statusName = array_values($statusName);
            $status = !empty($statusName) ? $statusName[0]['name'] : 'Status Desconhecido';

            $formatedUploadFile[] = [
                'nome' => $uploadFile['name'],
                'status' => $status,
                'data_criacao' => $formatedCreatedAt->format('d-m-Y H:i:s')
            ];
        }

        return [
            'message' => 'Documento(s) foram Encontrado(s)',
            'data' => $formatedUploadFile
        ];
    }

    function isValidDate($date, $format = 'Y-m-d') {
        try {
            $parsedDate = $this->validateDate->createFromFormat($format, $date);
            return $parsedDate && $parsedDate->format($format) === $date;
        } catch (InvalidFormatException $e) {
            return false;
        }
    }
}
