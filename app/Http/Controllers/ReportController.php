<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    function __construct() 
    {

    }

    function save(Request $req) {
        if ($req->input('title') == '' || 
            $req->input('description') == '' || 
            $req->input('guard_id') == '' || 
            $req->input('cluster_id') == '' || 
            $req->input('shift_id') == '') {
            return response()->json(['status' => false, 'message' => 'Invalid parameter']);
        }

        $photoName = '';
        $videoName = '';
        if ($req->hasFile('photo')) {
            $photoName = time().'.'.$req->photo->getClientOriginalExtension();
            $req->photo->move(rtrim(app()->basePath('public/report/photo'), '/'), $photoName);
        }


        if ($req->hasFile('video')) {
            $videoName = time().'.'.$req->video->getClientOriginalExtension();
            $req->video->move(rtrim(app()->basePath('public/report/video'), '/'), $videoName);
        }

        $data = [ 'title' => $req->input('title'),
                  'description' => $req->input('description'),
                  'guard_id' => $req->input('guard_id'),
                  'cluster_id' => $req->input('cluster_id'),
                  'shift_id' => $req->input('shift_id'),
                  'photo' => $photoName,
                  'video' => $videoName
                ];

        Report::create($data);
        return response()->json(['status' => 'Ok']);
        
    }

}
