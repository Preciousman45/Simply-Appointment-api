<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Appointments\Entity\Appointments;
use Assert\Assertion;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;
use Appointments\Service\AppointmentServices;
use Appointments\Exceptions\AppointmentExceptions;

class RescheduleAppointment implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private AppointmentServices $reschedule) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $appointmentId = $request->getAttribute('appointmentId');
        $data = $request->getParsedBody() ?? [];

        $appointment = $this->entityManager->getRepository(Appointments::class)->find($appointmentId);

        if (!$appointment) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        try {
            Assertion::notEmptyKey($data, 'time', 'the time field is required');
            Assertion::date($data['time'], 'H:i:s', 'the time is not in the right format, change it to match this format (H:i:s)');

            if (!empty($data['date'])) {
                Assertion::date($data['date'], 'Y-m-d', 'This date is not in the right format, change the date into this kind of format (Y-m-d)');
            }
        } catch (Throwable $e) {
            throw AppointmentExceptions::fromAppointmentThrowable($e);
        }


        return $this->reschedule->rescheduleAppointment($appointmentId, $data);
    }
}
