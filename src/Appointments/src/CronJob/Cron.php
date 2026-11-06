<?php

declare(strict_types=1);

namespace Appointments\CronJob;

use Cron\CronExpression;
use DateTimeImmutable;
use DateTimeZone;
use Psr\EventDispatcher\EventDispatcherInterface;
use Mezzio\Swoole\Event\WorkerStartEvent;
use Psr\Container\ContainerInterface;
use Swoole\Timer;
use Throwable;

class Cron
{
    private const TICK_MS = 15000;

    private array $lastRun = [];

    public function __construct(private ContainerInterface $container) {}

    public function __invoke(WorkerStartEvent $event): void
    {
        if ($event->getWorkerId() !== 0) {
            return;
        }

        $jobs = $this->container->get('config')['jobs'];

        $startedAt = new DateTimeImmutable();

        echo "[Scheduler] Started on worker 0 with " . count($jobs) . " jobs\n";
        echo "[Scheduler] Timezone: " . date_default_timezone_get() . " (UTC{$startedAt->format('P')})\n";
        echo "[Scheduler] Local time now: {$startedAt->format('Y-m-d H:i:s')}\n";
        echo "[Scheduler] UTC time now:   {$startedAt->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d H:i:s')}\n";

        Timer::tick(self::TICK_MS, function () use ($jobs) {
            $now = new DateTimeImmutable();
            $key = $now->format('Y-m-d H:i');

            foreach ($jobs as $name => $job) {
                if (($this->lastRun[$name] ?? null) === $key) {
                    continue;
                }

                if (!(new CronExpression($job['schedule']))->isDue($now)) {
                    continue;
                }

                $this->lastRun[$name] = $key;

                try {
                    /** @var EventDispatcherInterface $dispatcher */
                    $dispatcher = $this->container->get(EventDispatcherInterface::class);

                    echo "[Scheduler][{$now->format('H:i:s T')}] {$name} dispatched\n";
                    $dispatcher->dispatch(new $job['event']());
                } catch (Throwable $e) {
                    echo "[Scheduler Error] {$name}: " . $e->getMessage() . "\n";
                }
            }
        });
    }
}
