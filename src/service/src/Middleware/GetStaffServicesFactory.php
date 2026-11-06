<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;

class GetStaffServicesFactory
{
    public function __invoke(ContainerInterface $container): GetStaffServices
    {
        return new getStaffServices($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
