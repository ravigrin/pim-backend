<?php

namespace Wildberries\Application\Command\Attribute\Module\Init;

use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Infrastructure\PersistenceRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Wildberries\Domain\Entity\Attribute;
use Wildberries\Domain\Repository\AttributeInterface;
use Exception;
use Ramsey\Uuid\Uuid as UuidBuild;

final readonly class Handler implements CommandHandlerInterface
{
    /**
     * @var int $unionMax - Максимальное кол-во номенклатур которые можно объединить в одной карточке товара
     */
    private int $unionMax;

    private const array MODULE_ATTRIBUTE = [
        // -- Атрибуты карточки WB, не получаемые по апи атрибутов
        [
            'name' => 'Продавец',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'sellerName',
            'isReadOnly' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Артикул/Номенклатурный номер WB',
            'type' => 'integer',
            'charcType' => 4,
            'maxCount' => 1,
            'alias' => 'nmId',
            'isReadOnly' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Идентификатор карточки товара',
            'type' => 'integer',
            'charcType' => 4,
            'maxCount' => 1,
            'alias' => 'imtId',
            'isReadOnly' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Артикул продавца',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'vendorCode',
            'isReadOnly' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Наименование',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'title',
            'isRequired' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Описание',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'description',
            'isRequired' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Бренд',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'brand',
            'isRequired' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Медиафайлы номенклатуры',
            'type' => 'string[]',
            'charcType' => 1,
            'maxCount' => 30,
            'alias' => 'media',
            'isReadOnly' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Дата обновления',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'description' => 'Дата последнего обновления на маркетплейсе',
            'alias' => 'wbUpdatedAt',
            'isReadOnly' => true,
            'source' => 'product'
        ],
        [
            'name' => 'Массив тегов',
            'type' => 'string[]',
            'charcType' => 1,
            'maxCount' => 5,
            'alias' => 'tags',
            'source' => 'product'
        ],

        // -- Атрибуты габаритных размеров товара WB, не получаемые по апи атрибутов
        [
            'name' => 'Высота упаковки, см',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'height',
            'source' => 'dimension'
        ],
        [
            'name' => 'Ширина упаковки, см',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'width',
            'source' => 'dimension'
        ],
        [
            'name' => 'Длина упаковки, см',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'length',
            'source' => 'dimension'
        ],

        // -- Атрибуты размеров карточки WB, не получаемые по апи атрибутов
        [
            'name' => 'Российский размер товара',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'wbSize',
            'isReadOnly' => true,
            'source' => 'size'
        ],
        [
            'name' => 'Размер товара',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'alias' => 'techSize',
            'isReadOnly' => true,
            'source' => 'size'
        ],
        [
            'name' => 'Числовой идентификатор размера для данного артикула WB',
            'type' => 'integer',
            'charcType' => 4,
            'maxCount' => 1,
            'alias' => 'chrtId',
            'isReadOnly' => true,
            'source' => 'size'
        ],
        [
            'name' => 'Штрихкоды номенклатуры',
            'type' => 'string[]',
            'charcType' => 4,
            'maxCount' => 1,
            'alias' => 'skus',
            'isReadOnly' => true,
            'source' => 'size'
        ],

        // -- Дополнительно введенные атрибуты
        [
            'name' => 'Объединененные карточки Wildberries',
            'type' => 'string[]',
            'charcType' => 1,
            'maxCount' => 30,
            'description' => 'Массив идентификаторов номенклатур, объединенных в одной карточке товара',
            'alias' => 'wbUnion',
            'isReadOnly' => true,
            'source' => 'module'
        ],
        [
            'name' => 'РРЦ',
            'type' => 'integer',
            'charcType' => 4,
            'maxCount' => 1,
            'description' => 'Рекомендованная розничная цена (из PIM)',
            'alias' => 'price',
            'isRequired' => true,
            'source' => 'module'
        ],
        [
            'name' => 'Ссылка на публичную страницу товара',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'description' => 'Ссылка на публичную страницу товара',
            'alias' => 'publicProductLink',
            'isReadOnly' => true,
            'source' => 'module'
        ],
        [
            'name' => 'Ссылка на товар в личном кабинете',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'description' => 'Ссылка на товар в личном кабинете',
            'alias' => 'privateProductLink',
            'isReadOnly' => true,
            'source' => 'module'
        ],
        [
            'name' => 'Себестоимость PIM',
            'type' => 'integer',
            'charcType' => 4,
            'maxCount' => 1,
            'description' => 'Себестоимость продукта с наценками МП из DWH',
            'alias' => 'netCost',
            'isReadOnly' => true,
            'source' => 'module'
        ],
        [
            'name' => 'Статус',
            'type' => 'string',
            'charcType' => 1,
            'maxCount' => 1,
            'description' => 'Статус отправки товара на маркетплейс Wildberries',
            'alias' => 'exportStatus',
            'isReadOnly' => true,
            'source' => 'module'
        ]
    ];

    /**
     * @throws Exception
     */
    public function __construct(
        private AttributeInterface    $attributeRepository,
        private PersistenceRepository $persistenceRepository,
        private ParameterBagInterface $parameterBag
    )
    {
        if (!$this->unionMax = $this->parameterBag->get('wildberries')['union.max']) {
            throw new Exception(
                'App/Wildberries/Application/Command/ImportAttributes/Handler.php::construct() - FAIL GET '
            );
        }
    }

    /**
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        foreach (self::MODULE_ATTRIBUTE as $data) {

            if ($attribute = $this->attributeRepository->findOneBy(['alias' => $data['alias']])) {
                $attribute->setSource($data['source']);
                $attribute->setCharcType($data['charcType']);
            } else {
                $attribute = new Attribute(
                    attributeId: UuidBuild::uuid7()->toString(),
                    name: $data['name'],
                    type: $data['type'],
                    charcType: $data['charcType'],
                    maxCount: ($data['alias'] == 'wbUnion') ? $this->unionMax : $data['maxCount'],
                    source: $data['source'],
                    description: $data['description'],
                    alias: $data['alias'],
                    isRequired: $data['isRequired'] ?? false,
                    isPopular: $data['isPopular'] ?? false,
                    isDictionary: $data['isDictionary'] ?? false,
                    isReadOnly: $data['isReadOnly'] ?? false,
                );
            }

            $this->persistenceRepository->persist($attribute);
        }
        $this->persistenceRepository->flush();
    }

}
