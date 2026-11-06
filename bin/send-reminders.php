<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$container = require 'config/container.php';

$result = $container->get(\Appointments\Service\AppointmentReminder::class)->run();

echo date('c') . " 24h: {$result['24h']}, 1h: {$result['1h']}\n";
