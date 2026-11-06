<?php

declare(strict_types=1);

namespace Appointments\Service;

use Appointments\Abstracts\CustomerReminderMailAbstract;
use Appointments\Event\SevenDaysEvent;
use Doctrine\ORM\EntityManagerInterface;

class SevenDaysCustomerReminderMail
{
    use CustomerReminderMailAbstract;

    public const max_sc      = 604800;
    public const interval_sc = 172800;  // 2 day window, wider than the daily schedule
    public const label       = '7 days';
    public const type        = '7d';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SendCustomerAppointmentReminderMail $reminderMail,
    ) {}

    public function __invoke(SevenDaysEvent $event): int
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
