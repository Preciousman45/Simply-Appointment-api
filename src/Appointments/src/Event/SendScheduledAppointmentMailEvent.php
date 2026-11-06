<?php

declare(strict_types=1);

namespace Appointments\Event;


class SendScheduledAppointmentMailEvent
{
    public function __construct(
        public string $appointmentId
    ) {}
}
