<?php

namespace App\Exchange;

use Common\Exchange\Core\Contracts\Exchange;
use Common\Exchange\Core\Exchange\IncomingRequest;
use Common\Exchange\Traits\ExchangeWithPermissions;
use Common\Exchange\Traits\ExchangeWithStatusCheck;
use Common\Service\Common\Entity\EntityManager;
use Common\Service\Common\Payload\EntityPayload;

/**
 * @exchangeName com.rnis.boilerplate8
 */
class ServiceExchange implements Exchange
{
    use ExchangeWithPermissions, ExchangeWithStatusCheck;

    /**
     * Запрос сущности.
     *
     * @exchangeSubject    com.rnis.control.action.entity
     * @exchangeMiddleware Common\Exchange\Middleware\ValidateTokenMiddleware
     * @disableMiddleware Common\Exchange\Middleware\ValidateRequesterMiddleware
     *
     * @param IncomingRequest $request
     * @param EntityPayload   $payload
     */
    public function entity(IncomingRequest $request, EntityPayload $payload)
    {
        app(EntityManager::class)->process($request, $payload);
    }
}
