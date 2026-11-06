<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class GetAvailableDatesFactory
{
    public function __invoke(ContainerInterface $container): GetAvailableDates
    {
        return new getAvailableDates(
            $container->get(\Appointments\Service\AppointmentServices::class),
            $container->get(\Doctrine\ORM\EntityManagerInterface::class)

        );
    }
}
