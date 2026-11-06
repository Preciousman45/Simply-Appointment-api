<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use service\Entity\Service;
use service\Exceptions\ServiceException;

class ValidateServiceId implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getAttributes();
        $serviceId = $data['serviceId'] ?? null;

        if (!$serviceId) {
            throw ServiceException::noServiceAdded();
        }
        $service = $this->entityManager->getRepository(Service::class)->find($serviceId);
        if (!$service) {
            throw ServiceException::serviceNotFound();
        }

        return $handler->handle($request);
    }
}
