<?php

declare(strict_types=1);

namespace Appointments\Service;

use App\MailService;
use Appointments\Service\SendCustomerAppointmentReminderMail;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class SendCustomerAppointmentReminderMailFactory
{
    public function __invoke(ContainerInterface $container): SendCustomerAppointmentReminderMail
    {
        return new SendCustomerAppointmentReminderMail(
            $container->get(MailService::class),
            $container->get(EntityManagerInterface::class)

        );
    }
}
