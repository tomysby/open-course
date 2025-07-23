<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Comment.php
class Comment extends Model
{
    protected $fillable = ['content', 'user_id', 'educational_material_id', 'parent_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(EducationalMaterial::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
