<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Appointments\Entity\Appointments;
use Doctrine\ORM\EntityManagerInterface;
use Appointments\Exceptions\AppointmentExceptions;
use Assert\Assertion;
use Throwable;

class CancelUserAppointment implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getAttributes();
        try {
            Assertion::isArray($data, 'Your request needs the appointment id and the user id');
            Assertion::notEmptyKey($data, 'appointmentId', 'You need the appointment ID in your request');
            Assertion::notEmptyKey($data, 'userId', 'You need the user id in your request');
        } catch (Throwable $e) {
            throw AppointmentExceptions::fromAppointmentThrowable($e);
        }

        $appointmentId = $data['appointmentId'];
        $userId = $data[' userId'];

        try {
            $appointment = $this->entityManager->getRepository(Appointments::class)->findOneBy(['appointment_id' => $appointmentId, 'user_id' => $userId]);

            if (!$appointment) {
                throw AppointmentExceptions::appointmentNotFound();
            }

            $this->entityManager->remove($appointment);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            AppointmentExceptions::fromAppointmentThrowable($e);
        }
        return new JsonResponse([
            'Details' => 'Ok.',
            "Status" => 200,

        ]);
    }
}
