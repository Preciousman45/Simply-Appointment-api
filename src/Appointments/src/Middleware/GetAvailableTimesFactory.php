<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class GetAvailableTimesFactory
{
    public function __invoke(ContainerInterface $container): GetAvailableTimes
    {
        return new getAvailableTimes($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
