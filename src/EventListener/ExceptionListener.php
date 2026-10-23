<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\NotFoundException;
use App\Exception\SourceNotFound;
use App\Exception\UserNotFound;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class ExceptionListener implements EventSubscriberInterface
{
    /**
     * Маппинг доменных исключений на HTTP-статусы.
     */
    private const STATUS_MAP = [
        NotFoundException::class => 404,
        UserNotFound::class => 404,
        SourceNotFound::class => 404,
    ];

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly bool $debug = false,
    ) {
    }

    public function processException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $this->logger->error($throwable->getMessage(), [
            'exception' => $throwable::class,
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
        ]);

        $status = $this->resolveStatus($throwable);

        $payload = ['error' => $this->resolveMessage($throwable, $status)];

        if ($this->debug) {
            $payload['exception'] = $throwable::class;
        }

        $event->setResponse(new JsonResponse(data: $payload, status: $status));
    }

    private function resolveStatus(Throwable $throwable): int
    {
        // Уже HTTP-исключение (в т.ч. 401/403 из security) — сохраняем его статус.
        if ($throwable instanceof HttpExceptionInterface) {
            return $throwable->getStatusCode();
        }

        foreach (self::STATUS_MAP as $class => $status) {
            if ($throwable instanceof $class) {
                return $status;
            }
        }

        return 500;
    }

    /**
     * Наружу отдаём только заведомо безопасные сообщения;
     * внутренние детали видны лишь в debug-режиме.
     */
    private function resolveMessage(Throwable $throwable, int $status): string
    {
        if ($this->debug) {
            return $throwable->getMessage();
        }

        if ($throwable instanceof HttpExceptionInterface || $status < 500) {
            return $throwable->getMessage();
        }

        return 'Internal server error';
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'processException',
        ];
    }
}
