<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('lab.auth.register');
    }

    public function register(Request $request)
    {
        // 1. Input Validation: Enforce strict rules and data types 
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', 
        ]);

        // 2. Prevent SQL Injection & Weak Hashing: Use Eloquent and bcrypt
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Securely hashes password
        ]);

        return redirect('/login')->with('message', 'Registered securely. You can now log in.');
    }

    public function showLogin()
    {
        // Removed the session fixation vulnerability entirely
        return view('lab.auth.login');
    }

    public function login(Request $request)
    {
        // 1. Input Validation 
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Secure Authentication
        if (Auth::attempt($credentials)) {
            // 3. Secure Session Management: Regenerate ID to prevent session fixation 
            $request->session()->regenerate();

            return redirect()->intended('/transactions');
        }

        // 4. Proper Error Handling: Generic message to limit information disclosure 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // 5. Secure Session Management: Invalidate and regenerate CSRF token 
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}