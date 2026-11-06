<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Appointments\Entity\Appointments;
use Appointments\Exceptions\AppointmentExceptions;
use Assert\Assertion;
use Throwable;

class UpdateAppointmentStatus implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getAttributes();
        $parsedBody = $request->getParsedBody();
        $status = $parsedBody['status'];

        try {
            Assertion::isArray($data, 'Pass the required details');
            Assertion::notEmptyKey($data, 'staffId', 'You need to pass the staffId');
            Assertion::notEmptyKey($data, 'appointmentId', 'You need to pass the appointment Id');
        } catch (Throwable $e) {
            throw AppointmentExceptions::fromAppointmentThrowable($e);
        }

        $appointmentId = $data['appointmentId'];
        $staffId = $data['staffId'];
        $appointment = $this->entityManager->getRepository(Appointments::class)->findOneBy([
            'appointment_id' => $appointmentId,
            'staff_id' => $staffId
        ]);

        if (!$appointment) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        $appointment->setStatus($status);
        $this->entityManager->flush();

        return new JsonResponse([
            'Details' => 'OK.',
            "Status" => 200,

        ]);
    }
}
