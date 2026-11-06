<?php

declare(strict_types=1);

namespace App;

/**
 * The configuration provider for the Dependencies module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [],
            'factories'  => [
                \Doctrine\ORM\EntityManagerInterface::class => \App\Database\EntityManagerFactory::class,
                \App\Auth\JwtService::class => \App\Auth\JwtServiceFactory::class,
                \JimTools\JwtAuth\Middleware\JwtAuthentication::class => \App\Auth\JwtAuthenticationFactory::class,

                \Mezzio\ProblemDetails\ProblemDetailsMiddleware::class => [
                    \App\Middleware\ProblemDetailsMiddlewareDelegator::class,
                    \App\MailService::class => \App\MailServiceFactory::class,

                ],
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'dependencies'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
