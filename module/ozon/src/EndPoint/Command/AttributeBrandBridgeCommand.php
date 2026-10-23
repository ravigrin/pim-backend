<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Domain\Repository\Internal\PimModuleInterface;
use Ozon\Domain\Repository\ProductAttributeInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:attribute:brand-bridge',
    description: 'Создать связи значения атрибута бренда pim => ozon',
)] final class AttributeBrandBridgeCommand extends Command
{
    public function __construct(
        private PimModuleInterface        $pimModuleRepository,
        private ProductAttributeInterface $productAttributeRepository,
        string                            $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pimBrands = $this->pimModuleRepository->findBrandValues();

        $brandProductValues = $this->productAttributeRepository->findByExternalId(externalId: 85);

        $ozonBrandValues = [];
        foreach ($brandProductValues as $brandProductValue) {
            $prepareValue = $brandProductValue->getPrepareValue();
            $value = array_shift($prepareValue['values']);

            $ozonBrandValues[$value['dictionary_value_id']] = $value['value'];
        }

        $bridge = [];
        foreach ($ozonBrandValues as $ozonBrandId => $ozonBrandValue) {
            foreach ($pimBrands as $pimBrandId => $pimBrand) {
                if ($ozonBrandValue === $pimBrand) {
                    $bridge[] = [
                        'ozonId' => $ozonBrandId,
                        'pimId' => $pimBrandId,
                        'value' => $pimBrand
                    ];
                }
            }
        }

        print_r([$ozonBrandValues, $pimBrands, $bridge]);

        return Command::SUCCESS;
    }

}
