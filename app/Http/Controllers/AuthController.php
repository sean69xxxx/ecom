<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('lab.auth.register');
    }

    public function register(Request $request)
    {
        // LAB ONLY: intentionally missing validation and using weak password handling.
        $name = $request->input('name');
        $email = $request->input('email');
        $password = md5($request->input('password'));

        DB::statement(
            "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')"
        );

        return redirect('/login')->with('message', 'Registered. You can now log in.');
    }

    public function showLogin(Request $request)
    {
        // LAB ONLY: accepts a session id from the URL, demonstrating session fixation risk.
        if ($request->filled('sid')) {
            session()->setId($request->input('sid'));
        }

        return view('lab.auth.login');
    }

    public function login(Request $request)
    {
        // LAB ONLY: SQL injection vulnerability from string concatenation.
        $email = $request->input('email');
        $password = md5($request->input('password'));

        $users = DB::select(
            "SELECT * FROM users WHERE email = '$email' AND password = '$password' LIMIT 1"
        );

        if (count($users) === 0) {
            return back()->with('error', 'Invalid login.');
        }

        // LAB ONLY: no session regeneration after login.
        session([
            'insecure_user_id' => $users[0]->id,
            'insecure_user_name' => $users[0]->name,
        ]);

        return redirect('/transactions');
    }

    public function logout()
    {
        session()->forget(['insecure_user_id', 'insecure_user_name']);

        return redirect('/login');
    }
}
