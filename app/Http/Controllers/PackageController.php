<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = 'Packages List';
        $packages = Package::all();

        return view('dashboard.pages.packages.index', compact('title', 'packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = 'Create Package';
        $mode = 'create';

        return view('dashboard.pages.packages.manage', compact('title', 'mode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'name' => 'required',
            'price' => 'required',
            'duration_type' => 'required',
            'max_users_allowed' => 'required',
            'max_requests_allowed' => 'required',
            'max_responses_allowed' => 'required',
            'description' => 'max:255',
            'status' => 'nullable'
        ]);

        $validate['status'] = $request->has('status') ? 'active' : 'inactive';


        // if validations failed
        if ($validate) {
            Package::create($validate);
            return redirect()->route('packages')->with('success', 'Package Created Successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        //
        $title = 'Edit Package';
        $mode = 'edit';

        return view('dashboard.pages.packages.manage', compact('title', 'package', 'mode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_type' => 'required|string',
            'max_users_allowed' => 'required|integer',
            'max_requests_allowed' => 'required|integer',
            'max_responses_allowed' => 'required|integer',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable',
        ]);

        // Handle checkbox (if unchecked, it won't exist in request)
        $validated['status'] = $request->has('status') ? 'active' : 'ianctive';
        if ($validated) {
            // Update package
            $package->update($validated);

            // Redirect with success message
            return redirect()->route('packages')->with('success', 'Package updated successfully');
        } else {
            // Redirect with error message
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        try {
            $package->delete();
            return redirect()->route('packages')->with('success', 'Package deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('packages')->with('error', 'Failed to delete the package. Please try again.');
        }
    }
}