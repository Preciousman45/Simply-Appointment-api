<?php

declare(strict_types=1);

namespace service\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'services')]           // this class ↔ the products table
class Service
{
    #[ORM\Id]                             // primary key...
    #[ORM\Column(name: 'service_id', type: 'guid')]            // ...assigned by the database
    private ?string $service_id = null;              // null until first flush()

    #[ORM\Column(type: 'string', length: 120, name: 'service_name', unique: true)]
    private string $service_name;


    #[ORM\Column(type: 'string', length: 255, name: 'description')]
    private string $description;


    #[ORM\Column(type: 'integer', name: 'price')]
    private int $price;


    #[ORM\Column(type: 'boolean', name: 'is_deleted')]
    private bool $is_deleted;


    #[ORM\Column(type: 'integer', name: 'duration')]
    private int $duration; // price ↔ $price

    public function __construct(

        string $service_name,
        string $description,
        int $price,
        bool $is_deleted,
        int $duration
    ) {

        $this->service_id = Uuid::uuid4()->toString();
        $this->service_name = $service_name;
        $this->is_deleted = $is_deleted;
        $this->description = $description;
        $this->price = $price;
        $this->duration = $duration;
    }

    public function service_id(): ?string
    {
        return $this->service_id;
    }

    public function service_name(): string
    {
        return $this->service_name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function duration(): int
    {
        return $this->duration;
    }

    public function is_deleted(): bool
    {
        return $this->is_deleted;
    }

    public function description(): string
    {
        return $this->description;
    }



    public function set_service_id(string $service_id): void
    {
        $this->service_id = $service_id;
    }
    public function set_service_name(string $service_name): void
    {
        $this->service_name = $service_name;
    }


    public function set_description(string $description): void
    {
        $this->description = $description;
    }

    public function set_price(int $price): void
    {
        $this->price = $price;
    }

    public function set_duration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function set_deleted(bool $is_deleted): void
    {
        $this->is_deleted = $is_deleted;
    }



    /** @return array{id: int|null, name: string, price: float} */
    public function toArray(): array
    {
        return [
            'price' => $this->price,
            'duration' => $this->duration,
            'description' => $this->description,
            'is_deleted' => $this->is_deleted,
            'service_id' => $this->service_id,
            'service_name' => $this->service_name,

        ];
    }
}
