<?php

declare(strict_types=1);

return [
    'mezzio-swoole' => [
        'enable_coroutine' => false,

        'swoole-http-server' => [
            'host' => '127.0.0.1',
            'port' => 9501,
            'options' => [
                'worker_num'            => 4,
                'task_worker_num'       => 4,
                'enable_coroutine'      => false,
                'task_enable_coroutine' => true,
            ],
            'daemonize' => false,

            // CORRECTED CONFIGURATION PLACEMENT
            'listeners' => [
                \Mezzio\Swoole\Event\TaskEvent::class => [
                    \Mezzio\Swoole\Task\TaskInvokerListener::class,
                ],
                \Appointments\Event\SendScheduledAppointmentMailEvent::class => [
                    \Appointments\Service\AppointmentNotification::class,
                ],
                \Mezzio\Swoole\Event\WorkerStartEvent::class => [
                    \Appointments\CronJob\Cron::class,
                ],
                \Appointments\Event\OneHourEvent::class => [
                    \Appointments\Service\OneHourCustomerReminderMail::class,
                ],
                \Appointments\Event\TwentyFourHoursEvent::class => [
                    \Appointments\Service\TwentyFourHoursCustomerReminderMail::class,
                ],
                \Appointments\Event\ThreeDaysEvent::class => [
                    \Appointments\Service\ThreeDaysCustomerReminderMail::class,
                ],
                \Appointments\Event\SevenDaysEvent::class => [
                    \Appointments\Service\SevenDaysCustomerReminderMail::class,
                ],

            ],
        ],

        'swoole_runtime_options' => [
            'hook_flags' => 0,
        ],
    ],
];
