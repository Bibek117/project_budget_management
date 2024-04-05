<?php

namespace App\Repostories;

use App\Models\Project;

class ProjectRepository extends CommonInterfaceRepository{

    public function __construct(Project $project)
    {
        parent::__construct($project);
    }
}
?>