<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class Schedule extends Model
{

    protected $table = 'guard_schedule';
    protected $fillable = [
        'guard_username', 'track_code', 'assign_date', 'shift_id'
    ];

    public function getGuard() {
        return $this->belongsTo('App\Guard', 'guard_username', 'username');
    }

    public function getShift() {
        return $this->belongsTo('App\Shift', 'shift_id', 'id');
    }
    
    public function getTrack() {
        return $this->belongsTo('App\Track', 'track_code', 'code');
    }
}
