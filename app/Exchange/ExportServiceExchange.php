<?php

namespace App\Exchange;

use App\Jobs\Export\LandingObjectExportJob;
use App\Models\LandingObject;
use Common\Exchange\Core\Contracts\Exchange;
use Common\Exchange\Core\Exchange\IncomingRequest;
use Common\Service\Common\Export\ExportManager;
use Common\Service\Common\Model\RequestHeaders;
use Common\Service\Common\Model\TableExport;

/**
 * Сервис экспорта сущностей.
 *
 * @package App\Exchange
 */
class ExportServiceExchange implements Exchange
{
    /**
     * Список entity и джоб им соответствующих
     * @var array
     */
    protected array $jobs = [
        LandingObject::EXPORT_ENTITY => LandingObjectExportJob::class,
    ];

    /**
     * Добавление нового задания на экспорт таблицы.
     *
     * @exchangeSubject    com.rnis.control.action.table.export
     * @exchangeMiddleware Common\Exchange\Middleware\ValidateTokenMiddleware
     * @disableMiddleware  Common\Exchange\Middleware\ValidateRequesterMiddleware
     *
     * @param IncomingRequest $request
     * @param RequestHeaders $headers
     * @param TableExport $payload
     */
    public function tableExport(IncomingRequest $request, RequestHeaders $headers, TableExport $payload)
    {
        $request->requireReadPermission('com.rnis.control.landing_objects');

        app(ExportManager::class)
            ->jobs($this->jobs)
            ->exportTable($request, $headers, $payload, []);
    }
}
