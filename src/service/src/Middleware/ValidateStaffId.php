<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class ValidateStaffId implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffId = $request->getAttribute('staffId');



        if (!$staffId) {
            return new JsonResponse([
                'Type' => 'Invalid request data',
                "Title" => "Bad Request",
                'Details' => 'Missing required fields.',
                "Status" => 400,
                "Instance" => "staffService\\Middleware\\addStaffService",
            ], 400);
        }

        return $handler->handle($request);
    }
}
