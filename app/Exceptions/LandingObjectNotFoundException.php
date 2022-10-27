<?php

namespace App\Exceptions;

/**
 * Класс ошибки "Объект посадки/высадки не найден"
 */
class LandingObjectNotFoundException extends \Exception
{
    protected $code = 'control-0-0-3';
    protected $message = 'Объект посадки/высадки не найден';
}
