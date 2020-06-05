<?php

namespace App\Infrastructure\Queue\Middleware;

use App\Infrastructure\Queue\Stamp\UniqueIdStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\SentStamp;

class AppMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $messengerAuditLogger)
    {
        $this->logger = $messengerAuditLogger;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(UniqueIdStamp::class)) {
            $envelope = $envelope->with(new UniqueIdStamp());
        }

        /** @var UniqueIdStamp $stamp */
        $stamp = $envelope->last(UniqueIdStamp::class);

        $context = [
            'id' => $stamp->getUniqueId(),
            'class' => get_class($envelope->getMessage()),
        ];

        $envelope = $stack->next()->handle($envelope, $stack);

        if ($envelope->last(SentStamp::class)) {
            $this->logger->info('[{id}] {class} Message sent to queue', $context);
        }

        return $envelope;
    }
}
