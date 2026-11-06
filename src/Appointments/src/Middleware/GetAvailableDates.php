<?php

declare(strict_types=1);

namespace Appointments\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Appointments\Exceptions\AppointmentExceptions;
use Appointments\Service\AppointmentServices;
use service\Entity\StaffService;
use user\Entity\User;

class GetAvailableDates implements MiddlewareInterface
{
    public function __construct(private AppointmentServices $appointment, private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffId = $request->getAttribute('staffId');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['user_id' => $staffId]);

        $staff = $this->entityManager->getRepository(StaffService::class)->findOneBy(['user_id' => $staffId]);

        if (!$staff) {
            throw AppointmentExceptions::staffNotFound();
        }

        $availableDates = $this->appointment->getAvailableDatesWithin30Days($staffId);

        return new JsonResponse([
            'Status' => 200,
            'Description' => 'List of available dates',
            'Content' => $availableDates
        ]);
    }
}
