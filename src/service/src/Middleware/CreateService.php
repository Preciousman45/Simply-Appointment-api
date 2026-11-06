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
use Throwable;
use service\Exceptions\ServiceException;
use Assert\Assertion;

class CreateService implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();
        try {
            Assertion::isArray($data, 'Invalid request body. Expected an array.');
            Assertion::keyExists($data, 'name', 'Missing required field: name.');
            Assertion::keyExists($data, 'description', 'Missing required field: description.');
            Assertion::keyExists($data, 'price', 'Missing required field: price.');
            Assertion::keyExists($data, 'duration', 'Missing required field: duration.');

            $name = $data['name'];
            $description = $data['description'];
            $price = $data['price'];
            $duration = $data['duration'];
            $is_deleted = $data['is_deleted'] ?? null;

            $service = new Service($name, $description, $price, $is_deleted, $duration);
            $this->entityManager->persist($service);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            throw ServiceException::fromServiceThrowable($e);
        }
        return new JsonResponse([
            'Details' => 'OK',
            "Status" => 201,
        ]);
    }
}
