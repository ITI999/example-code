<?php
declare(strict_types=1);

namespace App\Providers;

use App\Exchange\ExportServiceExchange;
use App\Exchange\LandingObjectServiceExchange;
use App\Exchange\ServiceExchange;
use Common\Exchange\Core\Contracts\Exchange;
use Illuminate\Support\ServiceProvider;

/**
 * Регистрация сервисов
 */
class ExchangeServiceProvider extends ServiceProvider
{
    /**
     * Список exchange-й
     *
     * @var array|string[]
     */
    private array $exchanges = [
        ServiceExchange::class,
        LandingObjectServiceExchange::class,
        ExportServiceExchange::class,
    ];

    /**
     * Регистрация
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->exchanges as $exchange) {
            $this->app->singleton($exchange);
        }
        $this->app->tag($this->exchanges, [Exchange::class]);
    }
}
