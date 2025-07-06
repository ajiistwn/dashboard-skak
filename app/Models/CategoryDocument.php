<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryDocument extends Model
{
    //
    protected $table = 'category_documents';
    protected $guarded = [];

    public function fileAndData()
    {
        return $this->hasMany('App\Models\FileAndData', 'category_documents_id');
    }
}
