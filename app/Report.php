<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class report extends Model
{

    protected $table = 'report';
    protected $fillable = [
        'title', 'description', 'cluster_id', 'guard_id', 'shift_id', 'photo', 'video'
    ];

}
