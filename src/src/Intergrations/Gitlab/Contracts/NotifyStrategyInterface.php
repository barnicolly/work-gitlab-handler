<?php

declare(strict_types=1);

namespace App\Intergrations\Gitlab\Contracts;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('role.notify.handler')]
interface NotifyStrategyInterface
{
    public function process(): void;
}
