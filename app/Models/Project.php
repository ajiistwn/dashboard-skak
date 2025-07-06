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

    public function fileAndData()
    {
        return $this->hasMany('App\Models\FileAndData', 'project_id');
    }
    
    public function agendas()
    {
        return $this->hasMany('App\Models\Agenda', 'project_id');
    }
}
