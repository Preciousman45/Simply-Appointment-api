<?php

declare(strict_types=1);

use Mezzio\Swoole\Event\EventDispatcherInterface as SwooleEventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface as PsrEventDispatcher;


return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            PsrEventDispatcher::class => SwooleEventDispatcher::class,
            // Fully\Qualified\ClassOrInterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [

            \App\MailService::class => \App\MailServiceFactory::class,
            \Appointments\Service\SendCustomerAppointmentReminderMail::class => \Appointments\Service\SendCustomerAppointmentReminderMailFactory::class
            // Fully\Qualified\ClassName::class => Fully\Qualified\FactoryName::class,
        ],
    ],
];
