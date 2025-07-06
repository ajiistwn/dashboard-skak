<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileAndData extends Model
{
    //
    protected $table = 'files_and_datas';

    protected $guarded = [];

    protected $casts = [
        'access' => 'array',
    ];

    public function categoryDocument()
    {
        return $this->belongsTo('App\Models\CategoryDocument', 'category_documents_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }
}
