<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Http\Connectors;

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
        $url = $this->container->getParameter('gitlab.url');
        $projectId = (int) $this->container->getParameter('gitlab.project_id');
        return "{$url}/api/v4/projects/{$projectId}";
    }

    protected function defaultHeaders(): array
    {
        $token = $this->container->getParameter('gitlab.token');
        return [
            'PRIVATE-TOKEN' => $token,
        ];
    }
}
