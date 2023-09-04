<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Http\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * https://docs.gitlab.com/ee/api/merge_requests.html#list-merge-requests
 */
final class GetMergeRequests extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/merge_requests/';
    }

    public function reviewedBy(int $reviewerId): self
    {
        $this->query()->add('approved_by_ids[]', $reviewerId);
        return $this;
    }

    public function opened(): self
    {
        $this->query()->add('state', 'opened');
        return $this;
    }

}