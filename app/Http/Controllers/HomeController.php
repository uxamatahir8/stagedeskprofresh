<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $title = 'Home - StageDesk Pro';
        return view('website', compact('title'));
    }
}
