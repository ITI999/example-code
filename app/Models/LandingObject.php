<?php

namespace App\Models;

use Carbon\Carbon;
use Common\Eloquent\BaseModel;
use Common\Service\Auth\Model\User;
use Common\Service\Auth\Payload\Users;
use Common\Service\Common\Model\Order;
use Common\Service\Common\Model\PaginationRequest;
use Common\Service\Common\Model\RequestMeta;
use Common\Service\Geo\Payload\GetUserGeoObjectPayload;
use Common\Service\Geo\Payload\UserGeoObjectPayload;
use Common\Service\System\Traits\Auditable;

/**
 * Объекты посадки/высадки.
 *
 * @property string $uuid                           Идентификатор
 * @property string $user_uuid                      Идентификатор пользователя
 * @property string $name                           Наименование объекта
 * @property string $actual_address                 Адрес
 * @property string $communal_municipality_uuid     Муниципальное образование
 * @property float  $latitude                       Широта
 * @property float  $longitude                      Долгота
 *
 * @property Carbon $created_at                     Дата создания
 * @property Carbon $updated_at                     Дата обновления
 * @property Carbon $deleted_at                     Дата удаления
 */
class LandingObject extends BaseModel
{
    use Auditable;

    const EXPORT_ENTITY = 'landing_objects';

    protected $table = 'landing_objects';

    protected $fillable = [
        'user_uuid',
        'name',
        'actual_address',
        'communal_municipality_uuid',
        'latitude',
        'longitude',
    ];

    protected array $keepLogOf = [
        'user_uuid',
        'name',
        'actual_address',
        'communal_municipality_uuid',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    /*******************************************************
     * Scopes
     *******************************************************/

    /**
     * Присоединение к запросу имен пользователей
     *
     * @param $query
     */
    public function scopeWithUserInfo($query): void
    {
        $meta = new RequestMeta();
        $meta->response_data = [
            "items/info/name",
            "items/info/second_name",
            "items/info/surname",
            "items/uuid",
        ];
        $meta->order = new Order();
        $meta->pagination = new PaginationRequest();
        $meta->pagination->page = 1;
        $meta->pagination->limit = 999999;
        $meta->filters = [
            'withUuid' => LandingObject::pluck('user_uuid')->unique()->toArray()
        ];

        $users = exchange()->request('com.rnis.auth.action.user.list')
            ->withSystemAuth()
            ->withHeader('meta', $meta)
            ->send(Users::class)
            ->payload
            ?->items;

        if (!$users) {
            return;
        }

        $values = "(VALUES " .
            collect($users)->map(function(User $user) {
                return "('" . $user->uuid . "'::uuid,'" . $user->info->getUserFio() . "')";
            })->implode(',')
            . ") as users (auth_user_uuid, user_name)";

        $query->leftJoin(\DB::raw($values), 'users.auth_user_uuid', '=', 'landing_objects.user_uuid');
    }

    /**
     * Присоединение к запросу имен мун. образований
     *
     * @param $query
     */
    public function scopeWithCommunalMunicipalities($query): void
    {
        $meta = new RequestMeta();
        $meta->response_data = [
            "items/title",
            "items/uuid",
        ];
        $meta->pagination = new PaginationRequest();
        $meta->pagination->page = 1;
        $meta->pagination->limit = 999999;

        $objects = exchange()->request('com.rnis.geo.action.user_geo_object.mo')
            ->withSystemAuth()
            ->withHeader('meta', $meta)
            ->send(GetUserGeoObjectPayload::class)
            ->payload
            ?->items;

        if (!$objects) {
            return;
        }

        $values = "(VALUES " .
            collect($objects)->map(function(UserGeoObjectPayload $object) {
                return "('" . $object->uuid . "'::uuid,'" . $object->title . "')";
            })->implode(',')
            . ") as communal_municipalities (geo_communal_municipality_uuid, communal_municipality_name)";

        $query->leftJoin(\DB::raw($values), 'communal_municipalities.geo_communal_municipality_uuid', '=', 'landing_objects.communal_municipality_uuid');
    }
}
