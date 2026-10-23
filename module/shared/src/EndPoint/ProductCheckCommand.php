<?php

declare(strict_types=1);

namespace Shared\EndPoint;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'shared:product:check',
    description: '',
)] final class ProductCheckCommand extends Command
{
    public function __construct(
        string                               $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pimCsv = array_map('str_getcsv', file(__DIR__ . '/pim.csv'));
        $pim = [];
        foreach ($pimCsv as $item) {
            $pim[$item[0]] = $item[1];
        }

        $oneCCsv = array_map('str_getcsv', file(__DIR__ . '/1c.csv'));
        $oneC = [];
        foreach ($oneCCsv as $item) {
            $oneC[$item[0]] = $item[1];
        }

        $diff = array_values(array_diff(array_keys($oneC), array_keys($pim)));

        foreach ($diff as $item) {
            echo sprintf("'%s',", $item) . PHP_EOL;
        }

        return Command::SUCCESS;
    }
}
