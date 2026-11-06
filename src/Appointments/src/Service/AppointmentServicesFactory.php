<?php

declare(strict_types=1);

namespace Appointments\Service;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class AppointmentServicesFactory
{
    public function __invoke(ContainerInterface $container): AppointmentServices
    {
        return new AppointmentServices(
            $container->get(\Doctrine\ORM\EntityManagerInterface::class),
            $container->get(EventDispatcherInterface::class)
        );
    }
}
