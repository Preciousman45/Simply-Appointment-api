<?php

declare(strict_types=1);

namespace Appointments\CronJob;

use Psr\Container\ContainerInterface;

class CronFactory
{
    public function __invoke(ContainerInterface $container): Cron
    {
        // Only pass the container itself
        return new Cron($container);
    }
}
