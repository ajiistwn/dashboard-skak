<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskProgressProject extends Model
{
    //
    protected $table = 'task_progres_projects';
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function progressProject()
    {
        return $this->belongsTo(ProgressProject::class, 'progress_project_id');
    }
}
