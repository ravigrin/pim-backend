<?php

declare(strict_types=1);

namespace Pim\EndPoint\Command;

use Pim\Domain\Entity\Dictionary;
use Pim\Domain\Repository\Pim\AttributeInterface;
use Pim\Domain\Repository\Pim\DictionaryInterface;
use Ramsey\Uuid\Uuid;
use Shared\Domain\Service\EntityStoreService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pim:dictionary:import',
    description: 'Загрузка словарей',
)] final class DictionaryImportCommand extends Command
{
    public function __construct(
        private AttributeInterface  $attributeRepository,
        private DictionaryInterface $dictionaryRepository,
        private EntityStoreService  $entityStoreService,
        string                      $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dictionary = [
            'Черновик',
            'В работе',
            'Маркетплейс',
            'Продажи',
        ];

        $attribute = $this->attributeRepository->findOneByCriteria(["alias" => "status"]);
        if (is_null($attribute)) {
            return Command::FAILURE;
        }

        foreach ($dictionary as $value) {
            $word = $this->dictionaryRepository->findOneByCriteria(["value" => $value]);
            if ($word instanceof Dictionary) {
                continue;
            }
            $word = new Dictionary(
                dictionaryId: Uuid::uuid7()->toString(),
                attributeId: $attribute->getAttributeId(),
                value: $value,
            );
            $this->entityStoreService->commit($word);
        }

        return Command::SUCCESS;
    }
}
