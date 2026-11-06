<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class GetServicesFactory
{
    public function __invoke(ContainerInterface $container): GetServices
    {
        return new getServices($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
