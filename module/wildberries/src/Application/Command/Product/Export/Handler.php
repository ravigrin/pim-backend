<?php

namespace Wildberries\Application\Command\Product\Export;

use Exception;
use Shared\Domain\Command\CommandHandlerInterface;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Repository\Dto\Export\WbCreateProductDto;
use Wildberries\Domain\Repository\Dto\Export\WbUpdateProductDto;
use Wildberries\Domain\Repository\Dwh\WbExportRepositoryInterface;
use Wildberries\Domain\Repository\ProductInterface;
use Wildberries\Infrastructure\Exception\ProductNotFound;
use Wildberries\Infrastructure\Service\ProductService;

final readonly class Handler implements CommandHandlerInterface
{
    public function __construct(
        private ProductInterface            $productRepository,
        private WbExportRepositoryInterface $wbExportRepository,
        private ProductService              $productService
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        $seller = match ($_ENV['WB_ENV']) {
            "prod" => "Marketplace Seller",
            default => "Marketplace Seller"
        };

        if (isset($command->productId)) {
            $product = $this->productRepository->findOneBy(['productId' => $command->productId]);
            if (!$product instanceof Product) {
                return;
                //throw new ProductNotFound("Product with id: $command->productId - NOT FOUND
                //- module/wildberries/src/Application/Command/Product/Export/Handler.php::invoke()");
            }
            // Если уже отправлен - не отправляем
            if ($product->getExportStatus() == 2) {
                //throw new Exception("Product with id: $command->productId - Has export status 0 - NOT EXPORT");
                return;
            }
            /** @var Product[] $products */
            $products[] = $product;
        } else {
            $products = $this->productRepository->findToExport($seller);
        }


        $createProducts = [];
        $updateProducts = [];

        foreach ($products as $product) {

            $productDto = $this->productService->build($product);


            // Разносим товары на создание и обновление
            if ($productDto instanceof WbCreateProductDto) {
                $createProducts[] = $productDto;
            }
            if ($productDto instanceof WbUpdateProductDto) {
                $updateProducts[] = $productDto;
            }
        }

        // Отправляем массив товаров на обновление
        if (!empty($updateProducts)) {
            $this->wbExportRepository->update(
                $updateProducts,
                $seller
            );
        }
        // Отправляем массив товаров на создание
        if (!empty($createProducts)) {
            $this->wbExportRepository->create(
                $createProducts,
                $seller
            );
        }

        // Отдельная доработка (см. TODO.md): синхронизация mediaFiles через
        // content/v2/media/save с проверкой изменений, вместо перезапуска карточки.

        // Объединение карточек (union) сейчас отключено: перед включением нужна
        // проверка корректности группировки (см. TODO.md).
        // Ретраи при HTTP 500 со стороны WB API — см. TODO.md (план развития).
    }

}
