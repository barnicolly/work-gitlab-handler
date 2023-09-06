<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks;

use App\Enums\MergeRequestLabelEnum;
use App\Intergrations\Gitlab\Dto\Items\MergeRequestDto;
use App\Intergrations\Gitlab\Dto\UpdateMergeRequestDto;

class SetMergeRequestsLabelsTask
{
    public function __construct(
        private readonly UpdateMergeRequestTask $updateMergeRequestTask
    ) {
    }

    /**
     * Получаем все MR
     * смотрим на название ветки,
     * если есть bugfix/hotfix и нет такого label - ставим
     *
     * @param MergeRequestDto[] $openedMR
     */
    public function run(array $openedMR): void
    {
        foreach ($openedMR as $mergeRequest) {
            $branchName = $mergeRequest->getSourceBranch();
            $resultLabel = $this->getBugLabel($branchName);
            if ($resultLabel instanceof MergeRequestLabelEnum && $this->needAddLabel(
                $mergeRequest->getLabels(),
                $resultLabel
            )
            ) {
                $dto = new UpdateMergeRequestDto();
                $dto->setLabels([$resultLabel]);
                $mergeRequestId = $mergeRequest->getIid();
                $this->updateMergeRequestTask->run($mergeRequestId, $dto);
            }
        }
    }

    private function needAddLabel(array $labels, MergeRequestLabelEnum $label): bool
    {
        $labelName = mb_strtolower($label->value);
        return !in_array($labelName, $labels, true);
    }

    private function getBugLabel(string $branchName): ?MergeRequestLabelEnum
    {
        $resultLabel = null;
        if (str_starts_with($branchName, 'bugfix/')) {
            $resultLabel = MergeRequestLabelEnum::BUGFIX;
        } elseif (str_starts_with($branchName, 'hotfix/')) {
            $resultLabel = MergeRequestLabelEnum::HOTFIX;
        }
        return $resultLabel;
    }
}
