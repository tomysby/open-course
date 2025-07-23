<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Tag.php
class Tag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function materials()
    {
        return $this->belongsToMany(EducationalMaterial::class);
    }
}
