<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class Track extends Model
{

    protected $table = 'tracks';
    protected $fillable = [
        'name', 'code', 'cluster_code'
    ];


    public function getCluster() {
        return $this->belongsTo('App\Cluster', 'cluster_code', 'code');
    }

    public function getPoint() {
        return $this->hasMany('App\TrackCheckPoint', 'track_code', 'code');
    }

    public function getCoordinate() {
        return $this->hasMany('App\TrackCheckPoint', 'track_code', 'code')->select(['longitude', 'latitude', 'point_order', 'description']);
    } 

    
}