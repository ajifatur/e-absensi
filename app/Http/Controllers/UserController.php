<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\Date;
use App\Models\User;
use App\Models\Role;
use App\Models\Group;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get users
        if(Auth::user()->role == role('super-admin'))
            $users = User::orderBy('role','asc')->get();
        elseif(Auth::user()->role == role('admin'))
            $users = User::where('group_id','=',Auth::user()->group_id)->orderBy('role','asc')->get();

        // View
        return view('admin/user/index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get roles
        $roles = Role::where('code','!=','super-admin')->get();

        // Get groups
        $groups = Group::all();

        // View
        return view('admin/user/create', [
            'roles' => $roles,
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
            'role' => 'required',
            'group_id' => Auth::user()->role == role('super-admin') ? 'required' : '',
            'office_id' => $request->role != '' && $request->role != role('admin') ? 'required' : '',
            'position_id' => $request->role != '' && $request->role != role('admin') ? 'required' : '',
            'name' => 'required|max:200',
            'birthdate' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'start_date' => 'required',
            'phone_number' => 'required|numeric',
            'email' => 'required|email|unique:users',
            'username' => 'required|alpha_dash|min:4|unique:users',
            'password' => 'required|min:6',
            'status' => 'required'
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Save the user
            $user = new User;
            $user->role = $request->role;
            $user->group_id = Auth::user()->role == role('super-admin') ? $request->group_id : Auth::user()->group_id;
            $user->office_id = $request->office_id;
            $user->position_id = $request->position_id;
            $user->name = $request->name;
            $user->birthdate = Date::change($request->birthdate);
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->start_date = Date::change($request->start_date);
            $user->phone_number = $request->phone_number;
            $user->latest_education = $request->latest_education;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->status = $request->status;
            $user->last_visit = null;
            $user->save();

            // Redirect
            return redirect()->route('admin.user.index')->with(['message' => 'Berhasil menambah data.']);
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
        // Get the user
        $user = User::findOrFail($id);

        // View
        return view('admin/user/detail', [
            'user' => $user,
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
        // Get the user
        $user = User::findOrFail($id);

        // Get roles
        $roles = Role::where('code','!=','super-admin')->get();

        // Get groups
        $groups = Group::all();

        // View
        return view('admin/user/edit', [
            'user' => $user,
            'roles' => $roles,
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
            'office_id' => $request->role != role('admin') ? 'required' : '',
            'position_id' => $request->role != role('admin') ? 'required' : '',
            'name' => 'required|max:200',
            'birthdate' => 'required',
            'gender' => 'required',
            'phone_number' => 'required|numeric',
            'email' => [
                'required', 'email', Rule::unique('users')->ignore($request->id, 'id')
            ],
            'username' => [
                'required', 'alpha_dash', 'min:4', Rule::unique('users')->ignore($request->id, 'id')
            ],
            'password' => $request->password != '' ? 'min:6' : '',
            'role' => 'required',
            'status' => 'required'
        ]);
        
        // Check errors
        if($validator->fails()){
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else{
            // Update the user
            $user = User::find($request->id);
            $user->role = $request->role;
            $user->office_id = $request->office_id;
            $user->position_id = $request->position_id;
            $user->name = $request->name;
            $user->birthdate = Date::change($request->birthdate);
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->start_date = Date::change($request->start_date);
            $user->phone_number = $request->phone_number;
            $user->latest_education = $request->latest_education;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
            $user->status = $request->status;
            $user->save();

            // Redirect
            return redirect()->route('admin.user.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        // Get the user
        $user = User::find($request->id);

        // Delete the user
        $user->delete();

        // Redirect
        return redirect()->route('admin.user.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}
