<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Strategies;

use App\Intergrations\Gitlab\Contracts\NotifyStrategyInterface;
use App\Intergrations\Gitlab\Tasks\Formatters\MergeRequestNotifyFormatterTask;
use App\Intergrations\Gitlab\Tasks\GetOpenedApprovedMergeRequestsIdsByReviewerIdTask;
use App\Intergrations\Gitlab\Tasks\GetOpenedMergeRequestsTask;
use App\Intergrations\Telegram\Tasks\SendMessageTelegram;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TechLeadNotifyStrategy implements NotifyStrategyInterface
{
    public function __construct(
        private readonly GetOpenedMergeRequestsTask $getOpenedMergeRequestsTask,
        private readonly GetOpenedApprovedMergeRequestsIdsByReviewerIdTask $getOpenedApprovedMergeRequestsIdsByReviewerIdTask,
        private readonly ContainerInterface $container,
        private readonly SendMessageTelegram $sendMessageTelegram,
        private readonly MergeRequestNotifyFormatterTask $mergeRequestNotifyFormatterTask
    ) {
    }

    public function process(): void
    {
        $openedMR = $this->getOpenedMergeRequestsTask->run();
        $techLeadUserId = (int) $this->container->getParameter('gitlab.users.tech_lead');
        $this->notifyAboutNonReviewedMR($openedMR, $techLeadUserId);
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
                    $result[] = [
                        $mergeRequest['web_url'],
                        $mergeRequest['title'],
                    ];
                }
            }
        }

        if ($result) {
            $html = $this->mergeRequestNotifyFormatterTask->run($result, 'MR к ревью');
            $this->sendMessageTelegram->run($html);
        }
    }


}
