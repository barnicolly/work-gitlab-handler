<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\UI\CLI;

use App\Intergrations\Gitlab\Contracts\NotifyStrategyInterface;
use App\Intergrations\Gitlab\Strategies\ProductNotifyStrategy;
use App\Intergrations\Gitlab\Strategies\TechLeadNotifyStrategy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsCommand(
    name: 'app:gitlab:notify-about-mr',
    description: 'Получение MR для просмотра',
    hidden: false
)]
class CheckNonApprovedMergeRequestsCommand extends Command
{

    public function __construct(
        #[TaggedIterator('role.notify.handler')]
        private readonly iterable $handlers
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var NotifyStrategyInterface $handler */
        foreach ($this->handlers as $handler) {
//            if ($handler instanceof TechLeadNotifyStrategy) {
//                $handler->process($techLeadUserId);
//            }
            if ($handler instanceof TechLeadNotifyStrategy) {
                $handler->process();
            }
        }

//        $context = $this->container->get('context');
//        $maintainerId = 39;

//        $strategy = $this->container->get(TechLeadNotifyStrategy::class);

        return Command::SUCCESS;
    }
}