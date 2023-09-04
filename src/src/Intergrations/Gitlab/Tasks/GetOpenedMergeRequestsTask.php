<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks;

use App\Intergrations\Gitlab\Http\Connectors\ForgeConnector;
use App\Intergrations\Gitlab\Http\Requests\GetMergeRequests;

class GetOpenedMergeRequestsTask
{
    public function __construct(
        private readonly ForgeConnector $forgeConnector,
        private readonly GetMergeRequests $request
    ) {
    }

    public function run(): array
    {
        $this->request->opened();
        return $this->forgeConnector->send($this->request)->json();
    }
}
