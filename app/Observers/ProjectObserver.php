<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        Log::info("Project {$project->title} was created by {$project->manager->name} ");
    }


}
