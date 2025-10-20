<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //

    public function index()
    {
        $title = 'Categories';
        $categories = BlogCategory::all();
        $mode = 'create';

        return view('dashboard.pages.categories.index', compact('title', 'categories', 'mode'));
    }

    public function edit($category)
    {
        $title = 'Edit Category';
        $mode = 'edit';
        $category = BlogCategory::find($category);
        $categories = BlogCategory::all();
        return view('dashboard.pages.categories.index', compact('title', 'mode', 'category', 'categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        BlogCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('blog-categories')->with('success', 'Category created successfully');
    }

    public function update(Request $request, $category)
    {
        $request->validate([
            'name' => 'required',
        ]);

        BlogCategory::find($category)->update([
            'name' => $request->name,
        ]);

        return redirect()->route('blog-categories')->with('success', 'Category updated successfully');
    }

    public function destroy($category)
    {
        BlogCategory::find($category)->delete();
        return redirect()->route('blog-categories')->with('success', 'Category deleted successfully');
    }
}
