<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Strategies;

use App\Intergrations\Gitlab\Contracts\NotifyStrategyInterface;
use App\Intergrations\Gitlab\Tasks\GetOpenedApprovedMergeRequestsIdsByReviewerIdTask;
use App\Intergrations\Gitlab\Tasks\GetOpenedMergeRequestsTask;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TechLeadNotifyStrategy implements NotifyStrategyInterface
{
    public function __construct(
        private readonly GetOpenedMergeRequestsTask $getOpenedMergeRequestsTask,
        private readonly GetOpenedApprovedMergeRequestsIdsByReviewerIdTask $getOpenedApprovedMergeRequestsIdsByReviewerIdTask,
        private readonly ContainerInterface $container
    ) {
    }

    public function process(): void
    {
        $openedMR = $this->getOpenedMergeRequestsTask->run();

        $techLeadUserId = (int) $this->container->getParameter('gitlab.users.tech_lead');
        $this->notifyAboutNonReviewedMR($openedMR, $techLeadUserId);

        $productUserId = (int) $this->container->getParameter('gitlab.users.product');
        $this->notifyAboutMRNeedMerge($openedMR, $productUserId);
    }

    /**
     * Получаем все MR
     * исключаем те, что принял продакт
     * оповещаем о том, что нужно влить ветку
     */
    private function notifyAboutMRNeedMerge(array $openedMR, int $userId): void
    {
        $reviewedMRIds = $this->getOpenedApprovedMergeRequestsIdsByReviewerIdTask->run($userId);

        $result = [];

        foreach ($openedMR as $mergeRequest) {
            $mergeRequestId = $mergeRequest['iid'];
            if ($mergeRequest['author']['id'] !== $userId) {
                if (in_array($mergeRequestId, $reviewedMRIds, true)) {
                    $result[] = $mergeRequest['title'];
                }
            }
        }

        if ($result) {
        }
    }

    /**
     * Получаем все MR
     * исключаем те, что принял
     */
    private function notifyAboutNonReviewedMR(array $openedMR, int $userId): void
    {
        $reviewedMRIds = $this->getOpenedApprovedMergeRequestsIdsByReviewerIdTask->run($userId);

        $result = [];

        foreach ($openedMR as $mergeRequest) {
            $mergeRequestId = $mergeRequest['iid'];
            if ($mergeRequest['author']['id'] !== $userId) {
                if (!in_array($mergeRequestId, $reviewedMRIds, true)) {
                    $result[] = $mergeRequest['title'];
                }
            }
        }

        if ($result) {
        }
    }
}