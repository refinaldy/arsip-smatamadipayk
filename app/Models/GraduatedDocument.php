<?php

namespace App\Models;

use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GraduatedDocument extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
