<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class Patrol extends Model
{

    protected $table = 'patrols';
    protected $fillable = [
        'guard_id', 'track_code', 'point_id', 'check_in_timestamp', 'schedule_id'
    ];

    
}
