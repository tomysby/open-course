<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'title',
        'content',
        'file_path',
        'file_type',
        'course_id',
        'order',
    ];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}
