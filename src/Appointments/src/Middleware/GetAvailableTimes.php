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

class GetAvailableTimes implements MiddlewareInterface

{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $rows = $this->entityManager->createQuery(
            'SELECT o.open, o.close FROM Appointments\Entity\OpenHours o'
        )->getArrayResult();

        if (!$rows) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                'open'  => $r['open']->format('H:i'),
                'close' => $r['close']->format('H:i'),
            ];
        }

        return new JsonResponse(
            [
                "Status" => 200,
                "Description" => ' List of available times',
                "Content" => $data
            ]
        );
    }
}
