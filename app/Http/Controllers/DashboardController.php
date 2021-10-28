<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Attendance;
use App\Models\WorkHour;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == role('super-admin') || Auth::user()->role == role('admin') || Auth::user()->role == role('manager')) {
            // View
            return view('admin/dashboard/index');
        }
        elseif(Auth::user()->role == role('member')) {
            // Check whether is already absent and not exit yet
            $is_entry = Attendance::has('workhour')->where('user_id','=',Auth::user()->id)->where('exit_at','=',null)->whereDate('entry_at','=',date('Y-m-d'))->get();

            // Display attendance if is already exit
            if(count($is_entry) <= 0) {
                // Get work hours
		        $work_hours = WorkHour::where('category','=',Auth::user()->position->work_hours)->where('group_id','=',Auth::user()->group_id)->get();

                if(count($work_hours) > 0) {
                    foreach($work_hours as $key=>$work_hour) {
                        // Get the entry time
                        $entry_at = date('Y-m-d H:i:s');
                        
                        // Set start date and end date
                        // if(date('G', strtotime($entry_at)) == 23 && date('G', strtotime($work_hour->start_at)) == 0){
                        //     $start_date = date('Y-m-d', strtotime("+1 day"));
                        //     $end_date = date('Y-m-d', strtotime("+1 day"));
                        // }
                        // elseif(date('G', strtotime($entry_at)) == 23 && date('G', strtotime($work_hour->end_at)) == 0){
                        //     $start_date = date('Y-m-d', strtotime($entry_at));
                        //     $end_date = date('Y-m-d', strtotime("+1 day"));
                        // }
                        // else{
                        //     $start_date = date('Y-m-d', strtotime($entry_at));
                        //     $end_date = date('Y-m-d', strtotime($entry_at));
                        // }

                        if(strtotime($work_hour->start_at) <= strtotime($work_hour->end_at)) {
                            $start_date = date('Y-m-d', strtotime($entry_at));
                            $end_date = date('Y-m-d', strtotime($entry_at));
                        }
                        else {
                            $start_date = date('Y-m-d', strtotime($entry_at));
                            $end_date = date('Y-m-d', strtotime("+1 day"));
                        }

                        $start_time = new \DateTime($start_date.' '.$work_hour->start_at);
                        $end_time = new \DateTime($end_date.' '.$work_hour->end_at);

                        // Set the attendance status
                        if(strtotime($entry_at) >= (strtotime($start_time->format('Y-m-d H:i:s')) - 3600) && strtotime($entry_at) <= (strtotime($end_time->format('Y-m-d H:i:s')) + 3600))
                            $work_hours[$key]->status = 1;
                        else
                            $work_hours[$key]->status = 0;
                    }
                }
            }
            else{
                // Display attendance (exit)
                $work_hours = $is_entry;
            }

            // View
            return view('member/index', [
                'is_entry' => $is_entry,
                'work_hours' => $work_hours,
            ]);
        }
    }
}