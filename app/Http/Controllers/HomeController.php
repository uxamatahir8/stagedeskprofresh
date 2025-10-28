<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Package;
use App\Models\Testimonials;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $title = 'Home';

        $monthly_packages = Package::where('duration_type', 'monthly')->get();
        $yearly_packages = Package::where('duration_type', 'yearly')->get();

        $blogs = Blog::with('category')->where('status', 'published')->get();

        $testimonials = Testimonials::all();

        return view('website', compact('title', 'monthly_packages', 'yearly_packages', 'blogs', 'testimonials'));
    }

    public function blogs()
    {
        $title = 'Insights & Updates';
        $blogs = Blog::with('category')->paginate(6); // show 6 per page (adjust as needed)
        return view('blogs', compact('title', 'blogs'));
    }
}
