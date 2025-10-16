<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Package;
use Illuminate\Http\Request;

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

        $packages = Package::all();

        return view('dashboard.pages.companies.manage', compact('title', 'mode', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}