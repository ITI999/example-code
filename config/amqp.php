<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Define which configuration should be used
    |--------------------------------------------------------------------------
    */

    'use' => 'production',

    /*
    |--------------------------------------------------------------------------
    | AMQP properties separated by key
    |--------------------------------------------------------------------------
    */

    'properties' => [
        'production' => [
            'host'            => env('RABBITMQ_HOST'),
            'port'            => env('RABBITMQ_PORT'),
            'username'        => env('RABBITMQ_USERNAME'),
            'password'        => env('RABBITMQ_PASSWORD'),
            'vhost'           => '/',
            'connect_options' => [],
            'ssl_options'     => [],

            'exchange'             => 'amq.topic',
            'exchange_type'        => 'topic',
            'exchange_passive'     => false,
            'exchange_durable'     => true,
            'exchange_auto_delete' => false,
            'exchange_internal'    => false,
            'exchange_nowait'      => false,
            'exchange_properties'  => [],

            'queue_force_declare' => false,
            'queue_passive'       => false,
            'queue_durable'       => true,
            'queue_exclusive'     => false,
            'queue_auto_delete'   => false,
            'queue_nowait'        => false,
            'queue_properties'    => [
                'x-ha-policy'   => [
                    'S',
                    'all',
                ],
                'x-message-ttl' => 30000,
            ],

            'consumer_tag'       => '',
            'consumer_no_local'  => false,
            'consumer_no_ack'    => false,
            'consumer_exclusive' => false,
            'consumer_nowait'    => false,
            'timeout'            => 30,
            'persistent'         => true,
        ],
    ],
];
