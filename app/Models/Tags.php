<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function projects()
    {
        return $this->belongsToMany(project::class, 'project_tag');
    }
}
