<?php

namespace App\Models;

use App\Models\Student;
use App\Models\AchievementRank;
use App\Models\AchievementCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Achievement extends Model
{
    use HasFactory;
    protected $guarded = [
        'id',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function achievement_rank()
    {
        return $this->belongsTo(AchievementRank::class);
    }

    public function achievement_category()
    {
        return $this->belongsTo(AchievementCategory::class);
    }
}
