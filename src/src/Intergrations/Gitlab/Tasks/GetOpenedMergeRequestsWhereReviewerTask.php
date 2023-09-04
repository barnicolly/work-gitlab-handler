<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks;

use App\Intergrations\Gitlab\Http\Connectors\ForgeConnector;
use App\Intergrations\Gitlab\Http\Requests\GetMergeRequests;

class GetOpenedMergeRequestsWhereReviewerTask
{
    public function __construct(
        private readonly ForgeConnector $forgeConnector,
        private readonly GetMergeRequests $request
    ) {
    }

    public function run(int $reviewerId): array
    {
        $this->request->opened()
            ->reviewerId($reviewerId);
        return $this->forgeConnector->send($this->request)
            ->json();
    }
}
