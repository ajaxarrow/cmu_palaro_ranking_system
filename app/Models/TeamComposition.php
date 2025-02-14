<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeamComposition extends Model
{
    use HasFactory;
    protected $fillable = ['college_id', 'team_id'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
