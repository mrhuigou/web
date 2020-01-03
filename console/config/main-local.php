<?php
return [
	'controllerMap' => [
		'cron' => [
			'class' => 'console\controllers\CronController',
			'cronJobs' =>[
//				'old/data/index' => [
//					'cron'      => '* * * * *',
//				],
//				'old/data/init' => [
//					'cron'      => '0 1 */1 * *',
//				],
				'old/open/index' => [
					'cron'      => '0 1 */1 * *',
				],
				'old/open/update' => [
					'cron'      => '* * * * *',
				],
				'old/order/auto' => [
					'cron'      => '* * * * *',
				],
				'old/express/order' => [
					'cron'      => '* * * * *',
				],
				'old/express/status' => [
					'cron'      => '*/2 * * * *',
				],
				'old/order/delivery-comment' => [
					'cron'      => '*/3 * * * *',
				],
				'old/order/status' => [
					'cron'      => '*/2 * * * *',
				],
				'old/order/cancel' => [
					'cron'      => '*/3 * * * *',
				],
                'old/order/payment-notice' => [
					'cron'      => '*/3 * * * *',
				],
				'old/stock/auto' => [
					'cron'      => '*/3 * * * *',
				],
				'old/stock/autoschedule' => [
					'cron'      => '*/3 * * * *',
				],
//                'old/notice/coupon' => [
//                    'cron'      => '0,30 09-17 * * *',
//                ],
                'old/order/auto-receive' => [
                    'cron'      => '*/3 * * * *',
                ],
//                'old/notice/srwd-point' => [
//                    'cron'      => ' 00 00 01 02 *',
//                ],
			],
		],
	],
];
