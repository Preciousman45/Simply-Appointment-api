<?php

declare(strict_types=1);

namespace Appointments\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use service\Entity\Service;
use user\Entity\User;

#[ORM\Entity]
#[ORM\Table(name: 'appointments')]
class Appointments
{
    #[ORM\Id]
    #[ORM\Column(name: 'appointment_id', type: 'guid')]
    private ?string $appointment_id = null;

    #[ORM\Column(name: 'status', type: 'string', length: 120)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, name: 'date')]
    private \DateTimeImmutable $date;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'time')]
    private ?\DateTimeImmutable $time = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'end_time')]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'created_at')]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'staff_id', referencedColumnName: 'user_id', nullable: false)]
    private ?User $staff_id = null;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(name: 'service_id', referencedColumnName: 'service_id', nullable: false)]
    private ?Service $service_id = null;

    #[ORM\Column(name: 'reminder_1h_sent_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $reminder_1h_sent_at = null;

    #[ORM\Column(name: 'reminder_24h_sent_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $reminder_24h_sent_at = null;

    #[ORM\Column(name: 'reminder_3d_sent_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $reminder_3d_sent_at = null;

    #[ORM\Column(name: 'reminder_7d_sent_at', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $reminder_7d_sent_at = null;

    public function __construct(
        string $appointment_id,
        string $status,
        \DateTimeImmutable $date,
        \DateTimeImmutable $time,
        \DateTimeImmutable $endTime,
        Service $service_id,
        User $staff_id,
        User $user_id,
        \DateTimeImmutable $createdAt
    ) {
        $this->appointment_id = $appointment_id;
        $this->status = $status;
        $this->date = $date;
        $this->time = $time;
        $this->endTime = $endTime;
        $this->service_id = $service_id;
        $this->staff_id = $staff_id;
        $this->user_id = $user_id;
        $this->createdAt = $createdAt;
    }

    public function Id(): ?string
    {
        return $this->appointment_id;
    }

    public function status(): ?string
    {
        return $this->status;
    }

    public function date(): \DateTimeInterface
    {
        return $this->date;
    }

    public function time(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function endTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function staff_id(): ?User
    {
        return $this->staff_id;
    }

    public function user_id(): ?User
    {
        return $this->user_id;
    }

    public function service_id(): ?Service
    {
        return $this->service_id;
    }

    public function markReminderSent(string $type): void
    {
        $now = new DateTimeImmutable();

        match ($type) {
            '1h'    => $this->reminder_1h_sent_at = $now,
            '24h'   => $this->reminder_24h_sent_at = $now,
            '3d'    => $this->reminder_3d_sent_at = $now,
            '7d'    => $this->reminder_7d_sent_at = $now,
            default => throw new \InvalidArgumentException("Unknown reminder type: {$type}"),
        };
    }

    public function setAppointmentId(string $appointment_id): void
    {
        $this->appointment_id = $appointment_id;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function setTime(\DateTimeImmutable $time): void
    {
        $this->time = $time;
    }

    public function setEndTime(\DateTimeImmutable $endTime): void
    {
        $this->endTime = $endTime;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUserId(User $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setstaff_Id(User $staff_id): void
    {
        $this->staff_id = $staff_id;
    }

    public function setservice_id(Service $service_id): void
    {
        $this->service_id = $service_id;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'id'         => $this->appointment_id,
            'status'     => $this->status,
            'date'       => $this->date->format('Y-m-d'),
            'time'       => $this->time?->format('H:i:s'),
            'end_time'   => $this->endTime?->format('H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'user_id'    => $this->user_id?->user_id(),
            'staff_id'   => $this->staff_id?->user_id(),
            'service_id' => $this->service_id?->service_id(),
        ];
    }
}
