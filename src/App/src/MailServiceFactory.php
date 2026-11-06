<?php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use App\MailService;

final class MailServiceFactory
{
    public function __invoke(ContainerInterface $container): MailService
    {
        $config = $container->get('config')['mail'];

        return new MailService(
            new Mailer(Transport::fromDsn($config['dsn'])),
            $config['from'],
        );
    }
}
