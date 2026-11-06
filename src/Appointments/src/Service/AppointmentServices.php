<?php

declare(strict_types=1);

namespace Appointments\Service;


use Appointments\Entity\Appointments;
use Appointments\Entity\OpenHours;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use service\Entity\Service;
use service\Entity\StaffService;
use user\Entity\User;
use Assert\Assertion;
use Throwable;
use Doctrine\DBAL\Types\Types;
use Ramsey\Uuid\Uuid;
use Appointments\Exceptions\AppointmentExceptions;
use Psr\EventDispatcher\EventDispatcherInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Appointments\Event\SendScheduledAppointmentMailEvent;


class AppointmentServices
{

    const MAX_SERVICE = 7;
    const BOOKING_WINDOW_DAYS = 30;


    public function __construct(private EntityManagerInterface $entityManager, private EventDispatcherInterface $dispatcher) {}

    public function bookAppointment(string $userId, array $data)
    {
        $appointmentDate = new DateTimeImmutable($data['date']);
        $startTime       = new DateTimeImmutable($data['date'] . ' ' . $data['time']);

        $service = $this->entityManager->getRepository(Service::class)
            ->findOneBy(['service_id' => $data['service_id']]);

        if (!$service) {
            throw AppointmentExceptions::serviceNotFound();
        }

        $endTime = $startTime->modify('+' . $service->duration() . ' minutes');

        if ($startTime < new DateTimeImmutable()) {
            throw AppointmentExceptions::appointmentInPast();
        }



        if (!$this->isWithinOpenAndCloseingHours($appointmentDate, $startTime, $endTime)) {
            throw AppointmentExceptions::outsideOpenHours();
        }


        if (!empty($data['staff_id'])) {
            $staffService = $this->entityManager->getRepository(StaffService::class)
                ->findOneBy(['user_id' => $data['staff_id'], 'service_id' => $data['service_id']]);

            if (!$staffService) {
                throw AppointmentExceptions::staffDoesNotOfferService();
            }

            $staffId = $data['staff_id'];
        } else {
            $staffList = $this->entityManager->getRepository(StaffService::class)
                ->findBy(['service_id' => $data['service_id']]);

            if (!$staffList) {
                throw AppointmentExceptions::noStaffOffersThisService();
            }

            $staffId = $staffList[array_rand($staffList)]->staff_id();
        }

        // Load User entities
        $user  = $this->entityManager->getRepository(User::class)->findOneBy(['user_id' => $userId]);
        $staff = $this->entityManager->getRepository(User::class)->findOneBy(['user_id' => $staffId]);




        // Daily limit and conflicts
        if ($this->checkForNumberOfAppointment($staff, $appointmentDate) >= self::MAX_SERVICE) {
            throw AppointmentExceptions::dailyLimitReached(7);
        }

        if ($this->checkForConflictingAppointmentTime($staff, $appointmentDate, $startTime, $endTime)) {
            throw AppointmentExceptions::timeSlotTaken();
        }

        $status = 'Pending';
        $appointment_id = Uuid::uuid4()->toString();
        // Create and save
        $appointment = new Appointments(
            $appointment_id,
            $status,
            $appointmentDate,
            $startTime,
            $endTime,
            $service,
            $staff,
            $user,
            new DateTimeImmutable()
        );

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        $appointmentId = $appointment->Id();

        $this->dispatcher->dispatch(new SendScheduledAppointmentMailEvent($appointmentId));


        return new JsonResponse(['Details' => 'Appointment Booked Successfully, .', 'status' => 201]);
    }



