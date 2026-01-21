<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tag(string $type)
    {
        $tag = Tags::firstorCreate(['type' => $type]);

        $this->tags()->attach($tag);
    }

    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'project_tag');
    }

    public function images()
    {
        return $this->hasMany(project_images::class);
    }
}
