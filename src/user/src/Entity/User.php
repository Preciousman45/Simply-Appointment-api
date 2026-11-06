<?php

declare(strict_types=1);

namespace user\Entity;

use Doctrine\ORM\Mapping as ORM;
use user\Entity\Role;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'users')]           // this class ↔ the products table
class User
{
    #[ORM\Id]                             // primary key...
    #[ORM\Column(name: 'user_id', type: 'guid')]                // ...assigned by the database
    private ?string $user_id = null;              // null until first flush()

    #[ORM\Column(type: 'string', length: 255, name: 'email')]
    private ?string $email;                 // email VARCHAR(120) ↔ $email

    #[ORM\Column(type: 'string', length: 120, name: 'name')]
    private ?string $name;                 // name VARCHAR(120) ↔ $name

    #[ORM\Column(type: 'string', name: 'password', length: 255)]
    private ?string $password;

    #[ORM\Column(type: 'string', name: 'phone_number', length: 255)]
    private ?string $phone_number;

    #[ORM\Column(type: 'boolean', name: 'is_available')]
    private ?bool $is_available;

    #[ORM\Column(type: 'string', name: 'usertype', length: 255)]
    private ?string $usertype;

    #[ORM\Column(type: 'string', name: 'address', length: 255)]
    private ?string $address;

    #[ORM\Column(type: 'boolean', name: 'is_deleted')]
    private bool $is_deleted;

    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'id', nullable: false)]
    private ?Role $role;

    public function __construct(
        string $name,
        string  $email,
        string  $phone_number,
        bool $is_available,
        string $usertype,
        string  $address,
        bool $is_deleted,
        string $password,
        Role $role,

    ) {
        $this->name = $name;
        $this->user_id =  Uuid::uuid4()->toString();
        $this->role = $role;
        $this->is_deleted = $is_deleted;
        $this->usertype = $usertype;
        $this->phone_number = $phone_number;
        $this->is_deleted = $is_deleted;
        $this->is_available = $is_available;
        $this->email = $email;
        $this->address = $address;
        $this->password = $password;
    }

    public function user_id(): ?string
    {
        return $this->user_id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function phone_number(): string
    {
        return $this->phone_number;
    }

    public function is_available(): bool
    {
        return $this->is_available;
    }


    public function usertype(): string
    {
        return $this->usertype;
    }


    public function address(): string
    {
        return $this->address;
    }


    public function is_deleted(): bool
    {
        return $this->is_deleted;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function role(): ?Role
    {
        return $this->role;
    }

    public function set_name(string $name): void
    {
        $this->name = $name;
    }


    public function set_user_id(string $user_id): void
    {
        $this->user_id = $user_id;
    }
    public function set_email(string $email): void
    {
        $this->email = $email;
    }

    public function set_phone_number(string $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    public function set_is_available(bool $is_available): void
    {
        $this->is_available = $is_available;
    }

    public function set_usertype(string $usertype): void
    {
        $this->usertype = $usertype;
    }

    public function set_address(string $address): void
    {
        $this->address = $address;
    }

    public function set_is_deleted(bool $is_deleted): void
    {
        $this->is_deleted = $is_deleted;
    }

    public function set_password(string $password): void
    {
        $this->password = $password;
    }

    public function set_role(Role $role): void
    {
        $this->role = $role;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'is_available' => $this->is_available,
            'usertype' => $this->usertype,
            'address' => $this->address,
            'is_deleted' => $this->is_deleted,
            'role_id'   => $this->role ? $this->role->id() : null,
        ];
    }
}
