<?php

namespace Wildberries\Application\Command\Attribute\Module\Fill;

use Pim\Domain\Repository\Pim\UserInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Wildberries\Infrastructure\Service\ProductAttributeService;

/**
 * Обработчик комманды заполнения атрибутов модуля Wildberries
 */
final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private UserInterface           $userRepository,
        private ProductAttributeService $productAttributeService
    ) {
    }


    /**
     * @throws \JsonException
     */
    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByUsername('system');
        $this->productAttributeService->setLinks($user);
    }

}
