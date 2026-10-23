<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class NormalizeRequestListener implements EventSubscriberInterface
{
    /**
     * @throws \JsonException
     */
    public function onRequestEvent(RequestEvent $event): void
    {
        $requestType = $event->getRequest()->getContentTypeFormat();
        $jsonContent = $event->getRequest()->getContent();
        if ($requestType === 'json' && $jsonContent) {
            /** @var array<string, string> $arrayContent */
            $arrayContent = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
            $event->getRequest()->request->replace($arrayContent);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}
