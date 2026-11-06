<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface; // 👈 Add this import
use Appointments\Service\AppointmentServices;
use Doctrine\ORM\EntityManagerInterface;

class BookAppointmentFactory
{
    public function __invoke(ContainerInterface $container): BookAppointment
    {
        return new BookAppointment(
            $container->get(AppointmentServices::class),
            $container->get(EntityManagerInterface::class),
            $container->get(EventDispatcherInterface::class) // 👈 Inject the dispatcher here
        );
    }
}
