<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SalaryCategory;
use App\Models\Group;

class SalaryCategoryController extends Controller
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
            // Get salary categories by the group
            $salary_categories = SalaryCategory::where('group_id','=',$request->query('group'))->get();

            // Return
            return response()->json($salary_categories);
        }

        // Get salary categories
        if(Auth::user()->role == role('super-admin'))
            $salary_categories = SalaryCategory::has('group')->get();
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
            $salary_categories = SalaryCategory::has('group')->where('group_id','=',Auth::user()->group_id)->get();

        // View
        return view('admin/salary-category/index', [
            'salary_categories' => $salary_categories
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
        return view('admin/salary-category/create', [
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
            'type_id' => 'required',
            'group_id' => Auth::user()->role == role('super-admin') ? 'required' : '',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Save the salary category
            $salary_category = new SalaryCategory;
            $salary_category->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $salary_category->name = $request->name;
            $salary_category->type_id = $request->type_id;
            $salary_category->save();

            // Redirect
            return redirect()->route('admin.salary-category.index')->with(['message' => 'Berhasil menambah data.']);
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
        // Get the salary category
        $salary_category = SalaryCategory::findOrFail($id);

        // Get groups
        $groups = Group::all();

        // View
        return view('admin/salary-category/edit', [
            'salary_category' => $salary_category,
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
            'type_id' => 'required',
            'group_id' => Auth::user()->role == role('super-admin') ? 'required' : '',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the salary category
            $salary_category = SalaryCategory::find($request->id);
            $salary_category->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $salary_category->name = $request->name;
            $salary_category->type_id = $request->type_id;
            $salary_category->save();

            // Redirect
            return redirect()->route('admin.salary-category.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the salary category
        $salary_category = SalaryCategory::findOrFail($request->id);

        // Delete the salary category
        $salary_category->delete();

        // Redirect
        return redirect()->route('admin.salary-category.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}