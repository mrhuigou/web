<?php
return [
    'controllerMap' => [
        'cron' => [
            'class' => 'console\controllers\CronController',
            'cronJobs' =>[
                'old/order/autoorder' => [
                    'cron'      => '* * * * *',
                ],
                'old/order/orderstatus' => [
                    'cron'      => '1 * * * *',
                ],
                'old/order/cancel' => [
                    'cron'      => '5 * * * *',
                ],
                'old/order/cycelorder' => [
                    'cron'      => '1 * * * *',
                ],
                'old/order/returnorder' => [
                    'cron'      => '1 * * * *',
                ],
                'old/order/rforder' => [
                    'cron'      => '5 * * * *',
                ],
                'old/stock/auto' => [
                    'cron'      => '5 * * * *',
                ],
                'old/stock/autoschedule' => [
                    'cron'      => '5 * * * *',
                ],
            ],
        ],
    ],
];
