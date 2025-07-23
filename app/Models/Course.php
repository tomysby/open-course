<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'category_id',
        'user_id',
        'is_published',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhereHas('category', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
    }
}
