<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks;

use App\Intergrations\Gitlab\Dto\Items\MergeRequestDto;
use App\Intergrations\Gitlab\Tasks\Formatters\MergeRequestNotifyFormatterTask;
use App\Intergrations\Telegram\Tasks\SendMessageTelegram;

class NotifyAboutNeedMergeTask
{
    public function __construct(
        private readonly GetOpenedApprovedMergeRequestsIdsByReviewerIdTask $getOpenedApprovedMergeRequestsIdsByReviewerIdTask,
        private readonly SendMessageTelegram $sendMessageTelegram,
        private readonly MergeRequestNotifyFormatterTask $mergeRequestNotifyFormatterTask
    ) {
    }

    /**
     * Получаем все MR
     * исключаем те, что принял продакт
     * оповещаем о том, что нужно влить ветку
     *
     * @param MergeRequestDto[] $openedMR
     */
    public function run(array $openedMR, int $userId): void
    {
        $reviewedMRIds = $this->getOpenedApprovedMergeRequestsIdsByReviewerIdTask->run($userId);

        $result = [];

        foreach ($openedMR as $mergeRequest) {
            $mergeRequestId = $mergeRequest->getIid();
            if ($mergeRequest->getAuthor()->getId() !== $userId && in_array($mergeRequestId, $reviewedMRIds, true)) {
                $result[] = $mergeRequest->getTitle();
            }
        }

        if ($result) {
            $html = $this->mergeRequestNotifyFormatterTask->run($result, 'MR к слиянию');
            $this->sendMessageTelegram->run($html);
        }
    }
}
