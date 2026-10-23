<?php

declare(strict_types=1);

namespace Ozon\EndPoint\Command;

use Ozon\Domain\Repository\AttributeInterface;
use Ozon\Domain\Repository\ProductAttributeInterface;
use Ozon\Domain\Repository\ProductInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ozon:product:set-union',
    description: '',
)] final class ProductSetUnionCommand extends Command
{
    public function __construct(
        private ProductInterface          $productRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private AttributeInterface        $attributeRepository,
        private PersistenceInterface      $persistenceRepository,
        string                            $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $attribute = $this->attributeRepository->findOneByCriteria(["attributeExternalId" => 9048]);
        if (is_null($attribute)) {
            return Command::FAILURE;
        }

        $products = $this->productRepository->findByCriteria([]);

        $union = [];
        foreach ($products as $product) {
            $unionAttribute = $this->productAttributeRepository->findOneByCriteria([
                "productId" => $product->getProductUuid(),
                "attributeId" => $attribute->getAttributeUuid()
            ]);
            if (is_null($unionAttribute)) {
                continue;
            }
            $value = $unionAttribute->getValue();
            if ($value && is_string($value)) {
                $union[$value][] = $product->getProductUuid();
            }
        }

        foreach ($union as $products) {
            if (count($products) > 1) {
                foreach ($products as $product) {
                    $product = $this->productRepository->findOneByCriteria(["productId" => $product]);
                    if (is_null($product)) {
                        continue;
                    }
                    $product->setUnification($products);
                    $this->persistenceRepository->save($product);
                }
            }
        }

        return Command::SUCCESS;
    }
}
