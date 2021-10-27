<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Office;
use App\Models\Group;

class OfficeController extends Controller
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

        // Get offices
        if(Auth::user()->role == role('super-admin'))
            $offices = Office::has('group')->get();
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
            $offices = Office::has('group')->where('group_id','=',Auth::user()->group_id)->get();

        // View
        return view('admin/office/index', [
            'offices' => $offices
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
        return view('admin/office/create', [
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
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Save the office
            $office = new Office;
            $office->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $office->name = $request->name;
            $office->save();

            // Redirect
            return redirect()->route('admin.office.index')->with(['message' => 'Berhasil menambah data.']);
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
        // Get the office
        $office = Office::findOrFail($id);

        // View
        return view('admin/office/detail', [
            'office' => $office
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
        // Get the office
        $office = Office::findOrFail($id);

        // Get groups
        $groups = Group::all();

        // View
        return view('admin/office/edit', [
            'office' => $office,
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
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the office
            $office = Office::find($request->id);
            $office->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $office->name = $request->name;
            $office->save();

            // Redirect
            return redirect()->route('admin.office.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the office
        $office = Office::findOrFail($request->id);

        // Delete the office
        $office->delete();

        // Redirect
        return redirect()->route('admin.office.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}