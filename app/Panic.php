<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class Panic extends Model
{

    protected $table = 'panics';
    protected $fillable = [
        'username', 'audio_filename', 'latitude', 'longitude', 'accuracy'
    ];

    
}
