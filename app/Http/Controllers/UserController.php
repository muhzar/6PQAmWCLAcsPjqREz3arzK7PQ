<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Guard;
use App\LoginSession;
use App\Cluster;
use App\Patrol;
use App\Track;
use App\Shift;
use Illuminate\Http\Request;
use App\Schedule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function __construct() 
    {

    }

    function auth(Request $req) {
        $usr = Guard::where('username', "ILIKE", $req->input('username'))
            ->first();

        if ($usr) {   //is user exist?
            if (Hash::check($req->input('password'), $usr->password)) {   //if password correct?
                $data['user'] = [ 
                    'id' => $usr->id,
                    'username' => $usr->username,
                ];
                $data['status'] = true;
                $data['unique_key'] = md5(time().'asiq');
                $isExist = LoginSession::where('userid', $req->input('username'))->first();

                if ($isExist) {
                    //send mqtt logout and delete record
                    if($req->input('uid') != $isExist->uid) {
                        LoginSession::destroy($isExist->id);
                        $this->saveLoginSession($req);
                    }
                } else {
                    $this->saveLoginSession($req);
                }

                

                $data  = array_merge($data, $this->get($req->input('username')));
            } else {
                $data = ['status' => false, 'message' => 'wrong User or password'];
            }
        } else {
            $data = ['status' => false, 'message' => 'User not found'];
        }

        return response()->json($data);
    }

    function saveLoginSession($req) {
        LoginSession::create([
            'userid' => $req->input('username'), 
            'latest_activities' => date('Y-m-d H:i:s'), 
            'shift_id' => '',
            'cluster_id' => '',
            'uid' => $req->input('uid')
        ]);

        // Patrol::create([
        //     'guard_id' => $req->input('username'),
        //     'cluster_code',
        //     'shift_id'
        // ]);
    }

    function logout(Request $req) {
        LoginSession::where('userid',"ILIKE",  $req->input('username'))->delete();
        return response()->json(['status' => true]);
    }

    function get($username) {
        $usr = Guard::where('username', "ILIKE", $username)->first();
        if($usr) {
            $data = [];

            if ($usr->username == 'admin') {

                $clusters = Cluster::orderBy('name', 'asc')->get();

                foreach ($clusters as $cluster) {
                    $tracks = Track::where('cluster_code', $cluster->code)->get();
                    $data[$cluster->code] = [];
                    foreach ($tracks as $track) {

                        $temp = [
                            'code' => $track->code,
                            'name' => $track->name,
                            'coordinates' => $track->getCoordinate->toArray()
                        ];

                        $data[$cluster->code][] = $temp;

                    }

                    $dataCluster[] = [
                        'code' => $cluster->code,
                        'name' => $cluster->name,
                        'track' => $data[$cluster->code]
                    ];

                }

                return [
                    'schedule_now' => true,
                    'cluster' => $dataCluster,
                    'shift_id' => $shiftId = $this->getShift()
                ];
            
            } else {
                //find schedule
                $date = date('Y-m-d 00:00:00');
                $shiftId = $this->getShift(); 
                $schedule = Schedule::where('guard_username', "ILIKE", $usr->username)
                    ->where('assign_date', $date)
                    ->where('shift_id', $shiftId)
                    ->get();
                $data = [];
                if ($schedule->count() > 0) {
                    foreach ($schedule as $value) {
                        $temp = [
                            'code' => $value->getTrack->code,
                            'name' => $value->getTrack->name,
                            'coordinates' => $value->getTrack->getCoordinate->toArray()
                        ];
                        if (isset($data[$value->getTrack->getCluster->code])) {
                            $temp1 = $data[$value->getTrack->getCluster->code];
                            $temp1[] = $temp;
                            $data[$value->getTrack->getCluster->code] =  $temp1;
                        } else {
                            $data[$value->getTrack->getCluster->code][] = $temp;
                            $name[$value->getTrack->getCluster->code] = $value->getTrack->getCluster->name;
                        }

                    }

                    foreach ($data as $code => $value) {
                        $dataCluster[] = [
                            'code' => $code,
                            'name' => $name[$code],
                            'track' => $value
                        ];
                    }

                    return [
                        'schedule_now' => true,
                        'shift_id' => $shiftId,
                        'cluster' => $dataCluster
                    ];
                } else {
                        return [
                            'message' => 'no schedule for this day', 
                            'shift_id' => $shiftId,
                            'schedule_now' => false
                        ];
                }
            }

        } else {
            return [
                'message' => 'user not found', 
                'status' => false
            ];
         
        }
    }


    function generate() {
        echo Hash::make('adminalsut');
    }


    function getShift() {
        $shift = 0;
        $schedule = Shift::get();
        $currentDate = date('H:i:s');

        foreach ($schedule as $key => $value) {
            $checkDate = $value['end_at'];
            if ($currentDate > $value['start_at'] && $currentDate < $value['end_at'] ) {
                $shift = $value['id'];
                break;
            }
        }
        return $shift;
    }


}
