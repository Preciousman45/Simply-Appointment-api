<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class RescheduleUserAppointmentFactory
{
    public function __invoke(ContainerInterface $container): RescheduleUserAppointment
    {
        return new rescheduleUserAppointment($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
