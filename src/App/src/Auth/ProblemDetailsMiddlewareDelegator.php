<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\ProblemDetails\ProblemDetailsMiddleware;
use Psr\Container\ContainerInterface;

class ProblemDetailsMiddlewareDelegator
{
    public function __invoke(
        ContainerInterface $container,
        string $serviceName,
        callable $callback
    ): ProblemDetailsMiddleware {
        // Return the middleware instance directly since no configuration is needed
        return $callback();
    }
}
