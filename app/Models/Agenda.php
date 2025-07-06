<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    //
    protected $table = 'agendas';

    protected $guarded = [];


// Define the relationship with CategoryDocument
    public function categoryDocument()
    {
        return $this->belongsTo('App\Models\CategoryDocument', 'category_documents_id');
    }

    // Define the relationship with Project
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }
}
