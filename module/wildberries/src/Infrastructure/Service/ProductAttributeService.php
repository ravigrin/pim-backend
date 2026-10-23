<?php

namespace Wildberries\Infrastructure\Service;

use Shared\Domain\Service\EntityStoreService;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Entity\Product;
use Wildberries\Domain\Entity\ProductAttribute;
use Wildberries\Domain\Entity\ProductAttributeHistory;
use Pim\Domain\Entity\User;
use Wildberries\Domain\Repository\AttributeInterface;
use Wildberries\Domain\Repository\ProductAttributeInterface;
use Wildberries\Domain\Repository\ProductInterface;
use Exception;
use JsonException;
use Ramsey\Uuid\Uuid as UuidBuild;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Сервис для работы с атрибутоми и характеристиками товаров Wildberries
 */
readonly class ProductAttributeService
{
    /**
     * @var string[] $imageAllowFormats
     */
    private array $imageAllowFormats;

    /**
     * @var string[] $videoAllowFormats
     */
    private array $videoAllowFormats;

    /**
     * @throws Exception
     */
    public function __construct(
        private EntityStoreService        $entityStoreService,
        private AttributeInterface        $attributeRepository,
        private ProductInterface          $productRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private ParameterBagInterface     $parameterBag
    ) {
        if (!$this->imageAllowFormats = $this->parameterBag->get('wildberries')['image']['allow']) {
            throw new \Exception(
                '- Wildberries/Infrastructure/Service/ProductAttributeService.php::construct() 
                - FAIL LOAD image allow formats'
            );
        }

        if (!$this->videoAllowFormats = $this->parameterBag->get('wildberries')['video']['allow']) {
            throw new \Exception(
                '- Wildberries/Infrastructure/Service/ProductAttributeService.php::construct() 
                - FAIL LOAD video allow formats'
            );
        }
    }

    /**
     * Проверяет установлен ли переданный атрибут у переданного товара,
     * если нет - устанавливает, если есть, обновляет и сохраняет историю
     * @param User $user Пользователь, внесший изменения
     * @param Product $product Товар
     * @param Attribute $attribute Атрибут
     * @param string|int[]|string[] $value Значение атрибута
     * @param int|null $wbAttributeId Идентификатор атрибута Wb
     * @return ProductAttribute
     * @throws JsonException
     */
    public function handling(User $user, Product $product, Attribute $attribute,
                             int|string|array $value, ?int $wbAttributeId = null): ProductAttribute
    {
        $productAttribute = $this->productAttributeRepository->findOneBy(
            [
                'productId' => $product->getProductId(),
                'attributeId' => $attribute->getAttributeId()
            ]
        );

        if (is_scalar($value)) {
            $value = $this->prepareValue($value);
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        $hash = md5(serialize($value));

        if ($attribute->getAlias() == 'mediaFiles') {
            $hash = json_encode($this->addChecksumKeyToImg($value), JSON_THROW_ON_ERROR);
        }

        if (!$productAttribute instanceof ProductAttribute) {
            $productAttribute = new ProductAttribute(
                productAttributeId: UuidBuild::uuid7()->toString(),
                productId: $product->getProductId(),
                attributeId: $attribute->getAttributeId(),
                value: $value,
                hash: $hash,
                wbAttributeId: $wbAttributeId
            );
            $this->setHistory($user, $productAttribute, $value);
        } else {
            $productAttribute->setValue($value);
            $productAttribute->setHash($hash);
            if ($hash != $productAttribute->getHash()) {
                $this->setHistory($user, $productAttribute, $value, $productAttribute->getValue());
            }
        }

        $this->entityStoreService->commit($productAttribute);
        return $productAttribute;
    }

    /**
     * Проверяем, является ли строка json-ом, если да - преобразуем (чтобы при сохранении не получить двойное вложение),
     *  нет - возвращаем как есть
     * @param string $string
     * @return mixed
     */
    private function prepareValue(string $string): mixed
    {
        if (!$json = json_decode($string)) {
            return $string;
        }
        return $json;
    }

    /**
     * Сохраняет историю измений атрибутов товара
     * @param User $user
     * @param ProductAttribute $productAttribute
     * @param array $newValue
     * @param array|null $oldValue
     * @return void
     */
    private function setHistory(User $user, ProductAttribute $productAttribute, array $newValue, null|array $oldValue = null): void
    {
        $history = new ProductAttributeHistory(
            productAttributeHistoryId: UuidBuild::uuid7()->toString(),
            userId: $user->getUserId(),
            productAttributeId: $productAttribute->getProductAttributeId(),
            newValue: $newValue,
            oldValue: $oldValue
        );

        $this->entityStoreService->commit($history);
    }

    /**
     * Разбирает массив медиа файлов на два: images и video
     * Ключами массива images устанавливает checksum изображения
     * @param string[] $mediaFiles
     */
    public function addChecksumKeyToImg(array $mediaFiles): array
    {
        $response = [];
        foreach ($mediaFiles as $fileUrl) {
            if (in_array(pathinfo($fileUrl)['extension'], $this->videoAllowFormats, true)) {
                $response['video'] = $fileUrl;
                continue;
            }

            if (in_array(pathinfo($fileUrl)['extension'], $this->imageAllowFormats, true)) {
                $imageContent = file_get_contents($fileUrl);
                if ($imageContent) {
                    $crc32 = crc32($imageContent);
                    $response['images'][$crc32] = $fileUrl;
                }
            }
        }

        return $response;
    }

    /**
     * Заполняет атрибут объединенных товаров
     * @param User $user
     * @param string[] $productsId
     * @return void
     * @throws Exception
     */
    public function fillUnion(User $user, array $productsId): void
    {
        if (!$this->canUnion($productsId)) {
            throw new Exception(
                message: 'Fail union, category is not same and cant be union 
                - Wildberries/Infrastructure/Service/ProductAttributeService.php::fillUnion()'
            );
        }

        // Проверяем наличие атрибута Объединененные карточки
        $attribute = $this->attributeRepository->findOneBy(['alias' => 'wbUnion']);
        if (!$attribute instanceof Attribute) {
            throw new Exception(
                message: 'Not found wbUnion attribute 
                - Wildberries/Infrastructure/Service/ProductAttributeService.php::fillUnion()',
                code: 500
            );
        }

        foreach ($productsId as $key => $productId) {
            $product = $this->productRepository->findOneBy(['productId' => $productId]);
            if (!$product instanceof Product) {
                throw new Exception(sprintf('Product not found with id: %d - 
                - Wildberries/Infrastructure/Service/ProductAttributeService.php::fillUnion()', $productId));
            }
            $unionProducts = $productsId;
            unset($unionProducts[$key]);
            $this->handling($user, $product, $attribute, array_values($unionProducts));
        }

    }

    /**
     * Проверяет, могут ли быть объединены в одну карточку переданные товары
     * (В одной ли категории находятся)
     * @param string[] $unionIds Идентификаторы товаров, которые нужно объединиять
     * @return bool
     */
    private function canUnion(array $unionIds): bool
    {
        $categories = $this->productRepository->getProductsCategories($unionIds);
        if (count(array_unique($categories)) > 1) {
            return false;
        }
        return true;
    }

    /**
     * Заполлняет для товаров атрибуты publicProductLink и privateProductLink
     * @param User $user
     * @return void
     * @throws JsonException
     */
    public function setLinks(User $user): void
    {
        $publicProductLink = $this->attributeRepository->findOneBy(['alias' => 'publicProductLink']);
        $privateProductLink = $this->attributeRepository->findOneBy(['alias' => 'privateProductLink']);

        foreach ($this->productRepository->findBy([]) as $product) {
            if (!$product->getNmId()) {
                continue;
            }
            $publicUrl = "https://www.wildberries.ru/catalog/{$product->getNmId()}/detail.aspx?targetUrl=GP";
            $privateUrl = "https://seller.wildberries.ru/new-goods/card?nmID={$product->getNmId()}&type=EXIST_CARD";
            $this->handling($user, $product, $publicProductLink, $publicUrl);
            $this->handling($user, $product, $privateProductLink, $privateUrl);
        }
    }
}
