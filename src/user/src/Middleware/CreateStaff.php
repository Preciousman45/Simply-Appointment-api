<?php

declare(strict_types=1);

namespace user\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use user\Entity\User;
use user\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use user\Exceptions\UserExceptions;
use Assert\Assertion;
use Throwable;

class CreateStaff implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();



        try {
            Assertion::isArray($data, 'the request body is required');
            Assertion::notEmptyKey($data, 'name',       'the name field is required');
            Assertion::notEmptyKey($data, 'email',       'the email field is required');
            Assertion::notEmptyKey($data, 'is_available', 'the is_avalablefield is required');
            Assertion::notEmptyKey($data, 'usertype', 'the usertype is required');
            Assertion::notEmptyKey($data, 'address',       'the address field is required');
            Assertion::notEmptyKey($data, 'is_deleted',       'the is_deleted field is required');
            Assertion::notEmptyKey($data, 'password', 'the password field is required');
            Assertion::notEmptyKey($data, 'role_id', 'the role_id field is required');
        } catch (Throwable $th) {
            throw UserExceptions::fromUserThrowable($th);
        }


        $name = $data['name'];
        $email = $data['email'];
        $phone_number = $data['phone_number'];
        $is_available = $data['is_available'];
        $usertype = $data['usertype'] ?? "customer";
        $address = $data['address'];
        $is_deleted = $data['is_deleted'];
        $password = $data['password'];
        $role_id = $data['role_id'];


        $role = $this->entityManager->find(Role::class, (int) $role_id);

        if (!$role) {
            throw UserExceptions::noRoleFound();
        }


        try {
            $user = new User($name, $email, $phone_number, $is_available, $usertype, $address, $is_deleted, $password, $role);


            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (Throwable $e) {
            throw UserExceptions::fromUserThrowable($e);
        }


        return new JsonResponse([
            'Details' => 'OK',
            'Content' => $user->toArray(),
            'Status' => 201
        ]);
    }
}
