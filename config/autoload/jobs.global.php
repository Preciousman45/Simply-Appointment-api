<?php

declare(strict_types=1);


use Appointments\Event\OneHourEvent;
use Appointments\Event\SevenDaysEvent;
use Appointments\Event\ThreeDaysEvent;
use Appointments\Event\TwentyFourHoursEvent;


return [
    'jobs' => [
        'one_hour' => [
            'schedule' => '*/5 * * * *',
            'event'    => OneHourEvent::class,
        ],
        'twentyfour_hour' => [
            'schedule' => '0 * * * *',
            'event'    => TwentyFourHoursEvent::class,
        ],
        'three_day' => [
            'schedule' => '0 6 * * *',
            'event'    => ThreeDaysEvent::class,
        ],
        'seven_day' => [
            'schedule' => '0 6 * * *',
            'event'    => SevenDaysEvent::class,
        ],
    ],
];
