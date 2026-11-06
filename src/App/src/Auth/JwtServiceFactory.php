<?php

declare(strict_types=1);

namespace App\Auth;

use Psr\Container\ContainerInterface;
use App\Auth\JwtService;

class JwtServiceFactory
{
    public function __invoke(ContainerInterface $container): JwtService
    {
        $globalConfig = $container->get('config');

        // Safely extract the 'jwt' array block from your configuration
        $jwtConfig = $globalConfig['jwt'] ?? [];

        return new JwtService(

            secret: $jwtConfig['secret'] ?? 'fallback_change_this_in_production!',
            algo: $jwtConfig['algorithm'] ?? 'HS256',
            ttl: $jwtConfig['ttl'] ?? 3600
        );
    }
}
