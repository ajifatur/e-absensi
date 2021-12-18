<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // View
        return view('auth/login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:6',
            'password' => 'required|string|min:6',
        ]);

        // Check login type
        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Set credentials
        $credentials = [
			$loginType => $request->username,
			'password' => $request->password,
            'role_id' => role('member')
		];

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('member.dashboard');
        }

        return back()->withErrors([
            'message' => 'Username atau password yang dimasukkan tidak tersedia.',
        ]);
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect()->route('auth.login');
    }
}