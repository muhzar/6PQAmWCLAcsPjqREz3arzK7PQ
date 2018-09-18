<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class Cluster extends Model
{

    protected $table = 'clusters';
    protected $fillable = [
        'name', 'code', 'cluster_code', 'description', 'latitude', 'longitude'
    ];

}
