<?php

declare(strict_types=1);

namespace user\Middleware;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Auth\JwtService;

class LoginFactory
{
    public function __invoke(ContainerInterface $container): Login
    {
        return new Login(
            $container->get(EntityManagerInterface::class),
            $container->get(JwtService::class)
        );
    }
}
