<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class GetUserAppointmentsFactory
{
    public function __invoke(ContainerInterface $container): GetUserAppointments
    {
        return new getUserAppointments($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
