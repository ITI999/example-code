<?php

namespace App\Repositories;

use App\Exceptions\LandingObjectNotFoundException;
use App\Filters\LandingObjectFilters;
use App\Models\LandingObject as LandingObjectModel;
use Common\Eloquent\BaseRepository;
use Common\Exchange\Exception\UnknownErrorException;
use Common\Exchange\Exception\ValueObjectMapperNotFound;
use Common\Service\Common\Contracts\QueryFilters;
use Common\Service\Control\Models\LandingObject;
use Illuminate\Validation\ValidationException;

/**
 * Репозиторий объектов посадки/высадки.
 *
 * @package App\Repository
 */
class LandingObjectRepository extends BaseRepository
{
    /**
     * Получение класса модели.
     *
     * @return string
     */
    protected function model(): string
    {
        return LandingObjectModel::class;
    }

    /**
     * Получение класса ошибки поиска записи.
     *
     * @return string
     */
    protected function modelNotFoundException(): string
    {
        return LandingObjectNotFoundException::class;
    }

    /**
     * Получение объекта фильтров модели.
     *
     * @return QueryFilters|null
     */
    protected function modelFilters()
    {
        return new LandingObjectFilters();
    }

    /**
     * Создание записи.
     *
     * @param LandingObject $payload
     *
     * @return LandingObjectModel
     * @throws ValidationException
     * @throws ValueObjectMapperNotFound
     */
    public function create(LandingObject $payload): LandingObjectModel
    {
        $this->validate($payload);

        return \DB::transaction(function () use ($payload) {
            $object = new LandingObjectModel;
            $object->fill(mapToModel($payload)->getAttributes());
            $object->save();

            return $object;
        });
    }

    /**
     * Изменение записи.
     *
     * @param LandingObject $payload
     *
     * @return LandingObjectModel
     * @throws UnknownErrorException
     * @throws ValidationException
     * @throws ValueObjectMapperNotFound
     */
    public function update(LandingObject $payload): LandingObjectModel
    {
        $this->validate($payload, true);

        return \DB::transaction(function () use ($payload) {
            $object = $this->find($payload->uuid);

            $attributes = mapToModel($payload)->getAttributes();

            $object->fill($attributes);
            $object->save();

            return $object;
        });
    }

    /**
     * Удаление записи.
     *
     * @param string $uuid
     *
     * @return LandingObjectModel
     * @throws UnknownErrorException
     */
    public function delete(string $uuid): LandingObjectModel
    {
        return \DB::transaction(function () use ($uuid) {
            $object = $this->find($uuid);
            $object->delete();

            return $object;
        });
    }

    /**
     * Валидация.
     *
     * @param LandingObject $payload
     * @param bool $exists
     * @throws ValidationException
     */
    protected function validate(LandingObject $payload, bool $exists = false)
    {
        validate([
            'object' => mapToArray($payload),
        ], [
            'object.uuid'                       => $exists ? 'required|string|max:255' : 'nullable',
            'object.user_uuid'                  => 'nullable|string|max:255',
            'object.name'                       => 'nullable|string|max:255',
            'object.actual_address'             => 'nullable|string|max:255',
            'object.communal_municipality_uuid' => 'nullable|string|max:255',
            'object.latitude'                   => 'nullable|numeric',
            'object.longitude'                  => 'nullable|numeric',
        ], [], []);
    }
}
