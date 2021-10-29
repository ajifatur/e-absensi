<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\WorkHour;
use App\Models\Group;
use App\Models\User;

class WorkHourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            if($request->query('user') != null) {
                $user = User::find($request->query('user'));
                $work_hours = WorkHour::where('office_id','=',$user->office_id)->where('position_id','=',$user->position_id)->get();

                return response()->json($work_hours);
            }
        }

        // Get work hours
        if(Auth::user()->role == role('super-admin'))
            $work_hours = WorkHour::all();
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
            $work_hours = WorkHour::where('group_id','=',Auth::user()->group_id)->get();

        // View
        return view('admin/work-hour/index', [
            'work_hours' => $work_hours
        ]);
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
        return view('admin/work-hour/create', [
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
            'name' => 'required|max:255',
            'group_id' => Auth::user()->role == role('super-admin') ? 'required' : '',
            // 'category' => 'required',
            'office_id' => 'required',
            'position_id' => 'required',
            'quota' => 'required|numeric',
            'start_at' => 'required',
            'end_at' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Save the work_hour
            $work_hour = new WorkHour;
            $work_hour->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $work_hour->office_id = $request->office_id;
            $work_hour->position_id = $request->position_id;
            $work_hour->name = $request->name;
            $work_hour->category = 0;
            $work_hour->quota = $request->quota;
            $work_hour->start_at = $request->start_at.':00';
            $work_hour->end_at = $request->end_at.':00';
            $work_hour->save();

            // Redirect
            return redirect()->route('admin.work-hour.index')->with(['message' => 'Berhasil menambah data.']);
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
        // Get the work hour
        $work_hour = WorkHour::findOrFail($id);

        // Get groups
        $groups = Group::all();

        // View
        return view('admin/work-hour/edit', [
            'work_hour' => $work_hour,
            'groups' => $groups,
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
            'name' => 'required|max:255',
            // 'category' => 'required',
            'office_id' => 'required',
            'position_id' => 'required',
            'quota' => 'required|numeric',
            'start_at' => 'required',
            'end_at' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the work hour
            $work_hour = WorkHour::find($request->id);
            $work_hour->name = $request->name;
            // $work_hour->category = $request->category;
            $work_hour->office_id = $request->office_id;
            $work_hour->position_id = $request->position_id;
            $work_hour->quota = $request->quota;
            $work_hour->start_at = $request->start_at.':00';
            $work_hour->end_at = $request->end_at.':00';
            $work_hour->save();

            // Redirect
            return redirect()->route('admin.work-hour.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the work hour
        $work_hour = WorkHour::findOrFail($request->id);

        // Delete the work hour
        $work_hour->delete();

        // Redirect
        return redirect()->route('admin.work-hour.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}