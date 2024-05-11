<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view('inicio');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/admin/login');
    }
}
