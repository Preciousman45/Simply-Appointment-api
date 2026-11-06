<?php

declare(strict_types=1);

namespace Appointments\Exceptions;

use DomainException;
use Mezzio\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Mezzio\ProblemDetails\Exception\ProblemDetailsExceptionInterface;
use Throwable;

class AppointmentExceptions extends DomainException implements ProblemDetailsExceptionInterface
{
    use CommonProblemDetailsExceptionTrait;

    // ==========================================
    // 400 - Bad Request Methods
    // ==========================================

    public static function appointmentInPast(): self
    {
        $detail = 'Appointment time cannot be in the past';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'Bad Request';
        $exception->detail = $detail;

        return $exception;
    }

    public static function failedToAppointmentMailToStaff(): self
    {
        $detail = 'Failed to send appointment schedule mail to staff because of wrong input details';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'Bad Request';
        $exception->detail = $detail;

        return $exception;
    }

    public static function failedToAddNotificationToNotificationTable(): self
    {
        $detail = 'Notification was not added to the notification table';
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'Bad Request';
        $exception->detail = $detail;

        return $exception;
    }

    public static function fromAppointmentThrowable(Throwable $th): self
    {
        $detail = $th->getMessage();
        $exception = new self($detail, 0, $th);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'Bad Request';
        $exception->detail = $detail;

        return $exception;
    }

    public static function missingData(string ...$data): self
    {
        $detail = "Failed to pass: " . implode(', ', $data);
        $exception = new self($detail);
        $exception->status = 400;
        $exception->type = '';
        $exception->title = 'Missing Data';
        $exception->detail = $detail;

        return $exception;
    }



    public static function appointmentNotFound(): self
    {
        $detail = 'Appointment not found';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function serviceNotFound(): self
    {
        $detail = 'Service not found';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function staffNotFound(): self
    {
        $detail = 'Staff member not found';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function customerNotFound(): self
    {
        $detail = 'Customer not found';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function staffDoesNotOfferService(): self
    {
        $detail = 'This staff member does not offer the selected service';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    public static function noStaffOffersThisService(): self
    {
        $detail = 'No staff member offers the selected service';
        $exception = new self($detail);
        $exception->status = 404;
        $exception->type = '';
        $exception->title = 'Not Found';
        $exception->detail = $detail;

        return $exception;
    }

    // ==========================================
    // 409 - Conflict Methods
    // ==========================================

    public static function outsideOpenHours(): self
    {
        $detail = 'The selected time is outside open hours';
        $exception = new self($detail);
        $exception->status = 409;
        $exception->type = '';
        $exception->title = 'Conflict';
        $exception->detail = $detail;

        return $exception;
    }

    public static function noOpeningHoursSetForThisDay(string $day): self
    {
        $detail = "No opening hour was set for {$day}";
        $exception = new self($detail);
        $exception->status = 409;
        $exception->type = '';
        $exception->title = 'Conflict';
        $exception->detail = $detail;

        return $exception;
    }

    public static function dailyLimitReached(int $max): self
    {
        $detail = "This staff member already has the maximum of {$max} appointments for that day";
        $exception = new self($detail);
        $exception->status = 409;
        $exception->type = '';
        $exception->title = 'Conflict';
        $exception->detail = $detail;

        return $exception;
    }

    public static function timeSlotTaken(): self
    {
        $detail = 'The selected time slot is already booked';
        $exception = new self($detail);
        $exception->status = 409;
        $exception->type = '';
        $exception->title = 'Conflict';
        $exception->detail = $detail;

        return $exception;
    }

    public static function appointmentCancelled(): self
    {
        $detail = 'This appointment has been cancelled and cannot be changed';
        $exception = new self($detail);
        $exception->status = 409;
        $exception->type = '';
        $exception->title = 'Conflict';
        $exception->detail = $detail;

        return $exception;
    }

    public static function appointmentBookingFailed(): self
    {
        $detail = 'Failed to book appointment';
        $exception = $exception = new self($detail);
        $exception->status = 409;
        $exception->type = '';
        $exception->title = 'Conflict';
        $exception->detail = $detail;

        return $exception;
    }
}
