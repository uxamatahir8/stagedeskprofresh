<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function index()
    {

        $title = 'Settings';

        $settings = Settings::loadAll();

        return view('dashboard.pages.settings.index', compact('title', 'settings'));
    }
}
