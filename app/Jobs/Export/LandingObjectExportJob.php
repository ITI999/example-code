<?php


namespace App\Jobs\Export;

use App\Models\LandingObject;
use App\Repositories\LandingObjectRepository;

class LandingObjectExportJob extends ExportJob
{
    protected function getRepository(): LandingObjectRepository
    {
        return app(LandingObjectRepository::class);
    }

    protected function getRowsAndHeaders(): array
    {
        $repository = $this->getRepository();
        $query = $repository->query()->withUserInfo()->withCommunalMunicipalities();
        $query = $repository->queryByRequestMetaWithQuery($query, $this->meta);

        $rows  = [];
        $limit = 1000;
        $page  = 1;

        while (true) {
            $objects = $query->forPage($page, $limit)->get();

            if (0 == count($objects)) {
                break;
            }

            $newRows = $objects->map(function (LandingObject $object) {
                 return $this->getRow($object);
            })->toArray();

            $rows = array_merge($rows, $newRows);
            ++$page;
        }

        $headers = $this->getHeaders();

        return [$rows, $headers];
    }

    protected function getHeaders(): array
    {
        return [
            'Пользователь',
            'Наименование объекта',
            'Адрес',
            'Муниципальное образование',
        ];
    }

    protected function getRow(LandingObject $object): array
    {
        return [
            data_get($object, 'user_name'),
            data_get($object, 'name'),
            data_get($object, 'actual_address'),
            data_get($object, 'communal_municipality_name'),
        ];
    }
}
