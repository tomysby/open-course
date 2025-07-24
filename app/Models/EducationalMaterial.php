<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EducationalMaterial extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content', 'type', 
        'file_path', 'thumbnail_path', 'status', 'rejection_reason'
    ];

    protected $casts = [
        'type' => 'string',
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // File type helpers
    public function isArticle(): bool { return $this->type === 'article'; }
    public function isImage(): bool { return $this->type === 'image'; }
    public function isPdf(): bool { return $this->type === 'pdf'; }
    public function isAudio(): bool { return $this->type === 'audio'; }
    public function isVideo(): bool { return $this->type === 'video'; }

    // Accessors for file URLs
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::disk('educational-materials')->url($this->file_path) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }
        return asset('images/default-thumbnail.jpg');
    }

    public function getFileExtensionAttribute()
    {
        return $this->file_path ? pathinfo($this->file_path, PATHINFO_EXTENSION) : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }
}