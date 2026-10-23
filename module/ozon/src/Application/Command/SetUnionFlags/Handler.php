<?php

declare(strict_types=1);

namespace Ozon\Application\Command\SetUnionFlags;

use Ozon\Domain\Entity\Attribute;
use Ozon\Domain\Repository\AttributeInterface;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Service\EntityStoreService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private AttributeInterface $attributeRepository,
        private EntityStoreService $entityStoreService
    ) {
    }

    public function __invoke(Command $command): void
    {
        $needBeSame = ["Бренд", "Название модели (для объединения в одну карточку)"];
        $this->setFlag($needBeSame, 'same');

        $needBeDifferent = ["Вес, г", "Объем, мл", "Единиц в одном товаре", "Название аромата"];
        $this->setFlag($needBeDifferent, 'diff');
    }

    /**
     * @param string[] $attributesNames
     * @param string $flag
     * @return void
     */
    private function setFlag(array $attributesNames, string $flag): void
    {
        foreach ($attributesNames as $name) {
            $attribute = $this->attributeRepository->findOneByCriteria([
                "name" => $name
            ]);
            if ($attribute instanceof Attribute) {
                $attribute->setUnionFlag($flag);
                $this->entityStoreService->commit($attribute);
            }
        }
    }

}
