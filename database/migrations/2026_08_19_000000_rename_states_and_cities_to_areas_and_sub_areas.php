<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Domain rename: State -> Area, City -> Sub Area.
 *
 * Renames the tables and their foreign keys in place so existing production
 * rows (and their ids) are preserved. Media collection names and polymorphic
 * type aliases are migrated with the same pass.
 */
return new class extends Migration
{
    /**
     * Tables holding the old `city_id` foreign key.
     *
     * @var array<int, string>
     */
    private array $subAreaForeignKeyTables = [
        'compounds',
        'properties',
        'orders',
        'sell_units',
        'contacts',
    ];

    public function up(): void
    {
        if (Schema::hasTable('states') && ! Schema::hasTable('areas')) {
            Schema::rename('states', 'areas');
        }

        if (Schema::hasTable('cities') && ! Schema::hasTable('sub_areas')) {
            Schema::rename('cities', 'sub_areas');
        }

        if (Schema::hasColumn('sub_areas', 'state_id')) {
            Schema::table('sub_areas', function (Blueprint $table): void {
                $table->renameColumn('state_id', 'area_id');
            });
        }

        foreach ($this->subAreaForeignKeyTables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'city_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->renameColumn('city_id', 'sub_area_id');
                });
            }
        }

        $this->renameMorphAlias('city', 'sub_area');
        $this->renameMediaCollection('city_image', 'sub_area_image');
    }

    public function down(): void
    {
        $this->renameMediaCollection('sub_area_image', 'city_image');
        $this->renameMorphAlias('sub_area', 'city');

        foreach ($this->subAreaForeignKeyTables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'sub_area_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->renameColumn('sub_area_id', 'city_id');
                });
            }
        }

        if (Schema::hasColumn('sub_areas', 'area_id')) {
            Schema::table('sub_areas', function (Blueprint $table): void {
                $table->renameColumn('area_id', 'state_id');
            });
        }

        if (Schema::hasTable('sub_areas') && ! Schema::hasTable('cities')) {
            Schema::rename('sub_areas', 'cities');
        }

        if (Schema::hasTable('areas') && ! Schema::hasTable('states')) {
            Schema::rename('areas', 'states');
        }
    }

    private function renameMorphAlias(string $from, string $to): void
    {
        $morphColumns = [
            'media' => 'model_type',
            'faqs' => 'faqable_type',
        ];

        foreach ($morphColumns as $table => $column) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            DB::table($table)->where($column, $from)->update([$column => $to]);
        }
    }

    private function renameMediaCollection(string $from, string $to): void
    {
        if (! Schema::hasTable('media')) {
            return;
        }

        DB::table('media')->where('collection_name', $from)->update(['collection_name' => $to]);
    }
};
