<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SalaryIndicator;
use App\Models\SalaryCategory;
use App\Models\Group;

class SalaryIndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get salary indicators
        if(Auth::user()->role == role('super-admin'))
            $salary_indicators = SalaryIndicator::has('group')->get();
        elseif(Auth::user()->role == role('admin') || Auth::user()->role == role('manager'))
            $salary_indicators = SalaryIndicator::has('group')->where('group_id','=',Auth::user()->group_id)->get();

        // View
        return view('admin/salary-indicator/index', [
            'salary_indicators' => $salary_indicators
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
        return view('admin/salary-indicator/create', [
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
            'category_id' => 'required',
            'lower_range' => 'required',
            'amount' => 'required|numeric',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Save the salary indicator
            $salary_indicator = new SalaryIndicator;
            $salary_indicator->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $salary_indicator->category_id = $request->category_id;
            $salary_indicator->lower_range = str_replace(',', '.', $request->lower_range);
            $salary_indicator->upper_range = $request->upper_range != '' ? str_replace(',', '.', $request->upper_range) : null;
            $salary_indicator->amount = $request->amount;
            $salary_indicator->save();

            // Redirect
            return redirect()->route('admin.salary-indicator.index')->with(['message' => 'Berhasil menambah data.']);
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
        // Get the salary indicator
        $salary_indicator = SalaryIndicator::findOrFail($id);

        // Get groups
        $groups = Group::all();

        // View
        return view('admin/salary-indicator/edit', [
            'salary_indicator' => $salary_indicator,
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
            'category_id' => 'required',
            'lower_range' => 'required',
            'amount' => 'required|numeric',
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the salary indicator
            $salary_indicator = SalaryIndicator::find($request->id);
            $salary_indicator->category_id = $request->category_id;
            $salary_indicator->lower_range = str_replace(',', '.', $request->lower_range);
            $salary_indicator->upper_range = $request->upper_range != '' ? str_replace(',', '.', $request->upper_range) : null;
            $salary_indicator->amount = $request->amount;
            $salary_indicator->save();

            // Redirect
            return redirect()->route('admin.salary-indicator.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the salary indicator
        $salary_indicator = SalaryIndicator::findOrFail($request->id);

        // Delete the salary indicator
        $salary_indicator->delete();

        // Redirect
        return redirect()->route('admin.salary-indicator.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}