<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Guard;
use App\TempRoute;
use App\Cluster;
use App\Track;
use App\Shift;
use Illuminate\Http\Request;
use App\Schedule;
use Illuminate\Http\Response;

class ScheduleController extends Controller
{
    function __construct() 
    {

    }

    function check() {

        $schedule = Shift::get();

        $currentDate = date('Y-m-d H:i:s');
        $result = [];
        $shif = '';

        foreach ($schedule as $key => $value) {
            $checkDate = date('Y-m-d ' . $value['end_at']);
            if ($currentDate > $checkDate) {
                $startDate = date('Y-m-d ' . $value['start_at']);
                $endDate = date('Y-m-d ' . $value['end_at']);
                $shif = $key;
                $result = $this->checkPatrolSchedule($startDate, $endDate);
            }
        }

        return response()->json(['cluster' => $result, 'shif' => $shif]);
        
    }

    function checkPatrolSchedule($startDate, $endDate) {
        $notPatrolYet = [];
        $clusters = Cluster::orderBy('name', 'asc')->get();

        foreach ($clusters as $cluster) {

            $patrol = TempRoute::where('cluster_id', $cluster->code)->where('created_at', '>=' , $startDate)->where('created_at', '<=' , $endDate)->first();
            if (!$patrol) {
                array_push($notPatrolYet, $cluster->name);
            }

        }
        return $notPatrolYet;
    }


}
