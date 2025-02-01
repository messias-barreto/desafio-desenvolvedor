<?php

namespace App\Services\UploadFile\UploadFileItem;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;

class FindItemsByUploadFileNameService
{
    public function __construct(
        private readonly UploadFileItemContract $repository,
        private readonly UploadFileContract $uploadFileRepository
    ) {}

    public function execute(string $uploadFileName): array 
    {
        $uploadFileAlreadyExists = $this->uploadFileRepository->findByName($uploadFileName);
        if(empty($uploadFileAlreadyExists)) {
            throw new NotFoundException('Arquivo Solicitado não Consta em Nossa Base de Dados');
        }

        if($uploadFileAlreadyExists->upload_file_status_id === 1) {
            return [
                'message' => 'O Arquivo Está sendo Processado, Porfavor aguarde um instante.',
                'status' => 200
            ];
        }

        if($uploadFileAlreadyExists->upload_file_status_id === 3) {
            throw new BadRequestException('O Arquivo apresenta falha no Processamento dos Itens, Porfavor, Reprocesse o Arquivo Novamente');
        }

        $uploadFileItems = $this->repository->getByUploadFileid($uploadFileAlreadyExists->id)->toArray();
        if(empty($uploadFileItems)) {
            throw new NotFoundException('Não foi Encontrado Nenhum Item para Esse Arquivo em Nossa Base de Dados');
        }

        return [
            'message' => 'Itens Foram Encontrados com Sucesso',
            'arquivo' => $uploadFileAlreadyExists->name,
            'data' => $uploadFileItems
        ];
    }
}
