<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use service\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use service\Exceptions\ServiceException;
use Throwable;

class DeleteService implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $serviceId = $request->getAttribute('serviceId');
            $service = $this->entityManager->getRepository(Service::class)->find($serviceId);

            if (!$service) {
                throw ServiceException::serviceNotFound();
            }

            $service->set_deleted(true);
            $this->entityManager->persist($service);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            throw ServiceException::fromServiceThrowable($e);
        }
        return new JsonResponse([
            "Details" => "OK",
            "Status" => 200,
        ]);
    }
}
