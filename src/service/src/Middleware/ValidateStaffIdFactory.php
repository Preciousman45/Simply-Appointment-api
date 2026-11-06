<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class ValidateStaffIdFactory
{
    public function __invoke(ContainerInterface $container): ValidateStaffId
    {
        return new ValidateStaffId($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
