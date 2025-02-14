<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedalWeightCategory extends Model
{
    use HasFactory;
    protected $fillable = ['category_no', 'name', 'gold_weight', 'silver_weight', 'bronze_weight'];
}
