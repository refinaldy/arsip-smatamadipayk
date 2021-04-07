<?php

namespace App\Models;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function achievement()
    {
        return $this->hasMany(Achievement::class);
    }
}
