<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // App/Models/Category.php
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function materials()
    {
        return $this->hasMany(EducationalMaterial::class);
    }
}
