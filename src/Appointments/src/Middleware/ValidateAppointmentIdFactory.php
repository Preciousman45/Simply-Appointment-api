<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ValidateAppointmentIdFactory
{
    public function __invoke(ContainerInterface $container): ValidateAppointmentId
    {
        return new ValidateAppointmentId($container->get(EntityManagerInterface::class));
    }
}
