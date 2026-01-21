<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_images extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectImagesFactory> */
    use HasFactory;

    public function project()
    {
        return $this->belongsTo(project::class);
    }
}
