<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $table = 'company_info';

    protected $fillable = [
        'phone',
        'email',
        'working_hours',
        'address',
    ];

    /**
     * Get the singleton instance (first record or create default).
     */
    public static function current(): self
    {
        return self::firstOrCreate([], [
            'phone' => null,
            'email' => null,
            'working_hours' => null,
            'address' => null,
        ]);
    }
}
