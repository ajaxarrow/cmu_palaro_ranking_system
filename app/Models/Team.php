<?php

namespace App\Models;

use App\Models\College;
use App\Models\MedalRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Team extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image','desc'];

    public function colleges()
    {
        return $this->belongsToMany(College::class, 'team_compositions');
    }

    public function medalRecords(): HasMany
    {
        return $this->hasMany(MedalRecord::class);
    }

}
