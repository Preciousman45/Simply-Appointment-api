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

class GetUserAppointments implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userId  = $request->getAttribute('userId');

        if (!$userId) {
            throw AppointmentExceptions::customerNotFound();
        }

        $appointments = $this->entityManager->getRepository(Appointments::class)->findBy(['user_id' => $userId]);

        if (!$appointments) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        $data = [];
        foreach ($appointments as $appointment) {
            $data[] = $appointment->toArray();
        }

        return new JsonResponse([
            'Details' => 'OK',
            "Status" => 200,
            'Content' => $data
        ]);
    }
}
