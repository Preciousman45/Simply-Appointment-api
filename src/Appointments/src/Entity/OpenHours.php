<?php

declare(strict_types=1);

namespace Appointments\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'open_hours')]           // this class ↔ the products table
class OpenHours
{
    #[ORM\Id]                             // primary key...
    #[ORM\GeneratedValue]                 // ...assigned by the database
    #[ORM\Column(name: 'id', type: 'integer')]
    private ?int $id = null;              // null until first flush()

    #[ORM\Column(name: 'day', type: 'string', length: 120)]
    private string $day;                 // name VARCHAR(120) ↔ $name

    #[ORM\Column(type: 'time', name: 'open')]
    private \DateTimeInterface $open;

    #[ORM\Column(type: 'time', name: 'close')]
    private \DateTimeInterface $close;

    public function Id(): ?int
    {
        return $this->id;
    }

    public function day(): string
    {
        return $this->day;
    }

    public function open(): \DateTimeInterface
    {
        return $this->open;
    }

    public function close(): \DateTimeInterface
    {
        return $this->close;
    }

    public function setDay(string $day): void
    {
        $this->day = $day;
    }

    public function setOpen(\DateTimeInterface $open): void
    {
        $this->open = $open;
    }

    public function setClose(\DateTimeInterface $close): void
    {
        $this->close = $close;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'open' => $this->open->format('H:i:s'),
            'close' => $this->close->format('H:i:s'),
        ];
    }
}
