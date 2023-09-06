<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks;

use App\Intergrations\Gitlab\Dto\Items\MergeRequestDto;
use App\Intergrations\Gitlab\Http\Connectors\ForgeConnector;
use App\Intergrations\Gitlab\Http\Requests\GetMergeRequests;

class GetOpenedMergeRequestsTask
{
    public function __construct(
        private readonly ForgeConnector $forgeConnector,
        private readonly GetMergeRequests $request
    ) {
    }

    /**
     * @return MergeRequestDto[]
     */
    public function run(): array
    {
        $this->request->opened();
        $response = $this->forgeConnector->send($this->request)->json();
        if ($response) {
            foreach ($response as $item) {
                $result[] = (new MergeRequestDto())->fromResponseArray($item);
            }
        }
        return $result ?? [];
    }
}
