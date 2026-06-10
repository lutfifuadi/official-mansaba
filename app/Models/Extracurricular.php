<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'coach', 'image', 'schedule', 'category'];

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class);
    }
}
