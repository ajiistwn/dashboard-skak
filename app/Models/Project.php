<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //
    protected $table = 'projects';
    protected $guarded = [];

    public function crewAndCasts()
    {
        return $this->hasMany('App\Models\CrewAndCast', 'project_id');
    }
}
