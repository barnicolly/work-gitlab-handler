<?php

declare(strict_types=1);

namespace App\Enums;

enum ProjectRole: string
{
    case PRODUCT_MANAGER = 'PRODUCT_MANAGER';
    case TECH_LEAD = 'TECH_LEAD';
}
