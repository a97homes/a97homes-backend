<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('about');
            $table->string('phone')->nullable()->after('whatsapp');
        });

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE developers ALTER COLUMN name TYPE jsonb USING jsonb_build_object('ar', name)");
            DB::statement("ALTER TABLE developers ALTER COLUMN about TYPE jsonb USING jsonb_build_object('ar', about)");
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("UPDATE developers SET name = JSON_OBJECT('ar', name), about = JSON_OBJECT('ar', about)");
            DB::statement('ALTER TABLE developers MODIFY name JSON NOT NULL');
            DB::statement('ALTER TABLE developers MODIFY about JSON NOT NULL');
        }
        // sqlite is dynamically typed: existing TEXT columns store JSON strings as-is.
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE developers ALTER COLUMN name TYPE varchar(255) USING name->>'ar'");
            DB::statement("ALTER TABLE developers ALTER COLUMN about TYPE text USING about->>'ar'");
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("UPDATE developers SET name = JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar')), about = JSON_UNQUOTE(JSON_EXTRACT(about, '$.ar'))");
            DB::statement('ALTER TABLE developers MODIFY name VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE developers MODIFY about TEXT NOT NULL');
        }

        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'phone']);
        });
    }
};
