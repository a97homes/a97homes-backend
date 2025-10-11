<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\User\User;

class Role extends SpatieRole
{
    use CreatedAtFilter;


}
