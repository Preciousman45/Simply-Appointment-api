<?php

return [
    // 'dependencies' => [
    //     'factories' => [
    //         // This is the dependency snippet you asked about:
    //         JimTools\JwtAuth\Middleware\JwtAuthentication::class => Dependencies\Middleware\JwtAuthenticationFactory::class,
    //     ],
    // ],
    'jwt' => [
        'secret' => '0b5bdab6de9d5382d8543906f6e951dbd5cd9c7f1547e810fcfa505627e572c6',
        'algorithm' => 'HS256',
        'ttl' => 3600,
    ],
];
