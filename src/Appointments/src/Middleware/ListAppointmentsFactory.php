<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class ListAppointmentsFactory
{
    public function __invoke(ContainerInterface $container): ListAppointments
    {
        return new ListAppointments($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
