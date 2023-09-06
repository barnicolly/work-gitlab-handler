<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\UI\CLI;

use App\Intergrations\Gitlab\Tasks\GetOpenedMergeRequestsTask;
use App\Intergrations\Gitlab\Tasks\SetMergeRequestsLabelsTask;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'gitlab:set-labels',
    description: 'Установка label (если отсутствует) исходя из префикса названия ветки',
    hidden: false
)]
class SetMergeRequestsLabelsCommand extends Command
{
    public function __construct(
        private readonly GetOpenedMergeRequestsTask $getOpenedMergeRequestsTask,
        private readonly SetMergeRequestsLabelsTask $setMergeRequestsLabelsTask,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $openedMR = $this->getOpenedMergeRequestsTask->run();
        $this->setMergeRequestsLabelsTask->run($openedMR);
        return Command::SUCCESS;
    }
}
