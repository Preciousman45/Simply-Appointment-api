<?php

declare(strict_types=1);

namespace Appointments\Service;

use App\MailService;
use Appointments\Service\AppointmentReminder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Appointments\Service\SendCustomerAppointmentReminderMail;
use Psr\EventDispatcher\EventDispatcherInterface;

class SevenDaysCustomerReminderMailFactory
{
    public function __invoke(ContainerInterface $container): SevenDaysCustomerReminderMail
    {
        return new SevenDaysCustomerReminderMail(
            $container->get(EntityManagerInterface::class),
            $container->get(SendCustomerAppointmentReminderMail::class)
        );
    }
}
