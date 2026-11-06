<?php

declare(strict_types=1);

namespace service\Entity;

use Doctrine\ORM\Mapping as ORM;
use user\Entity\User;
use service\Entity\Service;



#[ORM\Entity]
#[ORM\Table(name: 'staff_services')]           // this class ↔ the staff services table
class StaffService
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: false)]
    private User $user_id;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(name: 'service_id', referencedColumnName: 'service_id', nullable: false)]
    private Service $service_id;

    public function __construct(User $staff_id, Service $service_id)
    {
        $this->user_id = $staff_id;
        $this->service_id = $service_id;
    }



    public function staff_id(): User
    {
        return $this->user_id;
    }

    public function service_id(): Service
    {
        return $this->service_id;
    }

    public function setStaffId(User $staff_id): void
    {
        $this->user_id = $staff_id;
    }

    public function setServiceId(Service $service_id): void
    {
        $this->service_id = $service_id;
    }




    /** @return array{id: int|null, name: string, price: float} */
    public function toArray(): array
    {
        return ['id' => $this->user_id, 'service_id' => $this->service_id];
    }
}
