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
}
