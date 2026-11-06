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

class GetUserAppointment implements MiddlewareInterface
{

    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface

    {
        $userId  = $request->getAttribute('userId');
        $appointment_id  = $request->getAttribute('appointmentId');
        $appointment = $this->entityManager->getRepository(Appointments::class)->findOneBy(['user_id' => $userId, 'appointment_id' => $appointment_id]);

        if (!$appointment) {
            throw AppointmentExceptions::appointmentNotFound();
        }
        return new JsonResponse([
            'Details' => 'ok.',
            "Status" => 200,
            'Content' => $appointment->toArray(),
        ]);
    }
}
