<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        $title = 'Dashboard';
        return view('dashboard.pages.index', compact('title'));
    }
}
