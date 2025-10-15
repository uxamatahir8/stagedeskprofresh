<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login()
    {
        $title = 'Login';

        return view('auth.pages.login', compact('title'));
    }


    public function userLogin(Request $request)
    {
        // Validate input fields
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to authenticate user
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // prevent session fixation

            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email'); // keep entered email in input
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('dashboard');
    }
}