<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class TrackCoordinate extends Model
{

    protected $table = 'track_coordinates';
    protected $fillable = [
        'track_code', 'latitude', 'longitude'
    ];

    
}