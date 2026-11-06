<?php

declare(strict_types=1);

namespace Appointments\Entity;

use Doctrine\ORM\Mapping as ORM;
use user\Entity\User;
use Appointments\Entity\Appointments;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'notifications')]
class Notification
{

    #[ORM\Id]
    #[ORM\Column(name: 'notification_id', type: 'guid')]
    private ?string $notification_id = null;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: false)]
    private ?User $user_id = null;


    #[ORM\ManyToOne(targetEntity: Appointments::class)]
    #[ORM\JoinColumn(name: 'appointment_id', referencedColumnName: 'appointment_id', nullable: false)]
    private ?Appointments $appointment_id;


    #[ORM\Column(type: 'string', name: 'notificationtype', length: 50)]
    private string $notificationtype;


    #[ORM\Column(type: Types::TEXT, name: 'message')]
    private string $message;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: 'senttime')]
    private \DateTimeImmutable $senttime;

    #[ORM\Column(type: 'string', name: 'emailrecipients')]
    private string $emailrecipients;



    public function __construct(

        User $user_id,
        Appointments $appointment_id,
        string $notificationtype,
        string $message,
        \DateTimeImmutable $senttime,
        string $emailrecipients

    ) {
        $this->notification_id = Uuid::uuid4()->toString();
        $this->user_id  = $user_id;
        $this->appointment_id = $appointment_id;
        $this->notificationtype = $notificationtype;
        $this->message = $message;
        $this->senttime = $senttime;
        $this->emailrecipients = $emailrecipients;
    }

    public function notification_id(): ?string
    {
        return $this->notification_id;
    }

    public function user_id(): User
    {
        return $this->user_id;
    }

    public function appointment_id(): Appointments
    {
        return $this->appointment_id;
    }

    public function notificationtype(): string
    {
        return $this->notificationtype;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function senttime(): \DateTimeImmutable
    {
        return $this->senttime;
    }

    public function emailrecipients(): string
    {
        return $this->emailrecipients;
    }





    public function setNotification_id(string $notification_id)
    {
        $this->notification_id = $notification_id;
    }

    public function setUser_id(User $user_id)
    {
        $this->user_id = $user_id;
    }

    public function setAppointment_id(Appointments $appointment_id)
    {
        $this->appointment_id = $appointment_id;
    }

    public function setNotificationtype(string $notificationtype)
    {
        $this->notificationtype = $notificationtype;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function setSenttime(\DateTimeImmutable $senttime)
    {
        $this->senttime = $senttime;
    }

    public function setEmailrecipients(string $emailrecipients)
    {
        $this->emailrecipients = $emailrecipients;
    }

    public function toArray()
    {
        return
            [
                'notificationID' => $this->notification_id,
                'user' => $this->user_id,
                'appointmentId' => $this->appointment_id,
                'notification-type' => $this->notificationtype,
                'message' => $this->message,
                'sent-time' => $this->senttime,
                'email-recipients' => $this->emailrecipients,
            ];
    }
}
