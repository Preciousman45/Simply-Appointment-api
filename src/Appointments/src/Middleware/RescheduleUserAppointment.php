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
use DateTimeImmutable;
use Appointments\Exceptions\AppointmentExceptions;
use user\Entity\User;

class RescheduleUserAppointment implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userId = $request->getAttribute('userId');
        $appointmentId = $request->getAttribute('appointmentId');
        $appointmentDetails = $request->getParsedBody() ?? [];

        $appointment = $this->entityManager->getRepository(Appointments::class)->findOneBy(['user_id' => $userId, 'appointment_id' => $appointmentId]);
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            throw AppointmentExceptions::customerNotFound();
        }
        if (!$appointment) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        $appointment->setDate(new \DateTimeImmutable($appointmentDetails['date'] ?? 'now'));
        $appointment->setTime(new \DateTimeImmutable($appointmentDetails['time'] ?? 'now'));
        $appointment->setEndTime(new \DateTimeImmutable($appointmentDetails['end_time'] ?? 'now'));

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return new JsonResponse([

            'detail' => 'OK',
            'status' => 200,

        ]);
    }
}
