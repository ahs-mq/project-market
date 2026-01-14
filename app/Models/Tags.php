<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    public function projects()
    {
        return $this->belongsToMany(project::class);
    }
}
