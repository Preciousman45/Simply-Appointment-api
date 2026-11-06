<?php

declare(strict_types=1);

namespace user\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'roles')]           // this class ↔ the products table
class Role
{
    #[ORM\Id]                             // primary key...
    #[ORM\GeneratedValue]                 // ...assigned by the database
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;              // null until first flush()

    #[ORM\Column(type: 'string', length: 255, name: 'name')]
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function id(): ?int
    {
        return $this->id;
    }
}
