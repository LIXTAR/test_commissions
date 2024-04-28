<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\TransactionDetail;
use App\Exception\CommissionCannotBeCalculatedException;
use App\Service\CommissionsCalculator;
use App\Service\InputFileReader;
use App\Service\OutputFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand("app:commissions:execute")]
class CommissionsExecutionStarterCommand extends Command
{
    public function __construct(
        private readonly InputFileReader       $reader,
        private readonly CommissionsCalculator $commissionsCalculator,
        private readonly OutputFormatter       $formatter,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('input_file_path', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var TransactionDetail $detail */
            foreach ($this->reader->readOne($input->getArgument('input_file_path')) as $detail) {
                try {
                    $output->writeln($this->formatter->format($this->commissionsCalculator->calculate($detail)));
                } catch (CommissionCannotBeCalculatedException $exception) {
                    $output->writeln($exception->getMessage());
                }
            }
        } catch (Throwable) {
            $output->writeln("Malformed input file");
        }

        return self::SUCCESS;
    }
}
