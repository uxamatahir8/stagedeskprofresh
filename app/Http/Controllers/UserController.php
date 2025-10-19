<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Company;
use App\Models\Countries;
use App\Models\Role;
use App\Models\States;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = 'Users';

        $user = Auth::user();
        $users = $user->role->role_key === 'master_admin'
            ? User::all()
            : User::companyUsers()->get();

        return view('dashboard.pages.users.index', compact('title', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $title = 'Create User';
        $roles = Role::all();
        $companies = Company::all();
        $mode = 'create';
        $countries = Countries::all();

        return view('dashboard.pages.users.manage', compact('title', 'roles', 'mode', 'companies', 'countries'));
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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }


    public function getStates($country_id)
    {
        $states = States::where('country_id', $country_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
        return response()->json($states);
    }

    public function getCities($state_id)
    {
        $cities = Cities::where('state_id', $state_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
        return response()->json($cities);
    }
}
