<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;

class RescheduleAppointmentFactory
{
    public function __invoke(ContainerInterface $container): RescheduleAppointment
    {
        return new rescheduleAppointment(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class),
            $container->get(\Appointments\Service\AppointmentServices::class)
        );
    }
}
