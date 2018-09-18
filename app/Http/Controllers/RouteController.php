<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\TempRoute;
use App\TrackCheckPoint;
use App\Patrol;
use App\Panic;
use Log;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    function __construct() 
    {

    }

    function save(Request $req) {
        
        $data = [
            'cluster_id' => $req->input('cluster_id'),
            'longitude' => $req->input('longitude'),
            'latitude' => $req->input('latitude'),
            'speed' => $req->input('speed'),
            'accuracy' => $req->input('accuracy'),
            'unique_key' => $req->input('unique_key'),
            'device_date' => $req->input('date')
        ];

        $rsl = TempRoute::create($data);
        if ($rsl) {
            return response()->json(
                ['message' => 'saved', 
                'status' => true]);
        } else {
            return response()->json(
                ['message' => 'error on save', 
                'status' => false]);
        }
        

    }

    function fetch() {
        $rsl =  TempRoute::orderBy('id', 'desc')->limit(1)->first();
        return response()->json(['status' => true, 'data' => $rsl]);
    }

    function checkpoint(Request $req) {

        $currentPoint = TrackCheckPoint::where('point_order', $req->input('point'))->first();

        if($currentPoint) {
            $trackCode = $currentPoint->track_code;
            $pointOrder = $currentPoint->point_order;
            $nextPointOrder = $pointOrder + 1;
            $nextPoint = TrackCheckPoint::where('track_code', $trackCode)->where('point_order', $nextPointOrder)->first();

            Patrol::create([
                'guard_id' => $req->input('guard_id'), 
                'track_code' => $req->input('track_code'), 
                'point_id' => $currentPoint->id, 
                'schedule_id' => $req->input('schedule_id'), 
                'check_in_timestamp' => $req->input('date')]
            );

            if($nextPoint) {
                
                return response()->json(['status' => true, 'data' => ['current' => $currentPoint, 'next' => $nextPoint]]);
            } else {
                return response()->json(['status' => true, 'data' => ['current' => $currentPoint, 'next' => false]]);
            }

        } else {
            return response()->json(['status' => false, 'message' => 'invalid beacon id']);
        }
    }

    function getCheckpoint(Request $req) {
        if ($req->input('track_code') == '') {
            return response()->json(['status' => false, 'message' => 'Need track_code']);
        }

        $currentPoint = TrackCheckPoint::where('track_code', $req->input('track_code'))->get();

        if(count($currentPoint) > 0) {

            foreach ($currentPoint as $value) {
                $temp[$value->point_order] = [ 
                    'latitude' => $value->latitude,
                    'longitude' => $value->longitude,
                    'description' => $value->description
                ];
            }
            return response()->json(['status' => true, 'data' => $temp]);
        } else {
            return response()->json(['status' => false, 'message' => 'No Track Found']);
        }
    }

    function getData(Request $req) {
        if ($req->input('data') != '') {
            $data = $req->input('data');
            if (count($data) > 0) {
                foreach ($data as $value) {
                    // if ($value['type'] == 1) {
                        // $tem = $value
                        Patrol::create(
                            [
                                'guard_id' => $value['userid'], 
                                'track_code' => $value['trackid'], 
                                'point_id' => $value['pointid'],
                                // 'schedule_id' => $req->input('schedule_id'), 
                                'check_in_timestamp' => $value['date']
                            ]
                        );
                    // }
                }
                return response()->json(['status' => true, 'message' => 'Ok']);
            } else {
            return response()->json(['status' => false, 'message' => 'No Data']);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Empty Data']);
        }
    }

    function uploadFile(Request $req) {
        $destinationPath = rtrim(app()->basePath('public') . '/uploads');
        $filename = date('YmdHis') . time() . '.3gp';
        $result = $req->file('audio')->move($destinationPath, $filename);
        return response()->json(['status' => true, 'filename' => $filename]);
    }

    function panic(Request $req) {
        Log::debug($req->all());
        $id = Panic::create([
            'username' => $req->input('username'), 
            'audio_filename' => $req->input('filename'), 
            'latitude' => $req->input('coo.lat'),
            'longitude' => $req->input('coo.lng'),
            'accuracy' => $req->input('coo.accuracy')
        ]);
        if ($id) {
            return response()->json(['status' => true, 'id' => $id]);
        } else {
            return response()->json(['status' => false, 'message' => 'db fail save']);
        }

    }

    function getRouteData(Request $req) {
        $temp = [];
        if ($req->input('cluster_code') == '' || $req->input('date') == '') {
            return response()->json(['status' => false, 'message' => 'Invalid parameter']);
        }

        // dd(date('Y-m-d', strtotime($req->input('date') .' +1 day')));

        $currentPoint = TempRoute::where('cluster_id', $req->input('cluster_code'))
                        ->whereDate('created_at', '>=', $req->input('date'))
                        ->whereDate('created_at', '<=', date('Y-m-d', strtotime($req->input('date') .' +1 day')))
                        ->get();

        // dd($currentPoint);



        // if(count($currentPoint) > 0) {

        foreach ($currentPoint as $value) {
            $temp[] = [ 
                'lat' => floatval($value->latitude),
                'lng' => floatval($value->longitude)
            ];
        }
            return response()->json(['status' => true, 'data' => $temp]);
        // } else {
        //     return response()->json(['status' => false, 'message' => 'No Track Found']);
        // }
    }

}
