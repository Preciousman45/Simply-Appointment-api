<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class GetServiceFactory
{
    public function __invoke(ContainerInterface $container): GetService
    {
        return new getService($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
