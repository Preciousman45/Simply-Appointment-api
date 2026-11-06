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
use App\Auth\Exception\AuthException;
use Appointments\Exceptions\AppointmentExceptions;
use Throwable;

class GetAppointment implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $appointmentId = $request->getAttribute('appointmentId');

        $appointments = $this->entityManager->getRepository(Appointments::class)->find($appointmentId);

        if (!$appointments) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        return new JsonResponse(['Status' => 200, 'Description' => 'ok', 'Content' => $appointments]);
    }
}
