<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageFeatures;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_type' => 'required|string',
            'max_users_allowed' => 'required|integer',
            'max_requests_allowed' => 'required|integer',
            'max_responses_allowed' => 'required|integer',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable',
            'features.*' => 'nullable|string|max:255',
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        // Create package
        $package = Package::create($validated);

        // Store features
        if ($request->filled('features')) {
            foreach ($request->features as $feature) {
                if (!empty($feature)) {
                    PackageFeatures::create([
                        'package_id' => $package->id,
                        'feature_description' => $feature,
                    ]);
                }
            }
        }

        return redirect()->route('packages')->with('success', 'Package created successfully');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_type' => 'required|string',
            'max_users_allowed' => 'required|integer',
            'max_requests_allowed' => 'required|integer',
            'max_responses_allowed' => 'required|integer',
            'description' => 'nullable|string|max:255',
            'status' => 'nullable',
            'features.*' => 'nullable|string|max:255',
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        // Update package
        $package->update($validated);

        // Update features (delete old, add new)
        PackageFeatures::where('package_id', $package->id)->delete();

        if ($request->filled('features')) {
            foreach ($request->features as $feature) {
                if (!empty($feature)) {
                    PackageFeatures::create([
                        'package_id' => $package->id,
                        'feature_description' => $feature,
                    ]);
                }
            }
        }

        return redirect()->route('packages')->with('success', 'Package updated successfully');
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