    public function rescheduleAppointment(string $appointmentId, array $data)
    {
        $appointment = $this->entityManager->getRepository(Appointments::class)->find($appointmentId);

        if (!$appointment) {
            throw AppointmentExceptions::appointmentNotFound();
        }

        if ($appointment->status() === 'cancelled') {
            throw AppointmentExceptions::appointmentCancelled();
        }

        // Validate input
        try {
            Assertion::notEmptyKey($data, 'time', 'the time field is required');
            Assertion::date($data['time'], 'H:i:s', 'the time must be in H:i:s format');

            if (!empty($data['date'])) {
                Assertion::date($data['date'], 'Y-m-d', 'the date must be in Y-m-d format');
            }
        } catch (Throwable $e) {
            //throw AuthException::fromThrowable($e);
        }


        $appointmentDate = !empty($data['date'])
            ? new DateTimeImmutable($data['date'])
            : DateTimeImmutable::createFromInterface($appointment->date());

        $startTime = new DateTimeImmutable($appointmentDate->format('Y-m-d') . ' ' . $data['time']);
        $endTime   = $startTime->modify('+' . $appointment->service_id()->duration() . ' minutes');
        $staff     = $appointment->staff_id();

        // Past check
        if ($startTime < new DateTimeImmutable()) {
            throw AppointmentExceptions::appointmentInPast();
        }

        // Open hours, daily limit, conflicts (all exclude this appointment)
        if (! $this->isWithinOpenAndCloseingHours($appointmentDate, $startTime, $endTime)) {
            throw AppointmentExceptions::outsideOpenHours();
        }


        if ($this->checkForConflictingAppointmentTime($staff, $appointmentDate, $startTime, $endTime)) {
            throw AppointmentExceptions::timeSlotTaken();
        }

        // Save
        $appointment->setDate($appointmentDate);
        $appointment->setTime($startTime);
        $appointment->setEndTime($endTime);

        $this->entityManager->flush();

        return $appointment;
    }
    private function isWithinOpenAndCloseingHours(DateTimeImmutable $date, DateTimeImmutable $start, DateTimeImmutable $end)
    {
        $dayOfWeek = $date->format('l');

        $openHours = $this->entityManager
            ->getRepository(OpenHours::class)
            ->findOneBy(['day' => $dayOfWeek]);

        if (!$openHours) {
            throw AppointmentExceptions::noOpeningHoursSetForThisDay($dayOfWeek);
        }

        $dateString = $date->format('Y-m-d');
        $openTime   = new DateTimeImmutable($dateString . ' ' . $openHours->open()->format('H:i:s'));
        $closeTime  = new DateTimeImmutable($dateString . ' ' . $openHours->close()->format('H:i:s'));

        if ($start < $openTime || $end > $closeTime) {
            throw AppointmentExceptions::outsideOpenHours();
        }

        return true;
    }

    private function checkForNumberOfAppointment(User $staff, DateTimeImmutable $date): int
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(a.appointment_id)')
            ->from(Appointments::class, 'a')
            ->where('a.staff_id = :staff')
            ->andWhere('a.date = :date')
            ->andWhere('a.status != :cancelled')
            ->setParameter('staff', $staff)
            ->setParameter('date', $date, Types::DATE_IMMUTABLE)
            ->setParameter('cancelled', 'cancelled');


        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    private function checkForConflictingAppointmentTime(User $staff, DateTimeImmutable $date, DateTimeImmutable $start, DateTimeImmutable $end): bool
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('COUNT(a.appointment_id)')
            ->from(Appointments::class, 'a')
            ->where('a.staff_id = :staff')
            ->andWhere('a.date = :date')
            ->andWhere('a.status = :status')
            ->andWhere('a.time < :end')
            ->andWhere('a.endTime > :start')
            ->setParameter('staff', $staff)
            ->setParameter('date', $date, Types::DATE_IMMUTABLE)
            ->setParameter('status', 'approved')
            ->setParameter('start', $start, Types::DATETIME_IMMUTABLE)
            ->setParameter('end', $end, Types::DATETIME_IMMUTABLE);



        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }

    public function rescheduleUserAppointment(string $userId, string $appointmentId, array $data)
    {
        $appointment = $this->entityManager->getRepository(Appointments::class)
            ->findOneBy(['appointment_id' => $appointmentId, 'user_id' => $userId]);

        if (!$appointment) {
            throw AppointmentExceptions::appointmentNotFound();
        }
        return $this->rescheduleAppointment($appointmentId, $data);
    }


    public function getAvailableDatesWithin30Days(string $staffId): array
    {
        $staff = $this->entityManager->getRepository(User::class)->findOneBy(['user_id' => $staffId]);

        $from = new DateTimeImmutable('today');
        $to = $from->modify('last day of this month');


        $rows = $this->entityManager->createQueryBuilder()
            ->select('a.date AS day, COUNT(a.appointment_id) AS total')
            ->from(Appointments::class, 'a')
            ->where('a.staff_id = :staff')
            ->andWhere('a.date BETWEEN :from AND :to')
            ->andWhere('a.status != :cancelled')
            ->groupBy('a.date')
            ->setParameter('staff', $staff)
            ->setParameter('from', $from, Types::DATE_IMMUTABLE)
            ->setParameter('to', $to, Types::DATE_IMMUTABLE)
            ->setParameter('cancelled', 'cancelled')
            ->getQuery()
            ->getResult();


        $counts = [];
        foreach ($rows as $row) {
            $day = $row['day'];

            $counts[$day] = (int) $row['total'];
        }


        $available = [];
        $date = $from;

        while ($date <= $to) {
            $key   = $date->format('Y-m-d');
            $taken = $counts[$key] ?? 0;

            if ($taken < self::MAX_SERVICE) {
                $available[] = [
                    'date'      => $key,
                    'booked'    => $taken,
                    'remaining' => self::MAX_SERVICE - $taken,
                ];
            }

            $date = $date->modify('+1 day');
        }

        return $available;
    }
}
