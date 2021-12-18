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
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, $id = null)
    {
        // Set default date
        $dt1 = date('m') > 1 ? date('Y-m-d', strtotime(date('Y').'-'.(date('m')-1).'-24')) : date('Y-m-d', strtotime((date('Y')-1).'-12-24'));
        $dt2 = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-23'));

        // Set params
        $category = $request->query('category') != null ? $request->query('category') : 1;
        $workhour = $request->query('workhour') != null ? $request->query('workhour') : 0;
        $t1 = $request->query('t1') != null ? Date::change($request->query('t1')) : $dt1;
        $t2 = $request->query('t2') != null ? Date::change($request->query('t2')) : $dt2;

        if(Auth::user()->role_id != role('member')) {
            // Get the user
            $user = User::findOrFail($id);

            // Get the work hours
            $workhours = WorkHour::where('group_id','=',$user->group_id)->where('office_id','=',$user->office_id)->where('position_id','=',$user->position_id)->orderBy('name','asc')->get();

            // Get attendances
            if($workhour == 0)
                $attendances = Attendance::where('user_id','=',$user->id)->whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->orderBy('date','desc')->get();
            else
                $attendances = Attendance::where('user_id','=',$user->id)->where('workhour_id','=',$workhour)->whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->orderBy('date','desc')->get();

            // Count attendances
            $count[1] = $attendances->count();

            // Get late attendances
            $late = 0;
            foreach($attendances as $key=>$attendance) {
                $date = $attendance->start_at <= $attendance->end_at ? $attendance->date : date('Y-m-d', strtotime('-1 day', strtotime($attendance->date)));
                if(strtotime($attendance->entry_at) >= strtotime($date.' '.$attendance->start_at) + 60) $late++;
                if($category == 2) if(strtotime($attendance->entry_at) < strtotime($date.' '.$attendance->start_at) + 60) $attendances->forget($key);
            }

            // Count late attendances
            $count[2] = $late;

            // Get absents
            $absents1 = Absent::where('user_id','=',$user->id)->where('category_id','=',1)->where('date','>=',$t1)->where('date','<=',$t2)->orderBy('date','desc')->get();
            $absents2 = Absent::where('user_id','=',$user->id)->where('category_id','=',2)->where('date','>=',$t1)->where('date','<=',$t2)->orderBy('date','desc')->get();
            if($category == 3) $attendances = $absents1;
            if($category == 4) $attendances = $absents2;

            // Count absents
            $count[3] = count($absents1);
            $count[4] = count($absents2);

            // View
            return view('admin/attendance/detail', [
                'user' => $user,
                'workhours' => $workhours,
                'attendances' => $attendances,
                'category' => $category,
                't1' => $t1,
                't2' => $t2,
                'count' => $count
            ]);
        }
        else {
            // Get the user
            $user = User::findOrFail(Auth::user()->id);

            // Get attendances
            $attendances = Attendance::where('user_id','=',$user->id)->whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->orderBy('date','desc')->get();

            // View
            return view('member/attendance/detail', [
                'user' => $user,
                'attendances' => $attendances,
                't1' => $t1,
                't2' => $t2,
            ]);
        }
    }

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
