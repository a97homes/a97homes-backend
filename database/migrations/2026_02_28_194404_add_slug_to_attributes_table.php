<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        DB::table('attributes')->orderBy('id')->each(function ($attribute) {
            $name = json_decode($attribute->name, true)['en'] ?? '';
            DB::table('attributes')
                ->where('id', $attribute->id)
                ->update(['slug' => Str::slug($name)]);
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
