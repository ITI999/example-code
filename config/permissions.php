<?php

use Common\Service\Auth\Model\RolePermission;

/**
 * Список прав
 */
return [
    [
        'code'   => 'com.rnis.control.control_route',
        'name'   => 'Сущность "Заявки"',
        'grants' => RolePermission::all(),
    ],
];
