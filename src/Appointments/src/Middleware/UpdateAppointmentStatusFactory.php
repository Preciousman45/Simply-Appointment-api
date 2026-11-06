<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class UpdateAppointmentStatusFactory
{
    public function __invoke(ContainerInterface $container): UpdateAppointmentStatus
    {
        return new updateAppointmentStatus($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
