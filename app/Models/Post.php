<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, notifiable;
    protected $fillable = ['title', 'content', 'slug'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
