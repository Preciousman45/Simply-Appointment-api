<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class ValidateStaffIdAndServiceIdFactory
{
    public function __invoke(ContainerInterface $container): ValidateStaffIdAndServiceId
    {
        return new ValidateStaffIdAndServiceId($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
