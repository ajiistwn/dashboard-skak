<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrewAndCast extends Model
{
    //
    protected $table = 'crew_and_casts';
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }
}
