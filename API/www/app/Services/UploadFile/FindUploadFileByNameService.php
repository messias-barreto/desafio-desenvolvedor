<?php

namespace App\Services\UploadFile;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileStatusContract;
use Carbon\Carbon;

class FindUploadFileByNameService
{
    public function __construct(
        private readonly UploadFileContract $repository,
        private readonly UploadFileStatusContract $uploadFileStatusRepository,
        private Carbon $formatedData
    ) {}

    public function execute(string $name): array
    {
        $uploadFileAlreadyExists = $this->repository->findByLikedName($name)->toArray();

        if (empty($uploadFileAlreadyExists)) {
            return [
                'message' => 'Não foi Encontrado Nenhum Arquivo com Esse Nome'
            ];
        }

        $uploadFileStatus = $this->uploadFileStatusRepository->getAll()->toArray();
        $formatedUploadFile = [];
        foreach ($uploadFileAlreadyExists as $uploadFile) {
            $formatedCreatedAt = $this->formatedData->parse($uploadFile['created_at']);
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
            'message' => 'Arquivo foi Encontrado com Sucesso!',
            'data' => $formatedUploadFile
        ];
    }
}
