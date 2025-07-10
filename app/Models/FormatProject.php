<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormatProject extends Model
{
    //
    protected $table = 'format_projects';
    protected $guarded = [];

    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'format_project_id');
    }
}
