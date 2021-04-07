<?php

namespace App\Models;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AchievementRank extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }
}
