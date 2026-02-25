<?php

namespace App\Actions\Project;

use App\Models\Project;

class StoreProjectAction
{
    public function execute(array $data): Project
    {
        return Project::create($data);
    }
}
