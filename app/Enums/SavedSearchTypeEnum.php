<?php

declare(strict_types=1);

namespace App\Enums;

enum SavedSearchTypeEnum: string
{
    case Compound = 'compound';
    case Property = 'property';
}
