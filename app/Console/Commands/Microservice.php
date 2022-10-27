<?php

namespace App\Console\Commands;

use Common\Exchange\Core\Contracts\ExchangeManager;
use Illuminate\Console\Command;

class Microservice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запуск приложения в качестве микросервиса';

    /**
     * @param ExchangeManager $exchange
     */
    public function handle(ExchangeManager $exchange)
    {
        ini_set('memory_limit', '4096M');

        $exchange->setCommand($this);

        $exchange->run();
    }
}
