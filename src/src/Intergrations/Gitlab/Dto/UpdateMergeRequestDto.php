<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Dto;

use App\Enums\MergeRequestLabelEnum;

class UpdateMergeRequestDto
{
    /**
     * @var MergeRequestLabelEnum[]|null
     */
    private ?array $labels;

    public function getLabels(): ?array
    {
        return $this->labels;
    }

    // Setters
    public function setLabels(array $labels): void
    {
        $this->labels = $labels;
    }
}