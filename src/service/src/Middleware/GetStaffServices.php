<?php

declare(strict_types=1);

namespace service\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use service\Entity\StaffService;
use service\Entity\Service;
use Doctrine\ORM\EntityManagerInterface;
use App\Auth\Exception\AuthException;
use service\Exceptions\ServiceException;


class GetStaffServices implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $staffId = $request->getAttribute('staffId');

        try {

            $serviceIds = $this->entityManager->getRepository(StaffService::class)
                ->createQueryBuilder('s')
                ->select('IDENTITY(s.service_id) as service_id') // Use the actual entity property name here
                ->where('s.user_id = :staffId')
                ->setParameter('staffId', $staffId)
                ->getQuery()
                ->getSingleColumnResult();

            if (!$serviceIds) {
                throw ServiceException::serviceNotFound();
            }


            $data = [];

            foreach ($serviceIds as $serviceid) {
                $services = $this->entityManager->getRepository(Service::class)->findBy([
                    'service_id' => $serviceid
                ]);

                foreach ($services as $service) {
                    $data[] = $service->toArray();
                }
            }
        } catch (\Throwable $e) {
            throw ServiceException::fromServiceThrowable($e);
        }

        return new JsonResponse([
            'detail' => 'OK',
            'status' => 200,
            'Data' => $data
        ]);
    }
}
