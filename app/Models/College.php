<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class College extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'acronym'];


    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_compositions');
    }
}
