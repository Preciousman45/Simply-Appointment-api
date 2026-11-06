<?php

declare(strict_types=1);

namespace Appointments\Service;

use Appointments\Abstracts\CustomerReminderMailAbstract;
use Appointments\Event\TwentyFourHoursEvent;
use Doctrine\ORM\EntityManagerInterface;

class TwentyFourHoursCustomerReminderMail
{
    use CustomerReminderMailAbstract;

    public const max_sc      = 86400;
    public const interval_sc = 900;    // 15 min window, wider than the 5 min schedule
    public const label       = '24 hours';
    public const type        = '24h';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private SendCustomerAppointmentReminderMail $reminderMail,
    ) {}

    public function __invoke(TwentyFourHoursEvent $event): int
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
