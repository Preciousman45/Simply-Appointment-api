<?php

declare(strict_types=1);

namespace Appointments\Service;

use App\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Appointments\Entity\Appointments;
use user\Entity\User;
use service\Entity\Service;
use Appointments\Entity\Notification;
use Throwable;
use DateTimeImmutable;
use Appointments\Event\ProcessCustomerReminderMail;




class SendCustomerAppointmentReminderMail
{
    public function __construct(private MailService $mailer, private EntityManagerInterface $entityManager) {}


    public function sendReminderMail(string  $appointment_id,   string $label)
    {

        $appointment = $this->entityManager->getRepository(Appointments::class)->findOneBy(['appointment_id' => $appointment_id]);
        $user = $appointment->user_id();
        $worker = $appointment->staff_Id();
        $customer = $this->entityManager->getRepository(User::class)->findOneBy(['user_id' => $user]);
        $service = $this->entityManager->getRepository(Service::class)->findOneBy(['service_id' => $appointment->service_id()]);
        $staff = $this->entityManager->getRepository(User::class)->findOneBy(['user_id' => $worker]);


        $dateString = $appointment->date()->format('Y:m:d');
        $serviceName = $service->service_name();
        $customerName = $customer->name();
        $customerMail = $customer->email();
        $customerId = $customer->user_id();
        $startTimeString = $appointment->time()->format('H:i:s');
        $endTimeString = $appointment->endTime()->format('H:i:s');
        $staffName = $staff->name();

        $message =  "Hello {$customerName}\n"
            . "This is a reminder mail of your appointment with following details:\n\n"
            . "Staff Name: {$staffName}\n"
            . "Service: {$serviceName}\n"
            . "Appointment Date: {$dateString}\n"
            . "Starting Time: {$startTimeString}\n"
            . "Ending Time: {$endTimeString}\n\n"
            . "Please ensure to available 5 minute before the meeting or canceal the appointment 30mins to the aloted time.";

        $senttime = new DateTimeImmutable();
        $notificationType = "{$label} Reminder";


        try {
            $this->mailer->sendMail($customerMail, $notificationType, $message);

            $notification = new Notification(
                $customer,
                $appointment,
                $notificationType,   // type first
                $message,            // then message
                new DateTimeImmutable(),
                $customerMail,
            );
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        } catch (\Throwable $e) {
            error_log('>>> Mailer failed: ' . $e->getMessage());
        }
    }
}
