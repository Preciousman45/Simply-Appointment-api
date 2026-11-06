<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class AddStaffServiceFactory
{
    public function __invoke(ContainerInterface $container): AddStaffService
    {
        return new addStaffService($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
