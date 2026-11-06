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
use App\Auth\Exception\AuthException;
use service\Exceptions\ServiceException;
use Throwable;


class UpdateService implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $serviceId = $request->getAttribute('serviceId');
        $data = $request->getParsedBody();
        $service = $this->entityManager->getRepository(Service::class)->findoneby(['service_id' => $serviceId]);

        if (!$service) {
            throw ServiceException::serviceNotFound();
        }

        try {
            $service->set_service_name($data['name'] ?? $service->service_name());
            $service->set_description($data['description'] ?? $service->description());
            $service->set_price($data['price'] ?? $service->price());
            $service->set_duration($data['duration'] ?? $service->duration());
            $service->set_deleted($data['is_deleted'] ?? $service->is_deleted());
            $this->entityManager->persist($service);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            throw ServiceException::fromServiceThrowable($e);
        }


        return new JsonResponse([
            'Details' => 'OK.',
            "Status" => 200,
        ]);
    }
}
