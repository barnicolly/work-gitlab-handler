<?php

declare(strict_types=1);

namespace App\Intergrations\Telegram\Http\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

final class SendMessageRequest extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/sendMessage';
    }

    public function setText(string $text): self
    {
        $this->query()->add('text', $text);
        $this->query()->add('parse_mode', 'HTML');
        return $this;
    }
}
