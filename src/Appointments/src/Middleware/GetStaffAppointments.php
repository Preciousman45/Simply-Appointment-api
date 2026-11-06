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

class GetStaffAppointments implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffId = $request->getAttribute('staffId');

        $appointments = $this->entityManager->getRepository(Appointments::class)->findBy(['staff_id' => $staffId]);

        if (!$appointments) {
            throw AppointmentExceptions::appointmentNotFound();
        }
        $details = [];
        foreach ($appointments as $appointment) {
            $details[] = $appointment->toArray();
        }
        return new JsonResponse([

            "Details" => "OK",
            "Status" => 200,
            'Content' => $details
        ]);
    }
}
