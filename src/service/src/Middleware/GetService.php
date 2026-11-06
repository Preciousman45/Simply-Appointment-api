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

class GetService implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $serviceId = $request->getAttribute('serviceId');
        $service = $this->entityManager->getRepository(Service::class)->find($serviceId);
        if (!$service) {
            throw ServiceException::serviceNotFound();
        }
        return new JsonResponse([

            'Details' => 'OK',
            "Status" => 200,
            'Content' => $service->toArray(),
        ]);
    }
}
