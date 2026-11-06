<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use service\Entity\StaffService;
use service\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Auth\Exception\AuthException;
use service\Exceptions\ServiceException;

class GetStaffService implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffId = $request->getAttribute('staffId');
        $serviceId = $request->getAttribute('serviceId');


        $staffServices = $this->entityManager->getRepository(StaffService::class)->findBy([
            'user_id' => $staffId,
            'service_id' => $serviceId
        ]);

        if (!$staffServices) {
            throw ServiceException::serviceNotFound();
        }


        $services = $this->entityManager->getRepository(Service::class)->findoneBy([
            'service_id' => $serviceId
        ]);

        if (!$services) {
            throw ServiceException::serviceNotFound();
        }

        return new JsonResponse([

            'Details' => 'OK',
            'status' => 200,
            'Content' => $services->toArray()
        ]);
    }
}
