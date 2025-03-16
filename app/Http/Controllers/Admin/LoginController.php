<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(){
        return view("admin.login.index");
    }
    public function login(Request $request) {
       $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        Auth::attempt($request->only('email', 'password'));

        if (Auth::check()) {
            return redirect('admin/dashboard');
        }
        return redirect()->back()->withInput()->with("error", "Email or Password is incorrect");

    }
    public function logout(){
        Auth::logout();
        return redirect('/');
    }


}
