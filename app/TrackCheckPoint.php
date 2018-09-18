<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class TrackCheckPoint extends Model
{

    protected $table = 'track_checkpoint';
    protected $fillable = [
        'track_code', 'latitude', 'longitude', 'beacon_id', 'description'
    ];

    public function getPoint() {
        return $this->belongsTo('App\Track', 'track_code', 'track_code');
    }

}