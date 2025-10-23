<?php

namespace App\Http\Controllers;

use App\Models\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    //
    public function index()
    {
        $title = 'Testimonials';
        $testimonials = Testimonials::all();

        return view('dashboard.pages.testimonials.index', compact('title', 'testimonials'));
    }

    public function create()
    {
        $title = 'Create Testimonial';
        $mode = 'create';
        return view('dashboard.pages.testimonials.manage', compact('title', 'mode'));
    }


    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'designation'  => 'nullable|string|max:255',
            'testimonial'  => 'required|string',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        Testimonials::create($validated);

        return redirect()->route('testimonials')->with('success', 'Testimonial added successfully.');
    }


    public function edit(Testimonials $testimonial)
    {
        $title = 'Edit Testimonial';
        $mode = 'edit';

        return view('dashboard.pages.testimonials.manage', compact('title', 'mode', 'testimonial'));
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(Request $request, $id)
    {
        $testimonial = Testimonials::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'designation'  => 'nullable|string|max:255',
            'testimonial'  => 'required|string',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Remove old avatar if requested
        if ($request->has('remove_avatar') && $testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
            $validated['avatar'] = null;
        }

        // Handle new avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old one if exists
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $testimonial->update($validated);

        return redirect()->route('testimonials')->with('success', 'Testimonial updated successfully.');
    }

    /**
     * Remove the specified testimonial from storage (soft delete).
     */
    public function destroy($id)
    {
        $testimonial = Testimonials::findOrFail($id);

        // Optionally delete avatar file
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        return redirect()->route('testimonials')->with('success', 'Testimonial deleted successfully.');
    }
}