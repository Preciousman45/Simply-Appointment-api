<?php

declare(strict_types=1);

namespace service;

/**
 * The configuration provider for the service module
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
                \service\Middleware\CreateService::class => \service\Middleware\CreateServiceFactory::class,
                \service\Middleware\DeleteService::class => \service\Middleware\DeleteServiceFactory::class,
                \service\Middleware\GetService::class => \service\Middleware\GetServiceFactory::class,
                \service\Middleware\GetServices::class => \service\Middleware\GetServicesFactory::class,
                \service\Middleware\UpdateService::class => \service\Middleware\UpdateServiceFactory::class,
                \service\Middleware\AddStaffService::class => \service\Middleware\AddStaffServiceFactory::class,
                \service\Middleware\RemoveService::class => \service\Middleware\RemoveServiceFactory::class,
                \service\Middleware\GetStaffService::class => \service\Middleware\GetStaffServiceFactory::class,
                \service\Middleware\GetStaffServices::class => \service\Middleware\GetStaffServicesFactory::class,
                \service\Middleware\ValidateServiceId::class => \service\Middleware\ValidateServiceIdFactory::class,
                \service\Middleware\ValidateStaffId::class => \service\Middleware\ValidateStaffIdFactory::class,
                \service\Middleware\ValidateStaffIdAndServiceId::class => \service\Middleware\ValidateStaffIdAndServiceIdFactory::class

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
                'service'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
