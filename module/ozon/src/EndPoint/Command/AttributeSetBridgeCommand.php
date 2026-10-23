<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Domain\Entity\AttributeBridge;
use Ozon\Domain\Repository\AttributeBridgeInterface;
use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\Internal\PimModuleInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Shared\Domain\ValueObject\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:attribute:set-bridge',
    description: 'Создать связи атрибутов pim => ozon',
)] final class AttributeSetBridgeCommand extends Command
{
    public function __construct(
        private PimModuleInterface       $pimModuleRepository,
        private AttributeInterface       $attributeRepository,
        private AttributeBridgeInterface $attributeBridgeRepository,
        private PersistenceInterface     $persistenceRepository,
        string                           $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $aliasBridge = [
            "packageWidth" => 'width',
            "packageLength" => 'depth',
            "packageHeight" => 'height',
            "packageWeight" => 'weight',
            "barcode" => "barcode",
            "volume" => 8163,
            "vendorCode" => 9024,
        ];

        $pimAttributes = $this->pimModuleRepository->findAttributeByAlias(array_keys($aliasBridge));

        foreach ($pimAttributes as $alias => $pimAttributeId) {

            $attribute = null;

            if (is_int($aliasBridge[$alias])) {
                $attribute = $this->attributeRepository->findOneByCriteria(
                    ["attributeExternalId" => $aliasBridge[$alias]]
                );
            }
            if (is_string($aliasBridge[$alias])) {
                $attribute = $this->attributeRepository->findOneByCriteria(
                    ["alias" => $aliasBridge[$alias]]
                );
            }

            if (is_null($attribute)) {
                continue;
            }

            $bridgeAttribute = $this->attributeBridgeRepository->findOneByCriteria([
                "attributeId" => $attribute->getAttributeId(),
                "attributePimId" => $pimAttributeId
            ]);

            if (is_null($bridgeAttribute)) {
                $bridgeAttribute = new AttributeBridge(
                    attributeUuid: $attribute->getAttributeUuid(),
                    attributePimUuid: Uuid::fromString($pimAttributeId)
                );
                $this->persistenceRepository->save($bridgeAttribute);
            }
        }

        return Command::SUCCESS;
    }

}
