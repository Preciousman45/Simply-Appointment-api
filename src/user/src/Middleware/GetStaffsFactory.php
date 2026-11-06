<?php

declare(strict_types=1);

namespace user\Middleware;

use Psr\Container\ContainerInterface;

class GetStaffsFactory
{
    public function __invoke(ContainerInterface $container): GetStaffs
    {
        return new GetStaffs($container->get(\Doctrine\ORM\EntityManagerInterface::class));
    }
}
