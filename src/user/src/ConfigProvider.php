<?php

declare(strict_types=1);

namespace user;

/**
 * The configuration provider for the user module
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
                \user\Middleware\CreateStaff::class => \user\Middleware\CreateStaffFactory::class,
                \user\Middleware\GetStaffs::class => \user\Middleware\GetStaffsFactory::class,
                \user\Middleware\Login::class => \user\Middleware\LoginFactory::class
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
                'user'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
