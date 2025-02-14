<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedalRecord extends Model
{
    use HasFactory;
    protected $fillable = ['year', 'gold_tally', 'silver_tally', 'bronze_tally', 'entry_by', 'posted_by', 'is_posted', 'number_of_participant'];

    public function entryBy()
    {
        return $this->belongsTo(User::class, 'entry_by');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
