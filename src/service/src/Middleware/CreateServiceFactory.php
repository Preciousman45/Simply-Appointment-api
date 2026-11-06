<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidFactoryInterface; // Import the factory interface

class CreateServiceFactory
{
    public function __invoke(ContainerInterface $container): CreateService
    {
        return new createService(
            $container->get(EntityManagerInterface::class) // Fixed: Added ::class here
        );
    }
}
