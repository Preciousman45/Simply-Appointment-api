<?php

declare(strict_types=1);

namespace Appointments\Service;

use Appointments\Entity\Notification;
use DateTimeImmutable;
use user\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Appointments\Entity\Appointments;
use Appointments\Exceptions\AppointmentExceptions;
use App\MailService;
use service\Entity\Service;
use Appointments\Event\SendScheduledAppointmentMailEvent;


final class AppointmentNotification
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailService $mailer
    ) {}

    public function __invoke(SendScheduledAppointmentMailEvent $event): void
    {
        error_log('>>> AppointmentNotification called for ID: ' . $event->appointmentId);

        $appointment = $this->entityManager
            ->getRepository(Appointments::class)
            ->findOneBy(['appointment_id' => $event->appointmentId]);


        if (!$appointment) {
            error_log('>>> Appointment not found for ID: ' . $event->appointmentId);
            return;
        }

        $this->sendScheduledAppointmentMailToStaff($appointment);
    }

    public function sendScheduledAppointmentMailToStaff(Appointments $appointment): bool
    {
        $userDetails = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['user_id' => $appointment->user_id()]);

        $staffDetails = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['user_id' => $appointment->staff_id()]);

        $serviceDetails = $this->entityManager
            ->getRepository(Service::class)
            ->findOneBy(['service_id' => $appointment->service_id()]);

        if (!$userDetails || !$staffDetails || !$serviceDetails) {
            error_log('>>> Missing user/staff/service for appointment notification');
            return false;
        }

        $notification_type = 'Scheduled Staff Appointment';
        $staffMail = $staffDetails->email();

        $dateString = $appointment->date() instanceof \DateTimeInterface
            ? $appointment->date()->format('Y-m-d')
            : (string) $appointment->date();

        $startTimeString = $appointment->time() instanceof \DateTimeInterface
            ? $appointment->time()->format('H:i:s')
            : (string) $appointment->time();

        $endTimeString = $appointment->endTime() instanceof \DateTimeInterface
            ? $appointment->endTime()->format('H:i:s')
            : (string) $appointment->endTime();

        $customerName = $userDetails->name();
        $serviceName  = $serviceDetails->service_name();

        $message =  "Hello,\n\n"
            . "A new appointment has been scheduled for you on {$dateString} with the following details:\n\n"
            . "Customer Name: {$customerName}\n"
            . "Service: {$serviceName}\n"
            . "Appointment Date: {$dateString}\n"
            . "Starting Time: {$startTimeString}\n"
            . "Ending Time: {$endTimeString}\n\n"
            . "Please log in to review the appointment.";

        $senttime = new DateTimeImmutable();


        try {
            $this->mailer->sendMail($staffMail, $notification_type, $message);
        } catch (\Throwable $e) {
            error_log('>>> Mailer failed: ' . $e->getMessage());
            throw AppointmentExceptions::fromAppointmentThrowable($e);
        }

        $newNotification = new Notification(
            $userDetails,
            $appointment,
            $notification_type,
            $message,
            $senttime,
            $staffMail
        );

        try {
            $this->entityManager->persist($newNotification);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            error_log('>>> Notification save failed: ' . get_class($e) . ' - ' . $e->getMessage());
            return false;
        }

        return true;
    }
}


//wsl -d Ubuntu-24.04 
//php vendor/bin/laminas mezzio:swoole:start