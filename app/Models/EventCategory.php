<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'is_double_bronze', 'sport_id', 'medal_weight_category_id', 'medal_count'];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function medalWeightCategory()
    {
        return $this->belongsTo(MedalWeightCategory::class);
    }


    public function medalRecords()
    {
        return $this->hasMany(MedalRecord::class);
    }
}
