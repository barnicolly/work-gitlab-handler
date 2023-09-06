<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Strategies;

use App\Entity\GitlabUser;
use App\Enums\ProjectRole;
use App\Exceptions\NotFoundProjectRoleException;
use App\Intergrations\Gitlab\Contracts\NotifyStrategyInterface;
use App\Intergrations\Gitlab\Dto\Items\MergeRequestDto;
use App\Intergrations\Gitlab\Tasks\Formatters\MergeRequestNotifyFormatterTask;
use App\Intergrations\Gitlab\Tasks\GetOpenedApprovedMergeRequestsIdsByReviewerIdTask;
use App\Intergrations\Gitlab\Tasks\GetOpenedMergeRequestsTask;
use App\Intergrations\Telegram\Tasks\SendMessageTelegram;
use Doctrine\ORM\EntityManagerInterface;

class TechLeadNotifyStrategy implements NotifyStrategyInterface
{
    public function __construct(
        private readonly GetOpenedMergeRequestsTask $getOpenedMergeRequestsTask,
        private readonly GetOpenedApprovedMergeRequestsIdsByReviewerIdTask $getOpenedApprovedMergeRequestsIdsByReviewerIdTask,
        private readonly SendMessageTelegram $sendMessageTelegram,
        private readonly MergeRequestNotifyFormatterTask $mergeRequestNotifyFormatterTask,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function process(): void
    {
        $techLead = $this->entityManager
            ->getRepository(GitlabUser::class)
            ->findOneBy(['role' => ProjectRole::TECH_LEAD]);
        if ($techLead === null) {
            throw new NotFoundProjectRoleException();
        }
        $openedMR = $this->getOpenedMergeRequestsTask->run();
        $this->notifyAboutNonReviewedMR($openedMR, $techLead->getExternalUserId());
    }

    /**
     * Получаем все MR
     * исключаем те, что принял
     *
     * @param MergeRequestDto[] $openedMR
     */
    private function notifyAboutNonReviewedMR(array $openedMR, int $userId): void
    {
        $reviewedMRIds = $this->getOpenedApprovedMergeRequestsIdsByReviewerIdTask->run($userId);

        $result = [];

        foreach ($openedMR as $mergeRequest) {
            $mergeRequestId = $mergeRequest->getIid();
            if (($mergeRequest->getAuthor()->getId() !== $userId) && !in_array($mergeRequestId, $reviewedMRIds, true)) {
                $result[] = [
                    $mergeRequest->getWebUrl(),
                    $mergeRequest->getTitle(),
                ];
            }
        }

        if ($result) {
            $html = $this->mergeRequestNotifyFormatterTask->run($result, 'MR к ревью');
            $this->sendMessageTelegram->run($html);
        }
    }
}
