<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ValidateAppointmentIdAndUserIdFactory
{
    public function __invoke(ContainerInterface $container): ValidateAppointmentIdAndUserId
    {
        return new ValidateAppointmentIdAndUserId($container->get(EntityManagerInterface::class));
    }
}
