<?php

declare(strict_types=1);

namespace App\Intergrations\Telegram\Http\Connectors;

use Saloon\Http\Connector;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ForgeConnector extends Connector
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function resolveBaseUrl(): string
    {
        $token = $this->container->getParameter('telegram.token');
        return "https://api.telegram.org/bot{$token}";
    }

    protected function defaultQuery(): array
    {
        $chatId = $this->container->getParameter('telegram.chat_id');
        return [
            'chat_id' => $chatId,
            'disable_web_page_preview' => true,
        ];
    }
}
