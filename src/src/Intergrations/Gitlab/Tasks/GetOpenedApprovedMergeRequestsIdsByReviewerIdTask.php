<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks;

use App\Intergrations\Gitlab\Http\Connectors\ForgeConnector;
use App\Intergrations\Gitlab\Http\Requests\GetMergeRequests;

class GetOpenedApprovedMergeRequestsIdsByReviewerIdTask
{

    public function __construct(
        private readonly ForgeConnector $forgeConnector,
        private readonly GetMergeRequests $request
    ) {
    }

    public function run(int $reviewerId): array
    {
        $this->request->opened()
            ->reviewedBy($reviewerId);
        $result = $this->forgeConnector->send($this->request)->json();
        if (!empty($result)) {
            return array_column($result, 'iid');
        }
        return [];
    }
}