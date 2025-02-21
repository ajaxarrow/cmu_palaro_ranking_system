<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedalRecord extends Model
{
    use HasFactory;
    protected $fillable = ['year', 'gold_tally', 'silver_tally', 'bronze_tally', 'entry_by', 'posted_by', 'is_posted', 'number_of_participant', 'team_id', 'event_category_id'];

    public function entryBy()
    {
        return $this->belongsTo(User::class, 'entry_by');
    }

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function event()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class);
    }
}
