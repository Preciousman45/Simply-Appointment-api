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

class GetServices implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $services = $this->entityManager->getRepository(Service::class)->findAll();

        if (!$services) {
            throw ServiceException::serviceNotFound();
        }
        $data = [];
        foreach ($services as $service) {
            $data[] = $service->toArray();
        }

        return new JsonResponse([

            "details" => "OK",
            "Status" => 200,
            'content' => $data,
        ]);
    }
}
