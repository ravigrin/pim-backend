<?php

namespace Wildberries\Application\Command\Product\Import;

use Exception;
use JsonException;
use Pim\Application\Query\Product\GetUuidByVendorCode\Query as GetUuidByVendorCodeQuery;
use Pim\Domain\Entity\User;
use Pim\Domain\Repository\Pim\UserInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Domain\Query\QueryBusInterface;
use Shared\Domain\Repository\PersistenceInterface;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Entity\Media;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Entity\Size;
use Wildberries\Domain\Repository\AttributeInterface;
use Wildberries\Domain\Repository\CatalogInterface;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductCharacteristicsDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductMediaDto;
use Wildberries\Domain\Repository\Dwh\Dto\WbProductSizesDto;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;
use Wildberries\Domain\Repository\MediaInterface;
use Wildberries\Domain\Repository\ProductInterface;
use Wildberries\Domain\Repository\SizeInterface;
use Wildberries\Infrastructure\Service\ProductAttributeService;

final class Handler implements CommandHandlerInterface
{
    private User $user;

    /**
     * @throws Exception
     */
    public function __construct(
        private readonly QueryBusInterface           $queryBus,
        private readonly LoggerInterface             $logger,
        private readonly PersistenceInterface        $persistenceRepository,
        private readonly UserInterface               $userRepository,
        private readonly WbImportRepositoryInterface $wbRepository,
        private readonly CatalogInterface            $catalogRepository,
        private readonly ProductInterface            $productRepository,
        private readonly AttributeInterface          $attributeRepository,
        private readonly ProductAttributeService     $productAttributeService,
        private readonly SizeInterface               $sizeRepository,
        private readonly MediaInterface              $mediaRepository
    )
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        $user = $this->userRepository->findByUsername('system');
        if (!$user instanceof User) {
            throw new Exception('FAIL: user not found');
        }
        $this->user = $user;

        // Статус - отправлено (Маркетплейс).
        // Доработка (см. TODO.md): выводить статус из видимости товара на сайте.
        $status = 2;

        // Получаем все товары WB с DWH
        foreach ($this->wbRepository->getAllProducts() as $product) {

            if (!$productId = (string)$this->queryBus->dispatch(
                new GetUuidByVendorCodeQuery($product->attributes->vendorCode)
            )
            ) {
                continue;
            }

            if (!$catalog = $this->catalogRepository->findOneBy(["objectId" => $product->attributes->subjectId])) {
                $this->logger->warning("Not FOUND catalog by objectId: {$product->attributes->subjectId}
                - src/Wildberries/Application/Command/ImportProducts/Handler.php::__invoke()");
                continue;
            }

            $localProduct = $this->productRepository->findOneBy(['vendorCode' => $product->attributes->vendorCode]);

            if (!$localProduct) {
                $localProduct = new Product(
                    productId: $productId,
                    exportStatus: $status,
                    catalogId: $catalog->getCatalogId(),
                    imtId: $product->attributes->imtId,
                    nmId: $product->attributes->nmId,
                    vendorCode: $product->attributes->vendorCode,
                    brand: $product->attributes->brand,
                    title: $product->attributes->title,
                    description: $product->attributes->description,
                    sellerName: $product->attributes->sellerName,
                    nmUuid: $product->attributes->nmUuid,
                    dimensions: (array)$product->dimensions,
                    tags: $product->attributes->tags
                );
            } else {
                $localProduct->setExportStatus($status);
                $localProduct->setCatalogId($catalog->getCatalogId());
                $localProduct->setImtId($product->attributes->imtId);
                $localProduct->setNmId($product->attributes->nmId);
                $localProduct->setVendorCode($product->attributes->vendorCode);
                $localProduct->setBrand($product->attributes->brand);
                $localProduct->setTitle($product->attributes->title);
                $localProduct->setDescription($product->attributes->description);
                $localProduct->setSellerName($product->attributes->sellerName);
                $localProduct->setNmUuid($product->attributes->nmUuid);
                $localProduct->setDimensions((array)$product->dimensions);
                $localProduct->setTags($product->attributes->tags);
            }

            $this->persistenceRepository->persist($localProduct);

            $this->saveCharacteristics($localProduct, $product->characteristics);
            $this->saveSizes($localProduct, $product->sizes);
            $this->saveMedia($localProduct, $product->media);
        }

        $this->persistenceRepository->flush();

        $this->fillUnion();
    }


    /**
     * Сохраняем характеристики товара
     * @param Product $product
     * @param WbProductCharacteristicsDto[] $characteristics
     * @return void
     * @throws JsonException
     */
    private function saveCharacteristics(Product $product, array $characteristics): void
    {
        foreach ($characteristics as $characteristic) {
            $attribute = $this->attributeRepository->findOneBy(['name' => $characteristic->name]);

            if (!$attribute instanceof Attribute) {
                $this->logger->warning('Not found attribute by name: ' . $characteristic->name);
                continue;
            }

            $value = is_scalar($characteristic->value)
                ? $characteristic->value
                : json_encode($characteristic->value, JSON_THROW_ON_ERROR);

            $this->productAttributeService->handling(
                $this->user,
                $product,
                $attribute,
                $value,
                $characteristic->id
            );
        }
    }

    /**
     * Сохраняем размеры товара
     * @param Product $product
     * @param WbProductSizesDto[] $sizes
     * @return void
     */
    private function saveSizes(Product $product, array $sizes): void
    {
        foreach ($sizes as $size) {
            if (!$localSize = $this->sizeRepository->findOneBy(['chrtId' => $size->chrtId])) {
                $localSize = new Size(
                    sizeId: Uuid::uuid7()->toString(),
                    productId: $product->getProductId(),
                    chrtId: $size->chrtId,
                    techSize: $size->techSize,
                    wbSize: $size->wbSize,
                    skus: $size->skus
                );
            }
            $localSize->setChrtId($size->chrtId);
            $localSize->setWbSize($size->wbSize);
            $localSize->setTechSize($size->techSize);
            $localSize->setSkus($size->skus);

            $this->persistenceRepository->persist($localSize);
        }
    }

    /**
     * Сохраняем медиа файлы товара
     * @param Product $product
     * @param WbProductMediaDto[] $medias
     * @return void
     */
    private function saveMedia(Product $product, array $medias): void
    {
        foreach ($medias as $id => $media) {

            // Доработка (см. TODO.md): собирать хэш медиа по содержимому картинок, а не по именам.
            $hash = md5($media->little . $media->small . $media->big . $media->video);

            if (!$localMedia = $this->mediaRepository->findOneBy(['hash' => $hash])) {
                $localMedia = new Media(
                    mediaId: Uuid::uuid7()->toString(),
                    number: ++$id,
                    productId: $product->getProductId(),
                    little: $media->little,
                    small: $media->small,
                    big: $media->big,
                    video: $media->video,
                    hash: $hash
                );
            } else {
                $localMedia->setNumber(++$id);
                $localMedia->setLittle($media->little);
                $localMedia->setSmall($media->small);
                $localMedia->setBig($media->big);
                $localMedia->setVideo($media->video);
                $localMedia->setHash($hash);
            }

            $this->persistenceRepository->persist($localMedia);
        }
    }

    /**
     * Заполняет атрибут объединенных товаров
     * @throws Exception
     */
    private function fillUnion(): void
    {
        $productCards = $this->productRepository->findUnion();

        foreach ($productCards as $productIds) {
            $this->productAttributeService->fillUnion($this->user, $productIds);
        }
    }


}
