<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Position;
use App\Models\Group;

class PositionController extends Controller
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
            // Get positions by the group
            $positions = Position::where('group_id','=',$request->query('group'))->get();

            // Return
            return response()->json($positions);
        }

        // Get positions
        if(Auth::user()->role == role('super-admin'))
            $positions = Position::has('group')->get();
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
            $positions = Position::has('group')->where('group_id','=',Auth::user()->group_id)->get();

        // View
        return view('admin/position/index', [
            'positions' => $positions
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
        return view('admin/position/create', [
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
            // 'work_hours' => 'required'
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Save the position
            $position = new Position;
            $position->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $position->name = $request->name;
            $position->work_hours = 0;
            $position->save();

            // Redirect
            return redirect()->route('admin.position.index')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        // Get the position
        $position = Position::findOrFail($id);

        // View
        return view('admin/position/detail', [
            'position' => $position
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Get the position
        $position = Position::findOrFail($id);

        // Get groups
        $groups = Group::all();

        // View
        return view('admin/position/edit', [
            'position' => $position,
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
            'group_id' => Auth::user()->role == role('super-admin') ? 'required' : '',
            // 'work_hours' => 'required'
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the position
            $position = Position::find($request->id);
            $position->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $position->name = $request->name;
            // $position->work_hours = $request->work_hours;
            $position->save();

            // Redirect
            return redirect()->route('admin.position.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the position
        $position = Position::find($request->id);

        // Delete the position
        $position->delete();

        // Redirect
        return redirect()->route('admin.position.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}