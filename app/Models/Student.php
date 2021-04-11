<?php

namespace App\Models;

use App\Models\Achievement;
use App\Models\AcademicYear;
use App\Models\GraduatedDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class);
    }

    public function graduated_document()
    {
        return $this->hasOne(GraduatedDocument::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
