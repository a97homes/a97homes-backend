<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyCreatedAtSeeder extends Seeder
{
    private const PROPERTIES_PER_DAY = 2;

    private const FIRST_HOUR_OF_DAY = 9;

    public function run(): void
    {
        $propertiesCount = Property::query()->count();

        if ($propertiesCount === 0) {
            return;
        }

        $daysSpan = (int) ceil($propertiesCount / self::PROPERTIES_PER_DAY);
        $offset = 0;

        Property::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($properties) use ($daysSpan, &$offset): void {
                foreach ($properties as $property) {
                    $daysAgo = $daysSpan - 1 - intdiv($offset, self::PROPERTIES_PER_DAY);
                    $hourWithinDay = self::FIRST_HOUR_OF_DAY + ($offset % self::PROPERTIES_PER_DAY);

                    $propertyTimestamp = now()
                        ->startOfDay()
                        ->subDays($daysAgo)
                        ->addHours($hourWithinDay);

                    DB::table('properties')
                        ->where('id', $property->id)
                        ->update([
                            'created_at' => $propertyTimestamp,
                            'updated_at' => $propertyTimestamp,
                        ]);

                    $offset++;
                }
            });
    }
}
