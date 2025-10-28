<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
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

    public function blogs($categorySlug = null)
    {
        $title = 'Insights & Updates';
        $query = Blog::with('category');

        $category = null;

        if ($categorySlug) {
            // Find category by slug
            $category = BlogCategory::where('name', $categorySlug)
                ->orWhereRaw('LOWER(name) = ?', [strtolower($categorySlug)])
                ->firstOrFail();

            // Filter blogs by that category
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('id', $category->id);
            });
        }

        $blogs = $query->paginate(6);

        return view('blogs', compact('title', 'blogs', 'category'));
    }
}
