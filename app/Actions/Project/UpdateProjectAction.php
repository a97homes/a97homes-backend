<?php

namespace App\Actions\Project;

use App\Models\Project;

class UpdateProjectAction
{
    public function execute(Project $project, array $data): Project
    {
        $project->update($data);

        return $project;
    }
}
