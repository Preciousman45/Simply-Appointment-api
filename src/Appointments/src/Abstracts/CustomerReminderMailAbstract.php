<?php

declare(strict_types=1);

namespace Appointments\Abstracts;

use Appointments\Entity\Appointments;
use Appointments\Service\SendCustomerAppointmentReminderMail as ReminderMailService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

trait CustomerReminderMailAbstract
{
    public function processCustomerReminderMail(
        EntityManagerInterface $entityManager,
        ReminderMailService $reminderMail,
        int $millisecondsToRun,
        int $intervalForTheNextRun,
        string $label,
        string $type,
    ): int {
        $sent = 0;

        try {
            $column = match ($type) {
                '1h'  => 'reminder_1h_sent_at',
                '24h' => 'reminder_24h_sent_at',
                '3d'  => 'reminder_3d_sent_at',
                '7d'  => 'reminder_7d_sent_at',
            };

            $nowDt = new DateTimeImmutable();
            $now   = $nowDt->getTimestamp();
            $lower = $millisecondsToRun - $intervalForTheNextRun;
            $until = $nowDt->modify("+{$millisecondsToRun} seconds");

            $appointments = $entityManager
                ->createQueryBuilder()
                ->select('a')
                ->from(Appointments::class, 'a')
                ->where('a.date >= :today')
                ->andWhere('a.date <= :until')
                ->andWhere("a.reminder_{$type}_sent_at IS NULL")
                ->setParameter('today', $nowDt->format('Y-m-d'))
                ->setParameter('until', $until->format('Y-m-d'))
                ->getQuery()
                ->getResult();

            foreach ($appointments as $appointment) {
                $id = $appointment->Id();

                try {
                    $appointmentStartTime = new DateTimeImmutable(
                        $appointment->date()->format('Y-m-d') . ' ' . $appointment->time()->format('H:i:s')
                    );
                    $appointmentStartMiliseconds = $appointmentStartTime->getTimestamp() - $now;

                    if ($appointmentStartMiliseconds > $millisecondsToRun || $appointmentStartMiliseconds <= $lower) {
                        continue;
                    }

                    // 1. Claim the reminder first, so a repeat run skips this appointment.
                    $appointment->markReminderSent($type);
                    $entityManager->flush();

                    // 2. Send the email.
                    if ($reminderMail->sendReminderMail($id, $label)) {
                        $sent++;
                        continue;
                    }
                } catch (Throwable $e) {
                    error_log("[{$label}] appointment {$id} failed => " . $e->getMessage());

                    if (!$entityManager->isOpen()) {
                        error_log("[{$label}] EntityManager closed, aborting run");
                        break;
                    }
                }
            }
        } catch (Throwable $e) {
            error_log("[{$label}] failed => " . $e->getMessage());
        }

        echo "[Reminder] {$label} done. Sent: {$sent}\n";

        return $sent;
    }
}
