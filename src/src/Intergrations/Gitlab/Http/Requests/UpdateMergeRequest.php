<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Http\Requests;

use App\Enums\MergeRequestLabelEnum;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * https://docs.gitlab.com/ee/api/merge_requests.html#update-mr
 */
final class UpdateMergeRequest extends Request
{
    protected Method $method = Method::PUT;

    public function __construct(
        private readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/merge_requests/' . $this->id;
    }

    /**
     * @param MergeRequestLabelEnum[] $labels
     */
    public function addLabels(array $labels): self
    {
        $result = [];
        foreach ($labels as $label) {
            $result[] = mb_strtolower($label->value);
        }
        $this->query()->add('add_labels', implode(',', $result));
        return $this;
    }
}
