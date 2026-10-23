<?php

declare(strict_types=1);

namespace Wildberries\Application\Command\Suggest\Import;

use Shared\Domain\Command\CommandHandlerInterface;
use Shared\Infrastructure\PersistenceRepository;
use Wildberries\Domain\Entity\Suggest;
use Wildberries\Domain\Repository\AttributeInterface;
use Wildberries\Domain\Repository\CatalogInterface;
use Wildberries\Domain\Repository\SuggestInterface;
use Wildberries\Domain\Repository\Dwh\WbImportRepositoryInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

final readonly class Handler implements CommandHandlerInterface
{
    /**
     * Nekrasov cab
     * @var string
     */
    private const string WB_COOKIE = "external-locale=ru; x-supplier-id-external=76f0cdf0-931e-4ff9-b1da-d9a07487e861; _wbauid=7756447461697634143; BasketUID=159ae817290940539bdd645fcb5c254a; ___wbu=3734f287-024d-426b-a87b-f25782b96caa.1697634143; cfidsw-wb=X9fl5UbbQ/iEbCO1hmZTCUhO5dIx/618g1iUoGFKtBY11wo5YHSy1BjJ2TgXMj2waB9RbYvtH/lw65GabvUOX0q8gKw3b3q/caDfWS1wfEYKbijkgDKRfYC7So/w+Rj8VoYQlM30LSSq1V8vqEBvjsui3S03RTpP9+rBm8Qb; __zzatw-wb=MDA0dC0cTHtmcDhhDHEWTT17CT4VHThHKHIzd2UvO2sgYUpdJDVRP0FaW1Q4NmdBEXUmCQg3LGBwVxlRExpceEdXeiwZEwhzLFd/D11GRmllbQwtUlFRS19/Dg4/aU5ZQ11wS3E6EmBWGB5CWgtMeFtLKRZHGzJhXkZpdRVgR0kURlM/Im8/N0ZzZRJDCnMQHkohJBkYI3N8KCV5fF9vG3siXyoIJGM1Xz9EaVhTMCpYQXt1J3Z+KmUzPGwdYVBhKEtVUXsuHg1pN2wXPHVlLwkxLGJ5MVIvE0tsGA==ezyMtw==; WBToken=AtnktiiuzanaDK6hvdsMTvoZYSs4Ucw4x4PLc3f7s6zO167a7LaNa6_SKOoeFwQimbe03Z1pdvdUn3ax1kXQ2HpVRtrCJFlRRUzlzWAccKYKqIRD7ApAKuaico3Sug";

    /**
     * @var string
     */
    private const string USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko)'
    . ' Chrome/117.0.0.0 Safari/537.36';

    /**
     * Наименование атрибутов, для которых не нужно грузить словари
     */
    private const array EXCLUDED_ATTRIBUTES = ['SKU'];

    public function __construct(
        private PersistenceRepository       $persistenceRepository,
        private CatalogInterface            $catalogRepository,
        private AttributeInterface          $attributeRepository,
        private WbImportRepositoryInterface $wbImportRepository,
        private LoggerInterface             $logger,
        private SuggestInterface            $suggestRepository
    ) {
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function __invoke(Command $command): void
    {
        // Import dictionary that downloads by API
        print_r('| --- Start Import dictionary from DWH --- ' . date("Y-m-d H:i:s") . PHP_EOL);
        $this->getDwhDictionary();
        print_r('| --- END Import dictionary from DWH --- ' . date("Y-m-d H:i:s") . PHP_EOL);
        // ---- END

        print_r('| --- Start Import suggests from Wb --- ' . date("Y-m-d H:i:s") . PHP_EOL);
        $catalogs = $this->catalogRepository->findBy([]);

        foreach ($catalogs as $catalog) {
            $attributes = $this->attributeRepository->findByCatalog($catalog->getCatalogId());
            foreach ($attributes as $attribute) {
                $localSuggest = $this->suggestRepository->findOneBy(['attributeId' => $attribute->getAttributeId()]);
                if ($localSuggest || in_array($attribute->getName(), self::EXCLUDED_ATTRIBUTES, true)) {
                    continue;
                }

                print_r('| - ' . $attribute->getName() . PHP_EOL);

                $url = 'https://seller-content.wildberries.ru/ns/mapping/content-card/mapping/suggest?charc_name='
                    . urlencode($attribute->getName()) . '&subject_id=' . $catalog->getObjectId();

                $suggested = $this->getSuggested($url);
                if ($suggested) {
                    $suggest = new Suggest(
                        suggestId: Uuid::uuid7()->toString(),
                        value: $suggested,
                        attributeId: $attribute->getAttributeId(),
                        catalogId: $catalog->getCatalogId(),
                        objectId: $catalog->getObjectId()
                    );
                    $this->persistenceRepository->persist($suggest);

                    $attribute->setIsDictionary(true);
                    $this->persistenceRepository->persist($attribute);

                    print_r('| - - - has suggested - saved and set is dictionary = ture' . PHP_EOL);
                }

                $sek = random_int(1, 3);
                print_r('| - sleep: ' . $sek . PHP_EOL);
                sleep($sek);
            }
            $this->persistenceRepository->flush();
        }

        print_r('| --- END Import suggests from Wildberries --- ' . date("Y-m-d H:i:s") . PHP_EOL);
    }

    /**
     * @param string $url
     * @return string[]|false
     * @throws GuzzleException
     */
    private function getSuggested(string $url): array|false
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => self::USER_AGENT,
            'Cookie' => self::WB_COOKIE
        ];
        $request = new Request('GET', $url, $headers);
        $res = $client->send($request);
        $res = $res->getBody()->getContents();
        $content = json_decode($res, true);
        if (isset($content['data']['suggest']) && ($content['error'] === false)) {
            return $content['data']['suggest'];
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function getDwhDictionary(): void
    {
        $attributeNames = ['Цвет', 'ТНВЭД'];

        foreach ($attributeNames as $name) {

            $attribute = $this->attributeRepository->findOneBy(['name' => $name]);
            if (is_null($attribute)) {
                $this->logger->warning("Not Find attribute: $name for Wildberries channel
                    - src/Presenter/Command/Wildberries/ImportDictionary/Handler.php::getDwhDictionary()");
                continue;
            }

            if ($this->suggestRepository->findOneBy([
                'attributeId' => $attribute->getAttributeId()
            ])) {
                continue;
            }


            $dictionary = match ($name) {
                'Цвет' => $this->wbImportRepository->getColorDictionary(),
                'ТНВЭД' => $this->wbImportRepository->getTnvedDictionary(),
                //                    'Пол' => $this->wbImportRepository->getKindDictionary(),
                //                    'Страна производства' => $this->wbImportRepository->getCountriesDictionary(),
                //                    'Сезон' => $this->wbImportRepository->getSeasonsDictionary(),
            };

            $suggest = new Suggest(
                suggestId: Uuid::uuid7()->toString(),
                value: $dictionary->value,
                attributeId: $attribute->getAttributeId()
            );
            $this->persistenceRepository->persist($suggest);

            $attribute->setIsDictionary(true);
            $this->persistenceRepository->persist($attribute);
        }
    }
}
