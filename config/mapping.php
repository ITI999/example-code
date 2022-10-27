<?php

/**
 * Маппинг моделей и пейлоадов
 */
return [
    \App\Models\LandingObject::class => [
        'to'         => \Common\Service\Control\Models\LandingObject::class,
        'attributes' => [
            'uuid'                => [
                'type' => 'string',
            ],
            'user_uuid' => [
                'type' => 'string',
            ],
            'user_name' => [
                'type' => 'string',
            ],
            'name' => [
                'type' => 'string',
            ],
            'actual_address' => [
                'type' => 'string',
            ],
            'communal_municipality_uuid' => [
                'type' => 'string',
            ],
            'communal_municipality_name' => [
                'type' => 'string',
            ],
            'latitude' => [
                'type' => 'float',
            ],
            'longitude' => [
                'type' => 'float',
            ],
            'created_at' => [
                'type' => \Carbon\Carbon::class,
            ],
            'updated_at' => [
                'type' => \Carbon\Carbon::class,
            ],
            'deleted_at' => [
                'type' => \Carbon\Carbon::class,
            ],
        ]
    ],
];
