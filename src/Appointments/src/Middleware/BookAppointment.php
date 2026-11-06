<?php

declare(strict_types=1);

namespace Appointments\Middleware;


use Appointments\Service\AppointmentServices;
use Assert\Assertion;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use service\Entity\StaffService;
use user\Entity\User;
use Appointments\Exceptions\AppointmentExceptions;
use Psr\EventDispatcher\EventDispatcherInterface;

use Throwable;

class BookAppointment implements MiddlewareInterface
{
    public function __construct(private AppointmentServices $appointment, private EntityManagerInterface $entityManager, private EventDispatcherInterface $dispatcher) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $userId = $request->getAttribute('userId');
        $data   = $request->getParsedBody();

        try {
            Assertion::isArray($data, 'the request body is required');
            Assertion::notEmptyKey($data, 'date',       'the date field is required');
            Assertion::notEmptyKey($data, 'time',       'the time field is required');
            Assertion::notEmptyKey($data, 'service_id', 'the service_id field is required');
            Assertion::date($data['date'], 'Y-m-d', 'This date is not in the right format, change the date into this kind of format (Y-m-d)');
            Assertion::date($data['time'], 'H:i:s', 'the time is not in the right format, change it to match this format (H:i:s)');
        } catch (Throwable $th) {
            throw AppointmentExceptions::fromAppointmentThrowable($th);
        }

        $service = $this->entityManager->getRepository(StaffService::class)->findOneBy(['service_id' => $data['service_id']]);

        if (!$service) {
            throw AppointmentExceptions::serviceNotFound();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['user_id' => $userId]);

        if (!$user) {
            throw AppointmentExceptions::customerNotFound();
        }
        $appointment = $this->appointment->bookAppointment($userId, $data);

        if (!$appointment) {
            throw AppointmentExceptions::appointmentBookingFailed();
        }

        return new JsonResponse(['status' => 201, 'details' => 'OK', 'Content' => $appointment]);
    }
}
