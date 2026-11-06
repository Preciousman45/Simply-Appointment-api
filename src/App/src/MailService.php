<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use notification\Entity\Notification;
use DateTime;
use DateTimeImmutable;
use Laminas\Diactoros\Response\JsonResponse;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Throwable;

final class MailService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $from,
    ) {}



    public function sendMail(string $toEmail,  string $notificationtype, string $message)
    {
        try {
            $email = (new Email())
                ->from($this->from)
                ->to($toEmail)
                ->subject("{$notificationtype}")
                ->html("<p>  {$message}.</p>");

            $this->mailer->send($email);


            return True;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
