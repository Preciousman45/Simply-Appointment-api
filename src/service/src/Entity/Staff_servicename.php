<?php

declare(strict_types=1);

namespace service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Guid\Guid;
use user\Entity\User;

#[ORM\Entity]
#[ORM\Table(name: 'Staff_servicename')]           // this class ↔ the products table
class Staff_servicename
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'staff_id', referencedColumnName: 'user_id', nullable: false)]         // ...assigned by the database
    private ?string $staff_id = null;              // null until first flush()

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(name: 'serviceid', referencedColumnName: 'service_id', nullable: false)]
    private string $serviceid;
    // name VARCHAR(120) ↔ $name

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(name: 'servicename', referencedColumnName: 'service_name', nullable: false)]
    private string $servicename;


    public function __construct(
        string $serviceid,
        string $servicename,
        string $staff_id

    ) {
        $this->serviceid = $serviceid;
        $this->servicename = $servicename;
        $this->staff_id = $staff_id;
    }

    public function staff_id(): ?string
    {
        return $this->staff_id;
    }

    public function servicename(): string
    {
        return $this->servicename;
    }

    public function serviceid(): string
    {
        return $this->serviceid;
    }



    public function set_serviceid(string $service_id): void
    {
        $this->serviceid = $service_id;
    }
    public function set_servicename(string $service_name): void
    {
        $this->servicename = $service_name;
    }


    public function set_staff_id(string $staff_id): void
    {
        $this->staff_id = $staff_id;
    }



    public function toArray(): array
    {
        return [
            'staff_id' => $this->staff_id,
            'serviceid' => $this->serviceid,
            'servicename' => $this->servicename,

        ];
    }
}
