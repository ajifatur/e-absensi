<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;
use App\Models\Office;

class GroupController extends Controller
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
            // Get offices by the group
            $offices = Office::where('group_id','=',$request->query('group'))->get();

            // Return
            return response()->json($offices);
        }

        // Get groups
        if(Auth::user()->role == role('super-admin'))
            $groups = Group::all();
        elseif(Auth::user()->role == role('admin'))
            $groups = Group::where('id','=',Auth::user()->group_id)->get();
        elseif(Auth::user()->role == role('manager'))
            abort(403);

        // View
        return view('admin/group/index', [
            'groups' => $groups
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // View
        return view('admin/group/create');
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
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Save the group
            $group = new Group;
            $group->name = $request->name;
            $group->save();

            // Save the Head Office
            $office = new Office;
            $office->group_id = $group->id;
            $office->name = 'Head Office';
            $office->save();

            // Redirect
            return redirect()->route('admin.group.index')->with(['message' => 'Berhasil menambah data.']);
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
        // Get the group
        $group = Group::findOrFail($id);

        // View
        return view('admin/group/detail', [
            'group' => $group,
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
        // Get the group
        $group = Group::findOrFail($id);

        // View
        return view('admin/group/edit', [
            'group' => $group,
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
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the group
            $group = Group::find($request->id);
            $group->name = $request->name;
            $group->save();

            // Redirect
            return redirect()->route('admin.group.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the group
        $group = Group::find($request->id);

        // Delete the group
        $group->delete();

        // Redirect
        return redirect()->route('admin.group.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}