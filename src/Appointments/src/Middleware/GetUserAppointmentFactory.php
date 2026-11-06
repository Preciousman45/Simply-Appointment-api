<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class GetUserAppointmentFactory
{
    public function __invoke(ContainerInterface $container): GetUserAppointment
    {
        return new getUserAppointment($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
