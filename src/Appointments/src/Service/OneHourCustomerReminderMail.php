<?php

declare(strict_types=1);

namespace Appointments\Service;

use Appointments\Abstracts\CustomerReminderMailAbstract;
use Appointments\Event\OneHourEvent;
use Doctrine\ORM\EntityManagerInterface;

class OneHourCustomerReminderMail
{
    use CustomerReminderMailAbstract;

    public const max_sc      = 3600;
    public const interval_sc = 900;   // 15 min window, wider than the 5 min schedule
    public const label       = '1 hour';
    public const type        = '1h';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SendCustomerAppointmentReminderMail $reminderMail,
    ) {}

    public function __invoke(OneHourEvent $event): int
    {
        return $this->processCustomerReminderMail(
            $this->entityManager,
            $this->reminderMail,
            self::max_sc,
            self::interval_sc,
            self::label,
            self::type,
        );
    }
}
