<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class CancelAppointmentFactory
{
    public function __invoke(ContainerInterface $container): CancelAppointment
    {
        return new cancelAppointment($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
