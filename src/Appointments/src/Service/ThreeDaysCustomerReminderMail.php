<?php

declare(strict_types=1);

namespace Appointments\Service;

use Appointments\Abstracts\CustomerReminderMailAbstract;
use Appointments\Event\ThreeDaysEvent;
use Doctrine\ORM\EntityManagerInterface;

class ThreeDaysCustomerReminderMail
{
    use CustomerReminderMailAbstract;

    public const max_sc      = 259200;
    public const interval_sc = 172800;  // 2 day window, wider than the daily schedule
    public const label       = '3 days';
    public const type        = '3d';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SendCustomerAppointmentReminderMail $reminderMail,
    ) {}

    public function __invoke(ThreeDaysEvent $event): int
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
