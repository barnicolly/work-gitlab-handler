<?php

declare(strict_types=1);

namespace App\Enums;

enum MergeRequestLabelEnum: string
{
    case BUGFIX = 'BUGFIX';
    case HOTFIX = 'HOTFIX';
}
