<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class UpdateServiceFactory
{
    public function __invoke(ContainerInterface $container): UpdateService
    {
        return new updateService($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
