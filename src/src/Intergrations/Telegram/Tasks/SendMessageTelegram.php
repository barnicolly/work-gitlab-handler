<?php

declare(strict_types=1);

namespace App\Intergrations\Telegram\Tasks;

use App\Intergrations\Telegram\Http\Connectors\ForgeConnector;
use App\Intergrations\Telegram\Http\Requests\SendMessageRequest;

class SendMessageTelegram
{
    public function __construct(
        private readonly ForgeConnector $forgeConnector,
        private readonly SendMessageRequest $request
    ) {
    }

    public function run(string $text): array
    {
        $this->request->setText($text);
        return $this->forgeConnector
            ->send($this->request)
            ->json();
    }
}
