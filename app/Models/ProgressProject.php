<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressProject extends Model
{
    //
    protected $table = 'progress_projects';
    protected $guarded = [];


    public function tasksProgressProject()
    {
        return $this->hasMany(TaskProgressProject::class, 'progress_project_id');
    }
}
