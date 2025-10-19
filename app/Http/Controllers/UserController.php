<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Company;
use App\Models\Countries;
use App\Models\Role;
use App\Models\States;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'about' => 'nullable|string|max:500',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'company_id' => 'nullable|exists:companies,id',
            'status' => 'nullable|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
        ]);

        // ✅ Create user
        $user = User::create([
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => $validated['company_id'] ?? null,
            'status' => $request->status === 'active' ? 'active' : 'inactive',
        ]);

        // ✅ Handle logo upload
        $profileImagePath = null;
        if ($request->hasFile('logo')) {
            $profileImagePath = $request->file('logo')->store('profile_images', 'public');
        }

        // ✅ Create or Update Profile
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'zipcode' => $validated['zipcode'],
                'about' => $validated['about'] ?? null,
                'country_id' => $validated['country_id'],
                'state_id' => $validated['state_id'],
                'city_id' => $validated['city_id'],
                'profile_image' => $profileImagePath,
            ]
        );

        return redirect()->route('users')->with('success', 'User and profile saved successfully.');
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
        $title = 'Edit User';
        $roles = Role::all();
        $companies = Company::all();
        $mode = 'edit';
        $countries = Countries::all();

        return view('dashboard.pages.users.manage', compact('title', 'roles', 'mode', 'companies', 'user', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // ✅ Find user or fail
        $user = User::findOrFail($id);

        // ✅ Validation rules
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'min:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'zipcode' => 'required|string|max:10',
            'about' => 'nullable|string|max:500',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'company_id' => 'nullable|exists:companies,id',
            'status' => 'nullable|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
        ]);

        // ✅ Update user info
        $user->update([
            'role_id' => $validated['role_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_id' => $validated['company_id'] ?? null,
            'status' => $request->status === 'active' ? 'active' : 'inactive',
            'password' => !empty($validated['password'])
                ? Hash::make($validated['password'])
                : $user->password, // keep old password
        ]);

        // ✅ Handle new logo upload (and delete old one if replaced)
        $profileImagePath = $user->profile ? $user->profile->profile_image : null;

        if ($request->hasFile('logo')) {
            // delete old file if exists
            if ($profileImagePath && Storage::disk('public')->exists($profileImagePath)) {
                Storage::disk('public')->delete($profileImagePath);
            }
            $profileImagePath = $request->file('logo')->store('profile_images', 'public');
        }

        // ✅ Update or Create Profile
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'zipcode' => $validated['zipcode'],
                'about' => $validated['about'] ?? null,
                'country_id' => $validated['country_id'],
                'state_id' => $validated['state_id'],
                'city_id' => $validated['city_id'],
                'profile_image' => $profileImagePath,
            ]
        );

        return redirect()->route('users')->with('success', 'User and profile updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // ✅ Find user
        $user = User::findOrFail($id);

        // ✅ Start transaction for safety
        DB::beginTransaction();

        try {
            // ✅ Delete profile and image if exists
            if ($user->profile) {
                if ($user->profile->profile_image && Storage::disk('public')->exists($user->profile->profile_image)) {
                    Storage::disk('public')->delete($user->profile->profile_image);
                }
                $user->profile->delete();
            }

            // ✅ Delete user
            $user->delete();

            DB::commit();

            return redirect()->route('users')->with('success', 'User and profile deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('users')->with('error', 'Failed to delete user. Please try again.');
        }
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
