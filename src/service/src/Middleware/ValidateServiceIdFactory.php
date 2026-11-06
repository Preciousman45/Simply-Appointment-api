<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ValidateServiceIdFactory
{
    public function __invoke(ContainerInterface $container): ValidateServiceId
    {
        return new ValidateServiceId($container->get(EntityManagerInterface::class));
    }
}
