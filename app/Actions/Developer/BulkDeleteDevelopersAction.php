<?php

declare(strict_types=1);

namespace App\Actions\Developer;

use App\Models\Developer;
use Illuminate\Support\Facades\DB;

class BulkDeleteDevelopersAction
{
    /**
     * Delete the given developers and return how many rows were removed.
     *
     * @param  array<int, int>  $developerIds
     */
    public function execute(array $developerIds): int
    {
        return DB::transaction(function () use ($developerIds): int {
            $developers = Developer::query()->whereIn('id', $developerIds)->get();

            $developers->each(fn (Developer $developer) => $developer->delete());

            return $developers->count();
        });
    }
}
