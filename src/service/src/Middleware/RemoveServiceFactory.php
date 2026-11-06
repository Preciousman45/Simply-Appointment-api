<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class RemoveServiceFactory
{
    public function __invoke(ContainerInterface $container): RemoveService
    {
        return new removeService($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
