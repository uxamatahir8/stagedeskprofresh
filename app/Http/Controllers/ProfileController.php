<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the profile page (for master_admin, company_admin, and any role without a dedicated profile).
     */
    public function index()
    {
        $user = Auth::user()->load(['profile', 'role', 'company']);
        $title = 'My Profile';

        return view('dashboard.pages.profile.index', compact('title', 'user'));
    }

    /**
     * Update profile (personal info and optional profile image).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'zipcode' => 'nullable|string|max:20',
            'about' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $profileData = [
            'phone' => $validated['phone'] ?? '',
            'address' => $validated['address'] ?? '',
            'zipcode' => $validated['zipcode'] ?? '',
            'about' => $validated['about'] ?? null,
        ];

        if ($request->hasFile('profile_image')) {
            $profile = $user->profile;
            if ($profile && $profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                Storage::disk('public')->delete($profile->profile_image);
            }
            $profileData['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($user->profile) {
            $user->profile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            UserProfile::create($profileData);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(10)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.required' => 'New password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
            'must_change_password' => false,
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
