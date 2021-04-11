<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function graduated_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
