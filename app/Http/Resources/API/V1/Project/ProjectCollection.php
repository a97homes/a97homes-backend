<?php

namespace App\Http\Resources\API\V1\Project;

use App\Http\Resources\BasePaginationResource;

class ProjectCollection extends BasePaginationResource
{
    public $collects = ProjectResource::class;
}
