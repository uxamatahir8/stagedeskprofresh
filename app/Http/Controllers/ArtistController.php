<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistController extends Controller
{
    public function index()
    {
        $title = 'Artists';
        $roleKey = Auth::user()->role->role_key;

        $artistsQuery = Artist::with(['company', 'user']);

        if ($roleKey === 'company_admin') {
            $artistsQuery->where('company_id', Auth::user()->company_id);
        } elseif ($roleKey === 'customer') {
            return abort(403, 'Unauthorized');
        }

        $artists = $artistsQuery->paginate(15);

        return view('dashboard.pages.artists.index', compact('title', 'artists'));
    }

    public function create(Request $request)
    {
        $title = 'Add Artist';
        $mode = 'create';

        $roleKey = Auth::user()->role->role_key;

        // Get preselected user if passed from user creation
        $preselectedUserId = $request->query('user_id');
        $preselectedUser = null;

        if ($preselectedUserId) {
            $preselectedUser = User::find($preselectedUserId);
        }

        if ($roleKey === 'company_admin') {
            $users = User::where('company_id', Auth::user()->company_id)
                ->whereHas('role', function ($q) {
                    $q->whereIn('role_key', ['artist', 'dj']);
                })
                ->whereDoesntHave('artist') // Only show users without artist profile
                ->get();
        } else {
            $users = User::whereHas('role', function ($q) {
                $q->whereIn('role_key', ['artist', 'dj']);
            })
            ->whereDoesntHave('artist') // Only show users without artist profile
            ->get();
        }

        $companies = $roleKey === 'company_admin'
            ? Company::where('id', Auth::user()->company_id)->get()
            : Company::all();

        return view('dashboard.pages.artists.manage', compact('title', 'mode', 'users', 'companies', 'preselectedUser'));
    }

    public function store(Request $request)
    {
        $roleKey = Auth::user()->role->role_key;

        $validated = $request->validate([
            'company_id'      => 'required|exists:companies,id',
            'user_id'         => 'required|exists:users,id|unique:artists,user_id',
            'stage_name'      => 'required|string|max:255',
            'experience_years'=> 'required|integer|min:0',
            'genres'          => 'required|string|max:255',
            'specialization'  => 'required|string|max:255',
            'bio'             => 'nullable|string|max:1000',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'user_id.unique' => 'This user already has an artist profile.',
        ]);

        // Company admin can only create artists for their company
        if ($roleKey === 'company_admin') {
            if ($validated['company_id'] !== Auth::user()->company_id) {
                abort(403, 'You can only create artists for your company');
            }
            // Double check the user belongs to their company
            $user = User::find($validated['user_id']);
            if ($user->company_id !== Auth::user()->company_id) {
                return back()->withInput()->with('error', 'Selected user must belong to your company');
            }
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
            $validated['image'] = $path;
        }

        $validated['rating'] = 0;

        Artist::create($validated);

        return redirect()->route('artists.index')->with('success', 'Artist profile created successfully.');
    }

    public function show(Artist $artist)
    {
        $roleKey = Auth::user()->role->role_key;

        // Company admin can only view artists from their company
        if ($roleKey === 'company_admin' && $artist->company_id !== Auth::user()->company_id) {
            abort(403, 'You can only view artists from your company');
        }

        $title = 'Artist Details';

        // Fetch artist stats
        $stats = [
            'total_bookings' => $artist->assignedBookings()->count(),
            'completed_bookings' => $artist->assignedBookings()->where('status', 'completed')->count(),
            'avg_rating' => $artist->reviews()->avg('rating') ?? 0,
            'total_earnings' => \DB::table('payments')
                ->whereIn('booking_requests_id', $artist->assignedBookings()->pluck('id'))
                ->where('status', 'completed')
                ->sum('amount'),
            'reviews_count' => $artist->reviews()->count(),
        ];

        // Recent bookings
        $recentBookings = $artist->assignedBookings()
            ->with(['eventType', 'company'])
            ->latest()
            ->take(5)
            ->get();

        // Recent reviews
        $recentReviews = $artist->reviews()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.pages.artists.show', compact('title', 'artist', 'stats', 'recentBookings', 'recentReviews'));
    }

    public function edit(Artist $artist)
    {
        $title = 'Edit Artist';
        $mode = 'edit';

        $roleKey = Auth::user()->role->role_key;

        if ($roleKey === 'company_admin' && $artist->company_id !== Auth::user()->company_id) {
            return abort(403, 'Unauthorized');
        }

        if ($roleKey === 'company_admin') {
            $users = User::where('company_id', Auth::user()->company_id)
                ->whereHas('role', function ($q) {
                    $q->where('role_key', 'artist');
                })->get();
        } else {
            $users = User::whereHas('role', function ($q) {
                $q->where('role_key', 'artist');
            })->get();
        }

        $companies = $roleKey === 'company_admin'
            ? Company::where('id', Auth::user()->company_id)->get()
            : Company::all();

        return view('dashboard.pages.artists.manage', compact('title', 'mode', 'artist', 'users', 'companies'));
    }

    public function update(Request $request, Artist $artist)
    {
        $roleKey = Auth::user()->role->role_key;

        if ($roleKey === 'company_admin' && $artist->company_id !== Auth::user()->company_id) {
            return abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'company_id'      => 'required|exists:companies,id',
            'user_id'         => 'required|exists:users,id',
            'stage_name'      => 'required|string|max:255',
            'experience_years'=> 'required|integer|min:0',
            'genres'          => 'required|string|max:255',
            'specialization'  => 'required|string|max:255',
            'bio'             => 'nullable|string|max:1000',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Company admin can only update to their own company
        if ($roleKey === 'company_admin' && $validated['company_id'] !== Auth::user()->company_id) {
            return abort(403, 'You can only assign artists to your company');
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
            $validated['image'] = $path;
        }

        $artist->update($validated);

        return redirect()->route('artists.index')->with('success', 'Artist updated successfully.');
    }

    public function destroy(Artist $artist)
    {
        $roleKey = Auth::user()->role->role_key;

        if ($roleKey === 'company_admin' && $artist->company_id !== Auth::user()->company_id) {
            return abort(403, 'Unauthorized');
        }

        $artist->delete();

        return redirect()->route('artists.index')->with('success', 'Artist deleted successfully.');
    }
}
