<?php

declare(strict_types=1);

namespace Appointments;

use Mezzio\Swoole\Task\DeferredServiceListenerDelegator;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

/**
 * The configuration provider for the Appointments module
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
                \Appointments\Middleware\BookAppointment::class => \Appointments\Middleware\BookAppointmentFactory::class,
                \Appointments\Middleware\RescheduleAppointment::class => \Appointments\Middleware\RescheduleAppointmentFactory::class,
                \Appointments\Middleware\GetAppointment::class => \Appointments\Middleware\GetAppointmentFactory::class,
                \Appointments\Middleware\CancelAppointment::class => \Appointments\Middleware\CancelUserAppointment::class,
                \Appointments\Middleware\GetAvailableDates::class => \Appointments\Middleware\GetAvailableDatesFactory::class,
                \Appointments\Middleware\GetAvailableTimes::class => \Appointments\Middleware\GetAvailableTimesFactory::class,
                \Appointments\Middleware\ListAppointments::class => \Appointments\Middleware\ListAppointmentsFactory::class,
                \Appointments\Middleware\ValidateAppointmentId::class => \Appointments\Middleware\ValidateAppointmentIdFactory::class,
                \Appointments\Middleware\ValidateAppointmentIdAndUserId::class => \Appointments\Middleware\ValidateAppointmentIdAndUserIdFactory::class,
                \Appointments\Middleware\GetUserAppointment::class => \Appointments\Middleware\GetUserAppointmentFactory::class,
                \Appointments\Middleware\GetUserAppointments::class => \Appointments\Middleware\GetUserAppointmentsFactory::class,
                \Appointments\Middleware\CancelUserAppointment::class => \Appointments\Middleware\CancelUserAppointmentFactory::class,
                \Appointments\Middleware\RescheduleUserAppointment::class => \Appointments\Middleware\RescheduleUserAppointmentFactory::class,
                \Appointments\Service\AppointmentServices::class => \Appointments\Service\AppointmentServicesFactory::class,
                \Appointments\Service\AppointmentNotification::class => \Appointments\Service\AppointmentNotificationFactory::class,
                \Appointments\Service\SendCustomerAppointmentReminderMail::class => \Appointments\Service\SendCustomerAppointmentReminderMailFactory::class,
                \Appointments\Middleware\GetStaffAppointments::class => \Appointments\Middleware\GetStaffAppointmentsFactory::class,
                \Appointments\Middleware\UpdateAppointmentStatus::class => \Appointments\Middleware\UpdateAppointmentStatusFactory::class,
                \Appointments\CronJob\Cron::class => \Appointments\CronJob\CronFactory::class,
                \Appointments\Service\OneHourCustomerReminderMail::class => \Appointments\Service\OneHourCustomerReminderMailFactory::class,
                \Appointments\Service\ThreeDaysCustomerReminderMail::class => \Appointments\Service\ThreeDaysCustomerReminderMailFactory::class,
                \Appointments\Service\SevenDaysCustomerReminderMail::class => \Appointments\Service\SevenDaysCustomerReminderMailFactory::class,
                \Appointments\Service\TwentyFourHoursCustomerReminderMail::class => \Appointments\Service\TwentyFourHoursCustomerReminderMailFactory::class


            ],
            'delegators' => [
                \Appointments\Service\AppointmentNotification::class => [
                    DeferredServiceListenerDelegator::class,
                ],
                \Appointments\Service\SendCustomerAppointmentReminderMail::class => [
                    DeferredServiceListenerDelegator::class,
                ],
                // ADD THESE TO RUN AS TASK WORKERS:
                \Appointments\Service\OneHourCustomerReminderMail::class => [
                    DeferredServiceListenerDelegator::class,
                ],
                \Appointments\Service\TwentyFourHoursCustomerReminderMail::class => [
                    DeferredServiceListenerDelegator::class,
                ],
                \Appointments\Service\ThreeDaysCustomerReminderMail::class => [
                    DeferredServiceListenerDelegator::class,
                ],
                \Appointments\Service\SevenDaysCustomerReminderMail::class => [
                    DeferredServiceListenerDelegator::class,
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
                'appointments'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}
