<?php

declare(strict_types=1);

namespace user\Middleware;

use App\Auth\Exception\AuthException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use user\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Auth\JwtService;
use Assert\Assertion;
use Throwable;
use user\Exceptions\UserExceptions;



class Login implements MiddlewareInterface

{

    public function __construct(private EntityManagerInterface $entityManager, private readonly JwtService $jwtService) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $data = $request->getParsedBody();

        try {
            Assertion::notEmptyKey($data, 'password', 'password was not provided, please provide a password');
            Assertion::notEmptyKey($data, 'email', 'email was not provided, please provide an email');
        } catch (Throwable $th) {
            throw UserExceptions::fromUserThrowable($th);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $data['email'], 'password' => $data['password']]);

        if (!$user) {
            throw UserExceptions::userNotFound();
        }

        $this->entityManager->flush();

        $token = $this->jwtService->generateToken([
            'sub' => $user->user_Id(),
            'name' => $user->name(),
            'email' => $user->email(),
            'role' => $user->address(),
            'login_token' => $user->phone_number(),
        ]);

        return new JsonResponse([
            "Status" => 200,
            'description' => 'Login successful',
            "JWT-TOKEN" => $token,

        ]);
    }
}
