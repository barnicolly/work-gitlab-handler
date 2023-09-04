<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\UI\CLI;

use App\Intergrations\Gitlab\Tasks\GetOpenedMergeRequestsTask;
use App\Intergrations\Gitlab\Tasks\NotifyAboutNeedMergeTask;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[AsCommand(
    name: 'notify:gitlab:need-merge',
    description: 'Оповещение об MR к слиянию',
    hidden: false
)]
class CheckNeedMergeRequestsCommand extends Command
{
    public function __construct(
        private readonly GetOpenedMergeRequestsTask $getOpenedMergeRequestsTask,
        private readonly ContainerInterface $container,
        private readonly NotifyAboutNeedMergeTask $notifyAboutNeedMergeTask,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $openedMR = $this->getOpenedMergeRequestsTask->run();
        $productUserId = (int) $this->container->getParameter('gitlab.users.product');
        $this->notifyAboutNeedMergeTask->run($openedMR, $productUserId);
        return Command::SUCCESS;
    }
}
