<?php

declare(strict_types=1);

namespace service\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use service\Entity\Service;
use service\Entity\StaffService;
use service\Exceptions\ServiceException;
use Throwable;
use user\Entity\User;
use user\Exceptions\UserExceptions;

class AddStaffService implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffId   = $request->getAttribute('staffId');
        $serviceId = $request->getAttribute('serviceId');

        try {
            $user = $this->entityManager->getRepository(User::class)->find($staffId);
            if (!$user) {
                throw UserExceptions::userNotFound();
            }

            $service = $this->entityManager->getRepository(Service::class)->find($serviceId);
            if (!$service) {
                throw ServiceException::serviceNotFound();
            }

            $staffService = new StaffService($user, $service);
            $this->entityManager->persist($staffService);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            throw ServiceException::fromServiceThrowable($e);
        }

        return new JsonResponse([
            'details' => 'Staff service added successfully.',
            'status'  => 201,
        ], 201);
    }
}
