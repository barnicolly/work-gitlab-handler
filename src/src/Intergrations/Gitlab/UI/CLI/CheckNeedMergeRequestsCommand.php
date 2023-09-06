<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\UI\CLI;

use App\Entity\GitlabUser;
use App\Enums\ProjectRole;
use App\Exceptions\NotFoundProjectRoleException;
use App\Intergrations\Gitlab\Tasks\GetOpenedMergeRequestsTask;
use App\Intergrations\Gitlab\Tasks\NotifyAboutNeedMergeTask;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'notify:gitlab:need-merge',
    description: 'Оповещение об MR к слиянию',
    hidden: false
)]
class CheckNeedMergeRequestsCommand extends Command
{
    public function __construct(
        private readonly GetOpenedMergeRequestsTask $getOpenedMergeRequestsTask,
        private readonly NotifyAboutNeedMergeTask $notifyAboutNeedMergeTask,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $openedMR = $this->getOpenedMergeRequestsTask->run();
        $productManager = $this->entityManager
            ->getRepository(GitlabUser::class)
            ->findOneBy(['role' => ProjectRole::PRODUCT_MANAGER]);
        if ($productManager === null) {
            throw new NotFoundProjectRoleException();
        }
        $this->notifyAboutNeedMergeTask->run($openedMR, $productManager->getExternalUserId());
        return Command::SUCCESS;
    }
}
