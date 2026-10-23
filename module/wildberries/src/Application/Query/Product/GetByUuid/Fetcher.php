<?php

declare(strict_types=1);

namespace Wildberries\Application\Query\Product\GetByUuid;

use Shared\Domain\Query\QueryHandlerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Wildberries\Application\Query\Product\ProductAttributeDto;
use Wildberries\Application\Query\Product\ProductFullDto;
use Wildberries\Domain\Repository\AttributeInterface;
use Wildberries\Domain\Repository\MediaInterface;
use Wildberries\Domain\Repository\ProductAttributeInterface;
use Wildberries\Domain\Repository\ProductInterface;
use Wildberries\Domain\Repository\SizeInterface;
use Wildberries\Infrastructure\Service\ProductService;

final readonly class Fetcher implements QueryHandlerInterface
{
    public function __construct(
        private ProductService            $productService,
        private ProductInterface          $productRepository,
        private SizeInterface             $sizeRepository,
        private MediaInterface            $mediaRepository,
        private AttributeInterface        $attributeRepository,
        private ProductAttributeInterface $productAttributeRepository,
        private SerializerInterface $serializer
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(Query $query): ?ProductFullDto
    {
        if (!$product = $this->productRepository->findOneBy(['productId' => $query->productId])) {
            return $this->productService->getProductTemplate($query->productId);
        }

        $unions = [];
        $attributes = [];
        $productAttributes = $this->productAttributeRepository->findBy(['productId' => $product->getProductId()]);
        $unionAttribute = $this->attributeRepository->findOneBy(['alias' => 'wbUnion']);

        foreach ($productAttributes as $productAttribute) {
            if ($productAttribute->getAttributeId() == $unionAttribute->getAttributeId()) {
                $unions = $productAttribute->getValue();
            } else {
                $attributeId = $productAttribute->getAttributeId();
                $attribute = $this->attributeRepository->findOneBy(['attributeId' => $attributeId]);
                if (!$attribute) {
                    continue;
                }
                $attributes[] = new ProductAttributeDto(
                    attributeId: $attributeId,
                    value: match ($attribute->getType()) {
                        'integer', 'string' => $productAttribute->getValue()[0],
                        default => $productAttribute->getValue()
                    }
                );
            }
        }

        $productArray = $this->serializer->normalize($product);
        $sizeArray = $this->serializer->normalize(
            $this->sizeRepository->findBy(['productId' => $product->getProductId()]));
        $medias = $this->mediaRepository->findBy(['productId' => $product->getProductId()]);

        $notCharAttributes = $this->attributeRepository->findBy(['source' => ['product', 'size', 'dimension']]);
        foreach ($notCharAttributes as $notCharAttribute) {
            switch ($notCharAttribute->getSource()) {
                case 'product':
                    $attributes[] = new ProductAttributeDto(
                        attributeId: $notCharAttribute->getAttributeId(),
                        value: $productArray[$notCharAttribute->getAlias()]
                    );
                    break;
                case 'size':
                    foreach ($sizeArray as $size) {
                        $attributes[] = new ProductAttributeDto(
                            attributeId: $notCharAttribute->getAttributeId(),
                            value: $size[$notCharAttribute->getAlias()]
                        );
                    }
                    break;
                case 'dimension':
                    $attributes[] = new ProductAttributeDto(
                        attributeId: $notCharAttribute->getAttributeId(),
                        value: $productArray['dimension'][$notCharAttribute->getAlias()]
                    );
                    break;
            }
        }

        return ProductFullDto::getDto($product, $attributes, $medias, $unions);
    }
}
