<?php

declare(strict_types=1);

namespace user\Middleware;

use Psr\Container\ContainerInterface;

class CreateStaffFactory
{
    public function __invoke(ContainerInterface $container): CreateStaff
    {
        return new CreateStaff($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
