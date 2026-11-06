<?php

declare(strict_types=1);

namespace Appointments\Service;


use Appointments\Service\AppointmentReminder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Appointments\Service\SendCustomerAppointmentReminderMail;


class TwentyFourHoursCustomerReminderMailFactory
{
    public function __invoke(ContainerInterface $container): TwentyFourHoursCustomerReminderMail
    {
        return new TwentyFourHoursCustomerReminderMail(
            $container->get(EntityManagerInterface::class),
            $container->get(SendCustomerAppointmentReminderMail::class)
        );
    }
}
