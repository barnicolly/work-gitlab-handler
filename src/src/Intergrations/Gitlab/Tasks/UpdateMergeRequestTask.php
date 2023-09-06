<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Tasks;

use App\Intergrations\Gitlab\Dto\UpdateMergeRequestDto;
use App\Intergrations\Gitlab\Http\Connectors\ForgeConnector;
use App\Intergrations\Gitlab\Http\Requests\UpdateMergeRequest;

class UpdateMergeRequestTask
{
    public function __construct(
        private readonly ForgeConnector $forgeConnector,
    ) {
    }

    public function run(int $id, UpdateMergeRequestDto $dto): void
    {
        $request = new UpdateMergeRequest($id);
        $labels = $dto->getLabels();
        if ($labels !== null) {
            $request->addLabels($labels);
        }
        $this->forgeConnector->send($request)->json();
    }
}
