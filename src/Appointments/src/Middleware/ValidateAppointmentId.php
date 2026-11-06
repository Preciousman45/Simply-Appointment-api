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

class ValidateAppointmentId implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $appointmentId = $request->getAttribute('appointmentId');

        if (!$appointmentId) {
            throw AppointmentExceptions::missingData($appointmentId);
        }

        $appointment = $this->entityManager->getRepository(Appointments::class)->find($appointmentId);
        if (!$appointment) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        return $handler->handle($request);
    }
}
