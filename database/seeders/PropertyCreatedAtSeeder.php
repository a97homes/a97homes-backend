<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyCreatedAtSeeder extends Seeder
{
    public function run(): void
    {
        $baseCreatedAt = now()->startOfSecond()->subSeconds(Property::query()->count());
        $offset = 0;

        Property::query()
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($properties) use ($baseCreatedAt, &$offset): void {
                foreach ($properties as $property) {
                    $propertyTimestamp = $baseCreatedAt->copy()->addSeconds($offset++);

                    DB::table('properties')
                        ->where('id', $property->id)
                        ->update([
                            'created_at' => $propertyTimestamp,
                            'updated_at' => $propertyTimestamp,
                        ]);
                }
            });
    }
}
