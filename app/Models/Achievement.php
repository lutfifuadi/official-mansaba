<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'student_name', 'category', 'level', 'description', 'image', 'achievement_date'];

    protected function casts(): array
    {
        return [
            'achievement_date' => 'date',
        ];
    }

    public function extracurriculars()
    {
        return $this->belongsToMany(Extracurricular::class);
    }
}
