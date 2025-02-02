<?php

namespace App\Services\UploadFile\UploadFileItem;

use App\Contract\UploadFileContract;
use App\Contract\UploadFileItemContract;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;
use App\Models\UploadFileItem;
use Exception;
use Illuminate\Database\QueryException;

class FindItemsByUploadFileNameService
{
    public function __construct(
        private readonly UploadFileItemContract $repository,
        private readonly UploadFileContract $uploadFileRepository
    ) {}

    public function execute(string $uploadFileName, array $filter): array
    {
        $uploadFileAlreadyExists = $this->uploadFileRepository->findByName($uploadFileName);
        if (empty($uploadFileAlreadyExists)) {
            throw new NotFoundException('Arquivo Solicitado não Consta em Nossa Base de Dados');
        }

        if ($uploadFileAlreadyExists->upload_file_status_id === 1) {
            return [
                'message' => 'O Arquivo Está sendo Processado, Porfavor aguarde um instante.',
                'status' => 200
            ];
        }

        if ($uploadFileAlreadyExists->upload_file_status_id === 3) {
            throw new BadRequestException('O Arquivo apresenta falha no Processamento dos Itens, Porfavor, Reprocesse o Arquivo Novamente');
        }

        $uploadFileItems = $this->returnUploadFileItemConsult($filter, $uploadFileAlreadyExists->id);
        if (empty($uploadFileItems)) {
            throw new NotFoundException('Nenhum item foi encontrado para este arquivo em nossa base de dados.');
        }

        return [
            'message' => 'Itens Foram Encontrados com Sucesso',
            'arquivo' => $uploadFileAlreadyExists->name,
            'data' => $uploadFileItems
        ];
    }

    public function returnUploadFileItemConsult(array $filter, $uploadFileId): array
    {
        try {
            if ($filter) {
                $filterValid = UploadFileItem::getFilterableFields();
                $invalidFilters = array_diff_key($filter, array_flip($filterValid));

                if(!empty($invalidFilters)) {
                    throw new BadRequestException('Alguns parâmetros informados estão incorretos. Por favor, verifique os valores enviados e tente novamente.', array_keys($invalidFilters));
                }

                $filter = $this->removeItemNullableByFilter($filter);
                return $this->repository->getByUploadFileAndFilter($uploadFileId, $filter)->toArray();
            } 

            return $this->repository->getByUploadFileid($uploadFileId)->toArray();
        }catch (QueryException $e) {
            throw new ServerErrorException('Ocorreu um erro ao processar a consulta com os filtros fornecidos. Por favor, verifique se todos os campos foram preenchidos corretamente e tente novamente.');
        }
    }

    public function removeItemNullableByFilter(array $filter): array 
    {
        return array_filter($filter, function($value) {
            return $value !== null;
        });
    }
}
