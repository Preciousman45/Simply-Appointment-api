<?php

declare(strict_types=1);

namespace Appointments\Service;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use App\MailService;
use Appointments\Service\AppointmentNotification;
use Doctrine\ORM\EntityManagerInterface;



final class AppointmentNotificationFactory
{
    public function __invoke(ContainerInterface $container): AppointmentNotification
    {


        return new AppointmentNotification(
            $container->get(EntityManagerInterface::class),
            $container->get(MailService::class)

        );
    }
}
