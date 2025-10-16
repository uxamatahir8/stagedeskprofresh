<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Package;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = 'Companies List';
        $companies = Company::all();

        return view('dashboard.pages.companies.index', compact('title', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = 'Create Company';
        $mode = 'create';

        return view('dashboard.pages.companies.manage', compact('title', 'mode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'website' => 'nullable|url',
            'kvk_number' => 'nullable|string|max:50',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'status' => 'nullable|string|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'address' => 'nullable|string|max:255'
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        // ✅ Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('company_logos', 'public');
        }

        $company = Company::create($validated);

        // ✅ Store social links
        foreach ($request->input('social_links', []) as $handle => $url) {
            if (!empty($url)) {
                SocialLink::create([
                    'handle' => $handle,
                    'url' => $url,
                    'company_id' => $company->id,
                    'user_id' => null,
                ]);
            }
        }

        return redirect()->route('companies')->with('success', 'Company created successfully, you can add subscription to the comapny by clicking gear icon');
    }



    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
        $title = $company->name;

        return view('dashboard.pages.companies.show', compact('title', 'company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
        $title = 'Edit Company';
        $mode = 'edit';

        return view('dashboard.pages.companies.manage', compact('title', 'mode', 'company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'website' => 'nullable|url',
            'kvk_number' => 'nullable|string|max:50',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email',
            'status' => 'nullable|string|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'address' => 'nullable|string|max:255'
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('company_logos', 'public');
        }

        $company->update($validated);

        // ✅ Sync social links
        $socialLinks = $request->input('social_links', []);
        foreach ($socialLinks as $handle => $url) {
            $link = $company->socialLinks()->where('handle', $handle)->first();

            if (!empty($url)) {
                if ($link) {
                    $link->update(['url' => $url]);
                } else {
                    SocialLink::create([
                        'handle' => $handle,
                        'url' => $url,
                        'company_id' => $company->id,
                        'user_id' => null,
                    ]);
                }
            } elseif ($link) {
                $link->delete();
            }
        }

        return redirect()->route('companies')->with('success', 'Company updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
        // Delete associated social links
        $company->socialLinks()->delete();

        // Delete logo file if exists
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }

        // Delete the company
        $company->delete();

        return redirect()->route('companies')->with('success', 'Company and related social links deleted successfully.');
    }
}