<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ajifatur\Helpers\Date;
use App\Models\Attendance;
use App\Models\Absent;
use App\Models\User;
use App\Models\Group;
use App\Models\WorkHour;

class AttendanceController extends Controller
{
    /**
     * Do the entry absence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function entry(Request $request)
    {
        // Get the work hour
        $work_hour = WorkHour::find($request->id);
		
		// Entry at
		$entry_at = date('Y-m-d H:i:s');
        
        // If start_at and end_at are still at the same day
        if(strtotime($work_hour->start_at) <= strtotime($work_hour->end_at)) {
            $date = date('Y-m-d', strtotime($entry_at));
        }
        // If start_at and end_at are at the different day
        else {
            // If the user attends at 1 hour before work time
            if(date('G', strtotime($entry_at)) >= (date('G', strtotime($work_hour->start_at)) - 1)) {
                $date = date('Y-m-d', strtotime("+1 day"));
            }
            // If the user attends at 1 hour after work time
            elseif(date('G', strtotime($entry_at)) <= (date('G', strtotime($work_hour->end_at)) + 1)) {
                $date = date('Y-m-d', strtotime($entry_at));
            }
        }
		
		// Check absence
		$check = Attendance::where('user_id','=',Auth::user()->id)->where('workhour_id','=',$request->id)->where('office_id','=',Auth::user()->office_id)->where('date','=',$date)->whereTime('entry_at','>=',date('H:i', strtotime($entry_at)).":00")->whereTime('entry_at','<=',date('H:i', strtotime($entry_at)).":59")->first();

        // Entry absence
		if(!$check) {
			$attendance = new Attendance;
			$attendance->user_id = Auth::user()->id;
			$attendance->workhour_id = $request->id;
			$attendance->office_id = Auth::user()->office_id;
			$attendance->start_at = $work_hour->start_at;
			$attendance->end_at = $work_hour->end_at;
			$attendance->date = $date;
			$attendance->entry_at = $entry_at;
			$attendance->exit_at = null;
            $attendance->late = '';
			$attendance->save();
		}

        // Redirect
        return redirect()->route('member.dashboard')->with(['message' => 'Berhasil melakukan absensi masuk.']);
    }

    /**
     * Do the exit absence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exit(Request $request)
    {
        // Get the attendance
        $attendance = Attendance::find($request->id);
        $attendance->exit_at = date('Y-m-d H:i:s');
        $attendance->save();

        // Redirect
        return redirect()->route('member.dashboard')->with(['message' => 'Berhasil melakukan absensi keluar.']);
    }
}
