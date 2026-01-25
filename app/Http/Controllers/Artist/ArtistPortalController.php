<?php

namespace App\Http\Controllers\Artist;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\BookingRequest;
use App\Models\ArtistRequest;
use App\Models\Payment;
use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArtistPortalController extends Controller
{
    /**
     * Artist Dashboard
     */
    public function dashboard()
    {
        $title = 'Artist Dashboard';
        $user = Auth::user();
        $artist = $user->artist;

        // Safety check
        if (!$artist) {
            return redirect()->route('artist.create-profile')
                ->with('info', 'Please complete your artist profile first');
        }

        $stats = [
            'total_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)->count(),
            'pending_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->where('status', 'pending')
                ->count(),
            'confirmed_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->where('status', 'confirmed')
                ->count(),
            'completed_bookings' => BookingRequest::where('assigned_artist_id', $artist->id)
                ->where('status', 'completed')
                ->count(),
            'total_earnings' => Payment::whereHas('bookingRequest', fn($q) =>
                    $q->where('assigned_artist_id', $artist->id)
                )
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_earnings' => Payment::whereHas('bookingRequest', fn($q) =>
                    $q->where('assigned_artist_id', $artist->id)
                )
                ->where('status', 'pending')
                ->sum('amount'),
            'average_rating' => $artist->rating ?? 0,
            'total_reviews' => Review::where('artist_id', $artist->id)
                ->where('status', 'approved')
                ->count(),
        ];

        // Upcoming bookings
        $upcomingBookings = BookingRequest::where('assigned_artist_id', $artist->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('event_date', '>=', now())
            ->with(['user', 'eventType', 'company'])
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = BookingRequest::where('assigned_artist_id', $artist->id)
            ->with(['user', 'eventType', 'company'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Recent reviews
        $recentReviews = Review::where('artist_id', $artist->id)
            ->where('status', 'approved')
            ->with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Monthly earnings chart
        $monthlyEarnings = $this->getMonthlyEarnings($artist->id);

        return view('dashboard.pages.artist.dashboard', compact(
            'title',
            'artist',
            'stats',
            'upcomingBookings',
            'recentBookings',
            'recentReviews',
            'monthlyEarnings'
        ));
    }

    /**
     * My Bookings
     */
    public function myBookings(Request $request)
    {
        $title = 'My Bookings';
        $artist = Auth::user()->artist;

        $query = BookingRequest::where('assigned_artist_id', $artist->id)
            ->with(['user', 'eventType', 'company', 'payments']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('event_date', 'desc')->paginate(20);

        return view('dashboard.pages.artist.bookings', compact('title', 'bookings'));
    }

    /**
     * Booking Details
     */
    public function bookingDetails(BookingRequest $booking)
    {
        $artist = Auth::user()->artist;

        // Safety check: artist can only view own assigned bookings
        if ($booking->assigned_artist_id !== $artist->id) {
            abort(403, 'You can only view bookings assigned to you');
        }

        $title = 'Booking Details';
        $booking->load(['user', 'eventType', 'company', 'payments']);

        // Determine available actions based on booking status
        $canAccept = $booking->status === 'pending';
        $canReject = in_array($booking->status, ['pending', 'confirmed']);
        $canComplete = $booking->status === 'confirmed';
        $isCompleted = $booking->status === 'completed';
        $isCancelled = in_array($booking->status, ['cancelled', 'rejected']);

        return view('dashboard.pages.artist.booking-details', compact(
            'title', 'booking', 'canAccept', 'canReject', 'canComplete', 'isCompleted', 'isCancelled'
        ));
    }

    /**
     * Mark Booking as Completed
     */
    public function markBookingCompleted(Request $request, BookingRequest $booking)
    {
        $artist = Auth::user()->artist;

        // Safety checks
        if ($booking->assigned_artist_id !== $artist->id) {
            abort(403, 'You can only mark your own bookings as completed');
        }

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked as completed');
        }

        $request->validate([
            'completion_notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'completed',
                'completed_at' => now(),
                'company_notes' => ($booking->company_notes ?? '') . "\n\nArtist completion notes: " . ($request->completion_notes ?? 'None')
            ]);

            ActivityLog::log(
                'updated',
                $booking,
                'Booking marked as completed by artist',
                ['booking_id' => $booking->id, 'artist_id' => $artist->id]
            );

            // Notify company admin via email and in-app notification
            if ($booking->company) {
                $companyAdmins = \App\Models\User::where('company_id', $booking->company_id)
                    ->whereHas('role', fn($q) => $q->where('role_key', 'company_admin'))
                    ->get();

                foreach ($companyAdmins as $admin) {
                    // In-app notification
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'title' => 'Booking Completed',
                        'message' => $artist->user->name . ' marked booking #' . $booking->id . ' as completed',
                        'type' => 'booking_completed',
                        'link' => route('bookings.show', $booking->id),
                    ]);

                    // Email notification
                    try {
                        \Mail::to($admin->email)->send(
                            new \App\Mail\BookingStatusChanged($booking->fresh(), 'confirmed', 'completed', $artist->user->name . ' completed the event')
                        );
                    } catch (\Exception $e) {
                        \Log::error('Failed to send completion email to company admin: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            return back()->with('success', 'Booking marked as completed successfully! Company has been notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark booking as completed: ' . $e->getMessage());
        }
    }

    /**
     * Accept Booking
     */
    public function acceptBooking(BookingRequest $booking)
    {
        $artist = Auth::user()->artist;

        // Safety check
        if ($booking->assigned_artist_id !== $artist->id) {
            abort(403, 'You can only accept bookings assigned to you');
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be accepted. Current status: ' . ucfirst($booking->status));
        }

        DB::beginTransaction();
        try {
            $oldStatus = $booking->status;
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'company_notes' => ($booking->company_notes ?? '') . "\n\n[" . now()->format('Y-m-d H:i:s') . "] Artist accepted the booking."
            ]);

            ActivityLog::log(
                'updated',
                $booking,
                'Booking accepted by artist ' . $artist->user->name,
                ['booking_id' => $booking->id, 'artist_id' => $artist->id, 'confirmed_at' => now()]
            );

            // Send email to customer about booking confirmation
            if ($booking->user && $booking->user->email) {
                try {
                    \Mail::to($booking->user->email)->send(
                        new \App\Mail\BookingStatusChanged($booking->fresh(), $oldStatus, 'confirmed')
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send booking acceptance email to customer: ' . $e->getMessage());
                }
            }

            // Notify company admin
            if ($booking->company) {
                $companyAdmins = \App\Models\User::where('company_id', $booking->company_id)
                    ->whereHas('role', fn($q) => $q->where('role_key', 'company_admin'))
                    ->get();

                foreach ($companyAdmins as $admin) {
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'title' => 'Booking Accepted',
                        'message' => $artist->user->name . ' accepted booking #' . $booking->id,
                        'type' => 'booking_accepted',
                        'link' => route('bookings.show', $booking->id),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('artist.bookings.details', $booking->id)
                ->with('success', '✅ Booking accepted successfully! Customer has been notified.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to accept booking: ' . $e->getMessage());
        }
    }

    /**
     * Reject Booking
     */
    public function rejectBooking(Request $request, BookingRequest $booking)
    {
        $artist = Auth::user()->artist;

        // Safety check
        if ($booking->assigned_artist_id !== $artist->id) {
            abort(403, 'You can only reject bookings assigned to you');
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be rejected. Current status: ' . ucfirst($booking->status));
        }

        $request->validate([
            'reason' => 'required|string|max:500|min:10'
        ], [
            'reason.required' => 'Please provide a reason for rejection',
            'reason.min' => 'Please provide a detailed reason (at least 10 characters)'
        ]);

        DB::beginTransaction();
        try {
            $artistName = $artist->user->name;
            $rejectionNote = "[" . now()->format('Y-m-d H:i:s') . "] Rejected by " . $artistName . ": " . $request->reason;

            // When artist rejects, booking goes back to pending state with no artist assigned
            // This allows company to reassign to another artist
            $oldStatus = $booking->status;
            $booking->update([
                'status' => 'pending',
                'assigned_artist_id' => null,
                'confirmed_at' => null,
                'company_notes' => ($booking->company_notes ?? '') . "\n\n" . $rejectionNote
            ]);

            ActivityLog::log(
                'updated',
                $booking,
                'Booking rejected by artist ' . $artistName . ' - returned to pending for reassignment',
                ['booking_id' => $booking->id, 'artist_id' => $artist->id, 'reason' => $request->reason]
            );

            // Send email to customer about booking status change
            if ($booking->user && $booking->user->email) {
                try {
                    \Mail::to($booking->user->email)->send(
                        new \App\Mail\BookingStatusChanged($booking->fresh(), $oldStatus, 'pending')
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send rejection email to customer: ' . $e->getMessage());
                }
            }

// Notify company admins about rejection via email and in-app notification
            if ($booking->company) {
                $companyAdmins = \App\Models\User::where('company_id', $booking->company_id)
                    ->whereHas('role', fn($q) => $q->where('role_key', 'company_admin'))
                    ->get();

                foreach ($companyAdmins as $admin) {
                    // In-app notification
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'title' => 'Booking Rejected by Artist',
                        'message' => $artistName . ' rejected booking #' . $booking->id . ' for ' . $booking->name . ' ' . $booking->surname . '. Reason: ' . $request->reason,
                        'type' => 'booking_rejected',
                        'link' => route('bookings.show', $booking->id),
                    ]);

                    // Email notification
                    try {
                        \Mail::to($admin->email)->send(
                            new \App\Mail\BookingStatusChanged($booking->fresh(), $oldStatus, 'pending', $artistName . ' rejected: ' . $request->reason)
                        );
                    } catch (\Exception $e) {
                        \Log::error('Failed to send rejection email to company admin: ' . $e->getMessage());
                    }
                }
            }

            DB::commit();

            return redirect()->route('artist.bookings')->with('warning', '⚠️ Booking rejected. The company will be notified and can reassign to another artist.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject booking: ' . $e->getMessage());
        }
    }

    /**
     * My Profile
     */
    public function profile()
    {
        $title = 'My Profile';
        $artist = Auth::user()->artist()->with(['user', 'company'])->first();

        if (!$artist) {
            return redirect()->route('artist.create-profile')
                ->with('info', 'Please complete your artist profile first');
        }

        return view('dashboard.pages.artist.profile', compact('title', 'artist'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $artist = Auth::user()->artist;

        $validated = $request->validate([
            'bio' => 'nullable|string|max:2000',
            'genres' => 'nullable|json',
            'experience_years' => 'nullable|integer|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'availability' => 'nullable|in:available,busy,unavailable',
            'profile_image' => 'nullable|image|max:2048',
            'portfolio_images' => 'nullable|array',
            'portfolio_images.*' => 'image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                if ($artist->profile_image) {
                    Storage::disk('public')->delete($artist->profile_image);
                }
                $validated['profile_image'] = $request->file('profile_image')
                    ->store('artist-profiles', 'public');
            }

            // Handle portfolio images
            if ($request->hasFile('portfolio_images')) {
                $portfolioImages = [];
                foreach ($request->file('portfolio_images') as $image) {
                    $portfolioImages[] = $image->store('artist-portfolio', 'public');
                }
                $validated['portfolio_images'] = json_encode($portfolioImages);
            }

            $artist->update($validated);

            ActivityLog::log(
                'updated',
                $artist,
                'Artist profile updated',
                ['artist_id' => $artist->id]
            );

            DB::commit();

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * My Earnings
     */
    public function earnings(Request $request)
    {
        $title = 'My Earnings';
        $artist = Auth::user()->artist;

        $query = Payment::whereHas('bookingRequest', fn($q) =>
                $q->where('assigned_artist_id', $artist->id)
            )
            ->with('bookingRequest');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Earnings summary
        $summary = [
            'total_earnings' => Payment::whereHas('bookingRequest', fn($q) =>
                    $q->where('assigned_artist_id', $artist->id)
                )
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_earnings' => Payment::whereHas('bookingRequest', fn($q) =>
                    $q->where('assigned_artist_id', $artist->id)
                )
                ->where('status', 'pending')
                ->sum('amount'),
            'monthly_earnings' => Payment::whereHas('bookingRequest', fn($q) =>
                    $q->where('assigned_artist_id', $artist->id)
                )
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];

        $monthlyEarnings = $this->getMonthlyEarnings($artist->id);

        return view('dashboard.pages.artist.earnings', compact('title', 'payments', 'summary', 'monthlyEarnings'));
    }

    /**
     * My Reviews
     */
    public function reviews()
    {
        $title = 'My Reviews';
        $artist = Auth::user()->artist;

        $reviews = Review::where('artist_id', $artist->id)
            ->with(['user', 'booking'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'average_rating' => $artist->rating ?? 0,
            'total_reviews' => Review::where('artist_id', $artist->id)
                ->where('status', 'approved')
                ->count(),
            'pending_reviews' => Review::where('artist_id', $artist->id)
                ->where('status', 'pending')
                ->count(),
        ];

        return view('dashboard.pages.artist.reviews', compact('title', 'reviews', 'stats'));
    }

    /**
     * Availability Calendar
     */
    public function availability()
    {
        $title = 'My Availability';
        $artist = Auth::user()->artist;

        $bookings = BookingRequest::where('assigned_artist_id', $artist->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('event_date', 'asc')
            ->get();

        return view('dashboard.pages.artist.availability', compact('title', 'artist', 'bookings'));
    }

    /**
     * Update Availability Status
     */
    public function updateAvailability(Request $request)
    {
        $artist = Auth::user()->artist;

        $validated = $request->validate([
            'availability' => 'required|in:available,busy,unavailable'
        ]);

        $artist->update($validated);

        return back()->with('success', 'Availability status updated successfully!');
    }

    /**
     * Helper method to get monthly earnings
     */
    private function getMonthlyEarnings($artistId)
    {
        $months = [];
        $earnings = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M');

            $earnings[] = Payment::whereHas('bookingRequest', fn($q) =>
                    $q->where('assigned_artist_id', $artistId)
                )
                ->where('status', 'completed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
        }

        return [
            'months' => $months,
            'earnings' => $earnings
        ];
    }
}
