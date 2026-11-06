<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class GetAppointmentFactory
{
    public function __invoke(ContainerInterface $container): GetAppointment
    {
        return new getAppointment($container->get(EntityManagerInterface::class));
    }
}
