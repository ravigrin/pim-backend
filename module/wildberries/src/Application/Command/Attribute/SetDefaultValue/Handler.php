<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Attribute\SetDefaultValue;

use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Infrastructure\PersistenceRepository;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Repository\AttributeInterface;

final class Handler implements CommandHandlerInterface
{
    /**
     * @var array{
     *     "НДС": string,
     *     "Продавец": string
     * }
     */
    private array $defaultValues = [
        "Ставка НДС" => "20",
        "Продавец" => "Marketplace Seller",
        "Страна производства" => "Россия",
        "Прямые поставки от производителя" => "Да"
    ];


    public function __construct(
        private readonly AttributeInterface    $attributeRepository,
        private readonly PersistenceRepository $persistenceRepository
    ) {
    }

    public function __invoke(Command $command): void
    {
        $this->defaultValues['Продавец'] = match ($_ENV['WB_ENV']) {
            "test", "dev", "preprod" => "Marketplace Seller",
            "prod" => "Marketplace Seller"
        };

        $this->setDefault();
    }


    /**
     * @return void
     */
    private function setDefault(): void
    {
        foreach ($this->defaultValues as $name => $value) {
            $attribute = $this->attributeRepository->findOneBy(["name" => $name]);
            if ($attribute instanceof Attribute) {
                $attribute->setDefaultValue([$value]);
                $this->persistenceRepository->persist($attribute);
            }
        }
        $this->persistenceRepository->flush();
    }

}
