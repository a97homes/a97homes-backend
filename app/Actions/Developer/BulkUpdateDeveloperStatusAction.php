<?php

declare(strict_types=1);

namespace App\Actions\Developer;

use App\Models\Developer;

class BulkUpdateDeveloperStatusAction
{
    /**
     * Activate or deactivate the given developers and return how many rows changed.
     *
     * @param  array<int, int>  $developerIds
     */
    public function execute(array $developerIds, bool $isActive): int
    {
        return Developer::query()
            ->whereIn('id', $developerIds)
            ->update(['is_active' => $isActive]);
    }
}
