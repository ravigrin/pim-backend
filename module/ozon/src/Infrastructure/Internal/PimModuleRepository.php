<?php

namespace Ozon\Infrastructure\Internal;

use Ozon\Domain\Repository\Internal\PimModuleInterface;
use Pim\Application\Command\ProductSetMarketplace\Command as ProductSetMarketplaceCommand;
use Pim\Application\Query\Attribute\GetByAliases\Query as GetByAliasesQuery;
use Pim\Application\Query\Attribute\GetByProductId\Query as GetByProductIdQuery;
use Pim\Application\Query\Brand\GetNameByProduct\Query as GetNameByProductQuery;
use Pim\Application\Query\Brand\GetNames\Query as BrandGetNamesQuery;
use Pim\Application\Query\Product\GetUuidByVendorCode\Query as GetUuidByVendorCodeQuery;
use Pim\Application\Query\Product\GetVendorCodeByUuid\Query as GetVendorCodeByUuidQuery;
use Shared\Domain\Command\CommandBusInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\ValueObject\Uuid;

readonly class PimModuleRepository implements PimModuleInterface
{
    public function __construct(
        private QueryBusInterface   $queryBus,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function setPimStatusMarketplace(Uuid $productId): void
    {
        $this->commandBus->dispatch(
            new ProductSetMarketplaceCommand(
                productId: $productId->getValue()
            )
        );
    }

    /**
     * @param string $productId
     * @return array<string, string[]|string|null>
     */
    public function findAttributeByProduct(string $productId): array
    {
        return $this->queryBus->dispatch(new GetByProductIdQuery($productId));
    }

    /**
     * @param string[] $aliases
     * @return array<string, string>
     */
    public function findAttributeByAlias(array $aliases): array
    {
        return $this->queryBus->dispatch(new GetByAliasesQuery(aliases: $aliases));
    }

    public function findBrandValues(): array
    {
        return $this->queryBus->dispatch(new BrandGetNamesQuery());
    }

    public function findBrandValueByProduct(string $productId): ?string
    {
        return $this->queryBus->dispatch(new GetNameByProductQuery(
            productId: $productId
        ));
    }

    public function findUuidByVendorCode(string $vendorCode): ?string
    {
        return $this->queryBus->dispatch(new GetUuidByVendorCodeQuery($vendorCode));
    }

    public function findVendorCodeByUuid(string $uuid): ?string
    {
        return $this->queryBus->dispatch(new GetVendorCodeByUuidQuery($uuid));
    }
}
