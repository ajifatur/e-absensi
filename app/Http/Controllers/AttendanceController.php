<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ajifatur\Helpers\Date;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Group;
use App\Models\WorkHour;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == role('super-admin')){
            // Set params
            $group = $request->query('group') != null ? $request->query('group') : 0;
            $office = $request->query('office') != null ? $request->query('office') : 0;
            $t1 = $request->query('t1') != null ? Date::change($request->query('t1')) : date('Y-m-d');
            $t2 = $request->query('t2') != null ? Date::change($request->query('t2')) : date('Y-m-d');

            // Get attendances
            if($group != 0 && $office != 0)
                $attendances = Attendance::whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->whereHas('user', function (Builder $query) use ($group, $office) {
                    return $query->where('group_id','=',$group)->where('office_id','=',$office);
                })->get();
            elseif($group != 0 && $office == 0)
                $attendances = Attendance::whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->whereHas('user', function (Builder $query) use ($group) {
                    return $query->where('group_id','=',$group);
                })->get();
            else
                $attendances = Attendance::whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->orderBy('date','asc')->orderBy('start_at','asc')->get();

            // Get groups
            $groups = Group::all();

            // View
            return view('admin/attendance/index', [
                'attendances' => $attendances,
                'groups' => $groups,
            ]);
        }
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager')){
            // Set params
            $group = Auth::user()->group_id;
            $office = $request->query('office') != null ? $request->query('office') : 0;
            $t1 = $request->query('t1') != null ? Date::change($request->query('t1')) : date('Y-m-d');
            $t2 = $request->query('t2') != null ? Date::change($request->query('t2')) : date('Y-m-d');

            // Get attendances
            if($office != 0)
                $attendances = Attendance::whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->whereHas('user', function (Builder $query) use ($group, $office) {
                    return $query->where('group_id','=',$group)->where('office_id','=',$office);
                })->get();
            else
                $attendances = Attendance::whereDate('date','>=',$t1)->whereDate('date','<=',$t2)->whereHas('user', function (Builder $query) use ($group) {
                    return $query->where('group_id','=',$group);
                })->get();

            // View
            return view('admin/attendance/index', [
                'attendances' => $attendances,
            ]);
        }
    }

    /**
     * Display a listing of the resource (summary).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function summary(Request $request)
    {
        if(Auth::user()->role == role('super-admin')){
            // Set default date
            $dt1 = date('m') > 1 ? date('Y-m-d', strtotime(date('Y').'-'.(date('m')-1).'-24')) : date('Y-m-d', strtotime((date('Y')-1).'-12-24'));
            $dt2 = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-23'));

            // Set params
            $group = $request->query('group') != null ? $request->query('group') : 0;
            $office = $request->query('office') != null ? $request->query('office') : 0;
            $t1 = $request->query('t1') != null ? Date::change($request->query('t1')) : $dt1;
            $t2 = $request->query('t2') != null ? Date::change($request->query('t2')) : $dt2;

            // Get attendances
            if($group != 0 && $office != 0)
                $users = User::where('role','=',role('member'))->where('end_date','=',null)->where('group_id','=',$group)->where('office_id','=',$office)->get();
            elseif($group != 0 && $office == 0)
                $users = User::where('role','=',role('member'))->where('end_date','=',null)->where('group_id','=',$group)->get();
            else
                $users = User::where('role','=',role('member'))->where('end_date','=',null)->get();

            // Set attendances
            if(count($users) > 0) {
                foreach($users as $key=>$user) {
                    // Get attendances
                    $attendances = Attendance::where('user_id','=',$user->id)->where('date','>=',$dt1)->where('date','<=',$dt2)->get();

                    // Count late
                    $late = 0;
                    foreach($attendances as $attendance) {
                        if(strtotime($attendance->entry_at) >= strtotime(date('Y-m-d', strtotime($attendance->entry_at)).' '.$attendance->start_at) + 60) $late++;
                    }

                    // Set
                    $users[$key]->present = $attendances->count();
                    $users[$key]->late = $late;
                }
            }

            // Get groups
            $groups = Group::all();

            // View
            return view('admin/attendance/summary', [
                'groups' => $groups,
                'users' => $users,
                't1' => $t1,
                't2' => $t2,
            ]);
        }
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager')) {
            // Set default date
            $dt1 = date('m') > 1 ? date('Y-m-d', strtotime(date('Y').'-'.(date('m')-1).'-24')) : date('Y-m-d', strtotime((date('Y')-1).'-12-24'));
            $dt2 = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-23'));

            // Set params
            $group = Auth::user()->group_id;
            $office = $request->query('office') != null ? $request->query('office') : 0;
            $t1 = $request->query('t1') != null ? Date::change($request->query('t1')) : $dt1;
            $t2 = $request->query('t2') != null ? Date::change($request->query('t2')) : $dt2;

            // Get users
            if($office != 0)
                $users = User::where('role','=',role('member'))->where('end_date','=',null)->where('group_id','=',$group)->where('office_id','=',$office)->get();
            else
                $users = User::where('role','=',role('member'))->where('end_date','=',null)->where('group_id','=',$group)->get();

            // Set attendances
            if(count($users) > 0) {
                foreach($users as $key=>$user) {
                    $attendance = Attendance::where('user_id','=',$user->id)->where('date','>=',$dt1)->where('date','<=',$dt2)->count();
                    $users[$key]->attendance = $attendance;
                }
            }

            // View
            return view('admin/attendance/summary', [
                'users' => $users,
                't1' => $t1,
                't2' => $t2,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get groups
        $groups = Group::all();

        // View
        return view('admin/attendance/create', [
            'groups' => $groups
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'group_id' => Auth::user()->role == role('super-admin') ? 'required' : '',
            'office_id' => 'required',
            'user_id' => 'required',
            'workhour_id' => 'required',
            'date' => 'required',
            'entry_at.*' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Get the work hour
            $work_hour = WorkHour::find($request->workhour_id);

            // Save the attendance
            $attendance = new Attendance;
            $attendance->office_id = $request->office_id;
            $attendance->user_id = $request->user_id;
            $attendance->workhour_id = $request->workhour_id;
            $attendance->start_at = $work_hour->start_at;
            $attendance->end_at = $work_hour->end_at;
            $attendance->date = Date::change($request->date);
            $attendance->entry_at = Date::change($request->entry_at[0]).' '.$request->entry_at[1].':00';
            $attendance->exit_at = $request->exit_at[0] && $request->exit_at[1] != '' ? Date::change($request->exit_at[0]).' '.$request->exit_at[1].':00' : null;
            $attendance->entry_status = 0;
            $attendance->exit_status = 0;
            $attendance->save();

            // Redirect
            return redirect()->route('admin.attendance.index')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id = null)
    {
        // Set default date
        $dt1 = date('m') > 1 ? date('Y-m-d', strtotime(date('Y').'-'.(date('m')-1).'-24')) : date('Y-m-d', strtotime((date('Y')-1).'-12-24'));
        $dt2 = date('Y-m-d', strtotime(date('Y').'-'.date('m').'-23'));

        if(Auth::user()->role != role('member')) {
            // Get the user
            $user = User::findOrFail($id);

            // Get attendances
            $attendances = Attendance::where('user_id','=',$user->id)->whereDate('date','>=',$dt1)->whereDate('date','<=',$dt2)->orderBy('date','asc')->get();

            // View
            return view('admin/attendance/detail', [
                'user' => $user,
                'attendances' => $attendances,
                'dt1' => $dt1,
                'dt2' => $dt2,
            ]);
        }
        else {
            // Get the user
            $user = User::findOrFail(Auth::user()->id);

            // Get attendances
            $attendances = Attendance::where('user_id','=',$user->id)->whereDate('date','>=',$dt1)->whereDate('date','<=',$dt2)->orderBy('date','asc')->get();

            // View
            return view('member/attendance/detail', [
                'user' => $user,
                'attendances' => $attendances,
                'dt1' => $dt1,
                'dt2' => $dt2,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get the attendance
        $attendance = Attendance::findOrFail($id);

        // Get work hours
        $work_hours = WorkHour::where('office_id','=',$attendance->user->office_id)->where('position_id','=',$attendance->user->position_id)->get();

        // View
        return view('admin/attendance/edit', [
            'attendance' => $attendance,
            'work_hours' => $work_hours,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'workhour_id' => 'required',
            'date' => 'required',
            'entry_at.*' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Get the work hour
            $work_hour = WorkHour::find($request->workhour_id);

            // Update the attendance
            $attendance = Attendance::find($request->id);
            $attendance->workhour_id = $request->workhour_id;
            $attendance->start_at = $work_hour->start_at;
            $attendance->end_at = $work_hour->end_at;
            $attendance->date = Date::change($request->date);
            $attendance->entry_at = Date::change($request->entry_at[0]).' '.$request->entry_at[1].':00';
            $attendance->exit_at = $request->exit_at[0] && $request->exit_at[1] != '' ? Date::change($request->exit_at[0]).' '.$request->exit_at[1].':00' : null;
            $attendance->save();

            // Redirect
            return redirect()->route('admin.attendance.index')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Get the attendance
        $attendance = Attendance::findOrFail($request->id);

        // Delete the attendance
        $attendance->delete();

        // Redirect
        return redirect()->route('admin.attendance.index')->with(['message' => 'Berhasil menghapus data.']);
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

        // Entry absence
        $attendance = new Attendance;
        $attendance->user_id = Auth::user()->id;
        $attendance->workhour_id = $request->id;
        $attendance->office_id = Auth::user()->office_id;
		$attendance->start_at = $work_hour->start_at;
		$attendance->end_at = $work_hour->end_at;
        $attendance->date = $date;
        $attendance->entry_at = $entry_at;
        $attendance->exit_at = null;
        $attendance->entry_status = 0;
        $attendance->exit_status = 0;
        $attendance->save();

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