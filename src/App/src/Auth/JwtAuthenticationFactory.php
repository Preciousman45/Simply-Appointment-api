<?php

declare(strict_types=1);

namespace App\Auth;

use JimTools\JwtAuth\Decoder\FirebaseDecoder;
use JimTools\JwtAuth\Middleware\JwtAuthentication;
use JimTools\JwtAuth\Options;
use JimTools\JwtAuth\Secret;
use Psr\Container\ContainerInterface;

class JwtAuthenticationFactory
{
    public function __invoke(ContainerInterface $container): JwtAuthentication
    {
        $config = $container->get('config');

        $jwtConfig = $config['jwt'] ?? [];
        $secretKey = $jwtConfig['secret'] ?? 'fallback_change_this_in_production!';
        $algorithm = $jwtConfig['algorithm'] ?? 'HS256';

        $options = new Options();

        $secret = new Secret($secretKey, $algorithm);
        $decoder = new FirebaseDecoder($secret);

        return new JwtAuthentication($options, $decoder);
    }
}
