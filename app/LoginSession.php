<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class LoginSession extends Model
{

    protected $table = 'login_session';
    protected $fillable = [
        'userid', 'cluster_id', 'latest_activities', 'uid'
    ];

    
}
