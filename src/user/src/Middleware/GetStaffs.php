<?php

declare(strict_types=1);

namespace user\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use user\Entity\User;
use service\Entity\StaffService;
use Doctrine\ORM\EntityManagerInterface;
use user\Exceptions\UserExceptions;

class GetStaffs implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffsId = $this->entityManager->getRepository(StaffService::class)
            ->createQueryBuilder('s')
            ->select('DISTINCT IDENTITY(s.user_id)')
            ->getQuery()
            ->getSingleColumnResult();


        if (!$staffsId) {
            throw UserExceptions::staffNotFound();
        }
        $data = [];

        if (!empty($staffsId)) {
            $users =  $this->entityManager->getRepository(User::class)->findBy(
                ['user_id' => $staffsId]
            );
            foreach ($users as $user) {
                $data[] = $user->toArray();
            };
        }

        return new JsonResponse([
            'Status' => 200,
            'Details' => 'OK',
            'Content' => $data
        ]);
    }
}
