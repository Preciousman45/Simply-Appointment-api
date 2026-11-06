<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class CancelUserAppointmentFactory
{
    public function __invoke(ContainerInterface $container): CancelUserAppointment
    {
        return new cancelUserAppointment($container->get(EntityManagerInterface::class));
    }
}
