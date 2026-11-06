<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class DeleteServiceFactory
{
    public function __invoke(ContainerInterface $container): DeleteService
    {
        return new deleteService($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
