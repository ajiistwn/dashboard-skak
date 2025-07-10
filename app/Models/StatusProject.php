<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusProject extends Model
{
    //
    protected $table = 'status_projects';
    protected $guarded = [];

    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'status_project_id');
    }
    
}
