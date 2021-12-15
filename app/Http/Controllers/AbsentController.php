<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ajifatur\Helpers\Date;
use App\Models\Absent;
use App\Models\Group;

class AbsentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get absents
        if(Auth::user()->role == role('super-admin'))
            $absents = Absent::has('user')->orderBy('date','desc')->get();
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager')) {
            $group = Auth::user()->group_id;
            $absents = Absent::has('user')->whereHas('user', function (Builder $query) use ($group) {
                return $query->where('group_id','=',$group);
            })->orderBy('date','desc')->get();
        }

        // View
        return view('admin/absent/index', [
            'absents' => $absents
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
        return view('admin/absent/create', [
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
            'date' => 'required',
            'category_id' => 'required',
            'note' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the absent
            $absent = new Absent;
            $absent->user_id = $request->user_id;
            $absent->category_id = $request->category_id;
            $absent->date = Date::change($request->date);
            $absent->note = $request->note;
            $absent->attachment = '';
            $absent->save();

            // Redirect
            return redirect()->route('admin.absent.index')->with(['message' => 'Berhasil menambah data.']);
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
        // Get the absent
        $absent = Absent::findOrFail($id);

        // View
        return view('admin/absent/edit', [
            'absent' => $absent,
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
            'date' => 'required',
            'category_id' => 'required',
            'note' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the absent
            $absent = Absent::find($request->id);
            $absent->category_id = $request->category_id;
            $absent->date = Date::change($request->date);
            $absent->note = $request->note;
            $absent->save();

            // Redirect
            return redirect()->route('admin.absent.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the absent
        $absent = Absent::find($request->id);

        // Delete the absent
        $absent->delete();

        // Redirect
        return redirect()->route('admin.absent.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}