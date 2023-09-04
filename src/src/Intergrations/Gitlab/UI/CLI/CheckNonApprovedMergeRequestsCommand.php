<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\UI\CLI;

use App\Intergrations\Gitlab\Strategies\GetOpenedApprovedMergeRequestsIdsByReviewerIdTask;
use App\Intergrations\Gitlab\Strategies\GetOpenedMergeRequestsTask;
use App\Intergrations\Gitlab\Strategies\TechLeadNotifyStrategy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[AsCommand(
    name: 'app:gitlab:get_mr_for_review',
    description: 'Получение MR для просмотра',
    hidden: false
)]
class CheckNonApprovedMergeRequestsCommand extends Command
{

    public function __construct(


        private readonly ContainerInterface $container
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $maintainerId = 37;

        $maintainerId = 39;

        $strategy = $this->container->get(TechLeadNotifyStrategy::class);

        return Command::SUCCESS;
    }
}