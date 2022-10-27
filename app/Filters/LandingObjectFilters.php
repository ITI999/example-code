<?php

namespace App\Filters;

use Common\Service\Common\Filters\QueryFilters;

/**
 * Фильтры объектов посадки/высадки.
 *
 * @package App\Filters
 */
class LandingObjectFilters extends QueryFilters
{
    /**
     * Фильтрация по перевозчику.
     */
    public function withUuid(string|array $uuids)
    {
        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $this->builder->whereIn('uuid', $uuids);
    }

    /**
     * Фильтрация по наименованию объекта.
     */
    public function withName(string|array $names)
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        $this->builder->where('name', $names);
    }

    /**
     * Фильтрация по адресу.
     */
    public function withActualAddress(string|array $address)
    {
        if (!is_array($address)) {
            $address = [$address];
        }

        $this->builder->where('actual_address', $address);
    }

    /**
     * Фильтрация по идентификатору пользователя.
     */
    public function withUserUuid(string|array $uuids)
    {
        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $this->builder->whereIn('user_uuid', $uuids);
    }

    /**
     * Фильтрация по идентификатору муниципального образования.
     */
    public function withCommunalMunicipalityUuid(string|array $uuids)
    {
        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $this->builder->whereIn('communal_municipality_uuid', $uuids);
    }
}
