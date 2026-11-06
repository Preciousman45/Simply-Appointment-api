<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use service\Entity\StaffService;
use Doctrine\ORM\EntityManagerInterface;
use service\Exceptions\ServiceException;

class RemoveService implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffId = $request->getAttribute('staffId');
        $serviceId = $request->getAttribute('serviceId');
        try {

            $staffService = $this->entityManager->getRepository(StaffService::class)->findOneBy([
                'user_id' => $staffId,
                'service_id' => $serviceId
            ]);

            if (!$staffService) {
                throw ServiceException::serviceNotFound();
            }

            $this->entityManager->remove($staffService);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            throw ServiceException::fromServiceThrowable($e);
        }
        return new JsonResponse([
            'Details' => 'OK.',
            "Status" => 200
        ]);
    }
}
