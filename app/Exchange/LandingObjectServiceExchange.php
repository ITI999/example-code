<?php

namespace App\Exchange;

use App\Models\LandingObject as LandingObjectModel;
use App\Repositories\LandingObjectRepository;
use Common\Exchange\Core\Contracts\Exchange;
use Common\Exchange\Core\Exchange\IncomingRequest;
use Common\Exchange\Exception\MissedRequiredParametersException;
use Common\Exchange\Exception\UnknownErrorException;
use Common\Exchange\Exception\ValueObjectMapperNotFound;
use Common\Service\Common\Model\RequestHeaders;
use Common\Service\Common\Payload\EmptyPayload;
use Common\Service\Control\Models\LandingObject;
use Common\Service\Control\Payloads\LandingObjectList;
use Illuminate\Validation\ValidationException;

/**
 * Сервис объектов посадки/высадки.
 *
 * @package App\Exchange
 */
class LandingObjectServiceExchange implements Exchange
{
    /**
     * Получение репозиторий объектов посадки/высадки
     *
     * @return LandingObjectRepository
     */
    protected function getLandingObjectRepository(): LandingObjectRepository
    {
        return app(LandingObjectRepository::class);
    }

    /**
     * Получение объекта посадки/высадки.
     *
     * @exchangeSubject    com.rnis.control.action.landing_object.get
     * @exchangeMiddleware Common\Exchange\Middleware\ValidateTokenMiddleware
     * @disableMiddleware  Common\Exchange\Middleware\ValidateRequesterMiddleware
     *
     * @param IncomingRequest $request
     * @param RequestHeaders $headers
     * @param LandingObject $payload
     *
     * @throws MissedRequiredParametersException
     */
    public function getLandingObject(IncomingRequest $request, RequestHeaders $headers, LandingObject $payload)
    {
        $request->log($payload, 'Запрос');

        $request->requireReadPermission('com.rnis.control.landing_objects');

        $request->requiredParams('uuid');

        $query = $this->getLandingObjectRepository()->query()->withUserInfo();
        $response = mapToObject($query->find($payload->uuid));

        $request->log($response, 'Ответ');

        $request->sendResponse($response);
    }

    /**
     * Создание объекта посадки/высадки.
     *
     * @exchangeSubject    com.rnis.control.action.landing_object.create
     * @exchangeMiddleware Common\Exchange\Middleware\ValidateTokenMiddleware
     * @disableMiddleware  Common\Exchange\Middleware\ValidateRequesterMiddleware
     *
     * @param IncomingRequest $request
     * @param RequestHeaders $headers
     * @param LandingObject $payload
     *
     * @throws ValueObjectMapperNotFound
     * @throws ValidationException
     */
    public function createLandingObject(IncomingRequest $request, RequestHeaders $headers, LandingObject $payload)
    {
        $request->log($payload, 'Запрос');

        $request->requireCreatePermission('com.rnis.control.landing_objects');

        $response = mapToObject($this->getLandingObjectRepository()->create($payload));

        $request->log($response, 'Ответ');

        $request->sendResponse($response);
    }

    /**
     * Обновление объекта посадки/высадки.
     *
     * @exchangeSubject    com.rnis.control.action.landing_object.update
     * @exchangeMiddleware Common\Exchange\Middleware\ValidateTokenMiddleware
     * @disableMiddleware  Common\Exchange\Middleware\ValidateRequesterMiddleware
     *
     * @param IncomingRequest $request
     * @param RequestHeaders $headers
     * @param LandingObject $payload
     *
     * @throws ValidationException
     * @throws ValueObjectMapperNotFound
     * @throws UnknownErrorException
     */
    public function updateLandingObject(IncomingRequest $request, RequestHeaders $headers, LandingObject $payload)
    {
        $request->log($payload, 'Запрос');

        $request->requireUpdatePermission('com.rnis.control.landing_objects');

        $response = mapToObject($this->getLandingObjectRepository()->update($payload));

        $request->log($response, 'Ответ');

        $request->sendResponse($response);
    }

    /**
     * Удаление объекта посадки/высадки.
     *
     * @exchangeSubject    com.rnis.control.action.landing_object.delete
     * @exchangeMiddleware Common\Exchange\Middleware\ValidateTokenMiddleware
     * @disableMiddleware  Common\Exchange\Middleware\ValidateRequesterMiddleware
     *
     * @param IncomingRequest $request
     * @param RequestHeaders $headers
     * @param LandingObject $payload
     *
     * @throws MissedRequiredParametersException
     * @throws UnknownErrorException
     */
    public function deleteLandingObject(IncomingRequest $request, RequestHeaders $headers, LandingObject $payload)
    {
        $request->log($payload, 'Запрос');

        $request->requireUpdatePermission('com.rnis.control.landing_objects');

        $request->requiredParams('uuid');

        $response = mapToObject($this->getLandingObjectRepository()->delete($payload->uuid));

        $request->log($response, 'Ответ');

        $request->sendResponse($response);
    }

    /**
     * Получение списка объектов посадки/высадки.
     *
     * @exchangeSubject    com.rnis.control.action.landing_object.list
     * @exchangeMiddleware Common\Exchange\Middleware\ValidateTokenMiddleware
     * @disableMiddleware  Common\Exchange\Middleware\ValidateRequesterMiddleware
     *
     * @param IncomingRequest $request
     * @param RequestHeaders $headers
     * @param EmptyPayload $payload
     */
    public function listLandingObject(IncomingRequest $request, RequestHeaders $headers, EmptyPayload $payload)
    {
        $request->log($payload, 'Запрос');

        $request->requireReadPermission('com.rnis.control.landing_objects');

        $query = $this->getLandingObjectRepository()->query()->withUserInfo()->withCommunalMunicipalities();
        $objects = $this->getLandingObjectRepository()->itemsByRequestMetaWithQuery($query, $headers->meta);

        $response = new LandingObjectList;
        $response->items = $objects->getCollection()->map(function (LandingObjectModel $object) {
            return mapToObject($object);
        })->toArray();

        $request->log($response, count($response->items));

        $request->withPagination($objects)->sendResponse($response);
    }
}
