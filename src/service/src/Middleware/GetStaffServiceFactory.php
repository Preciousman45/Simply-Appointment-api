<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class GetStaffServiceFactory
{
    public function __invoke(ContainerInterface $container): GetStaffService
    {
        return new getStaffService($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
