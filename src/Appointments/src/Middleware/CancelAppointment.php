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
use Throwable;

class CancelAppointment implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $appointmentId  = $request->getAttribute('appointmentId');
            $appointment = $this->entityManager->getRepository(Appointments::class)->find($appointmentId);

            $this->entityManager->remove($appointment);
            $this->entityManager->flush();
        } catch (throwable $e) {
            throw AppointmentExceptions::fromAppointmentThrowable($e);
        }

        return new JsonResponse([
            'Details' => 'Appointment cancelled successfully.',
            "Status" => 200,
        ]);
    }
}
