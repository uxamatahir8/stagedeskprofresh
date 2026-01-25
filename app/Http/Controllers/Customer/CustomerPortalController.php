<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\Payment;
use App\Models\Review;
use App\Models\EventType;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerPortalController extends Controller
{
    /**
     * Customer Dashboard
     */
    public function dashboard()
    {
        $title = 'My Dashboard';
        $user = Auth::user();

        $stats = [
            'total_bookings' => BookingRequest::where('user_id', $user->id)->count(),
            'pending_bookings' => BookingRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'confirmed_bookings' => BookingRequest::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count(),
            'completed_bookings' => BookingRequest::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Payment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_payments' => Payment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
        ];

        // Upcoming events
        $upcomingBookings = BookingRequest::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('event_date', '>=', now())
            ->with(['eventType', 'company', 'assignedArtist.user'])
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = BookingRequest::where('user_id', $user->id)
            ->with(['eventType', 'company', 'assignedArtist.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Payment history
        $recentPayments = Payment::where('user_id', $user->id)
            ->with('bookingRequest')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.pages.customer.dashboard', compact(
            'title',
            'stats',
            'upcomingBookings',
            'recentBookings',
            'recentPayments'
        ));
    }

    /**
     * My Bookings
     */
    public function myBookings(Request $request)
    {
        $title = 'My Bookings';
        $user = Auth::user();

        $query = BookingRequest::where('user_id', $user->id)
            ->with(['eventType', 'company', 'assignedArtist.user', 'payments']);

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

        return view('dashboard.pages.customer.bookings', compact('title', 'bookings'));
    }

    /**
     * Booking Details
     */
    public function bookingDetails(BookingRequest $booking)
    {
        $user = Auth::user();

        // Safety check: customer can only view own bookings
        if ($booking->user_id !== $user->id) {
            abort(403, 'You can only view your own bookings');
        }

        $title = 'Booking Details';
        $booking->load(['eventType', 'company', 'assignedArtist.user', 'payments', 'reviews']);

        // Check if user can leave a review
        $canReview = $booking->status === 'completed' &&
                     !Review::where('booking_id', $booking->id)
                          ->where('user_id', $user->id)
                          ->exists();

        return view('dashboard.pages.customer.booking-details', compact('title', 'booking', 'canReview'));
    }

    /**
     * Create Booking
     */
    public function createBooking()
    {
        $title = 'Request New Booking';
        $eventTypes = EventType::all();
        $companies = Company::where('status', 'active')->get();

        return view('dashboard.pages.customer.create-booking', compact('title', 'eventTypes', 'companies'));
    }

    /**
     * Cancel Booking
     */
    public function cancelBooking(Request $request, BookingRequest $booking)
    {
        $user = Auth::user();

        // Safety check
        if ($booking->user_id !== $user->id) {
            abort(403, 'You can only cancel your own bookings');
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'Only pending or confirmed bookings can be cancelled');
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'company_notes' => "Customer cancellation: " . $request->reason
            ]);

            DB::commit();

            return back()->with('success', 'Booking cancelled successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    /**
     * My Payments
     */
    public function myPayments(Request $request)
    {
        $title = 'My Payments';
        $user = Auth::user();

        $query = Payment::where('user_id', $user->id)
            ->with('bookingRequest');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        // Payment summary
        $summary = [
            'total_paid' => Payment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_amount' => Payment::where('user_id', $user->id)
                ->where('status', 'pending')
                ->sum('amount'),
        ];

        return view('dashboard.pages.customer.payments', compact('title', 'payments', 'summary'));
    }

    /**
     * Submit Review
     */
    public function submitReview(Request $request, BookingRequest $booking)
    {
        $user = Auth::user();

        // Safety checks
        if ($booking->user_id !== $user->id) {
            abort(403, 'You can only review your own bookings');
        }

        if ($booking->status !== 'completed') {
            return back()->with('error', 'You can only review completed bookings');
        }

        // Check if already reviewed
        if (Review::where('booking_id', $booking->id)->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'You have already reviewed this booking');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'artist_id' => 'nullable|exists:artists,id',
            'company_id' => 'nullable|exists:companies,id'
        ]);

        DB::beginTransaction();
        try {
            $validated['user_id'] = $user->id;
            $validated['booking_id'] = $booking->id;
            $validated['status'] = 'pending'; // Requires admin approval

            Review::create($validated);

            // Update artist rating if applicable
            if ($validated['artist_id'] ?? false) {
                $this->updateArtistRating($validated['artist_id']);
            }

            DB::commit();

            return back()->with('success', 'Review submitted successfully! It will appear after admin approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit review: ' . $e->getMessage());
        }
    }

    /**
     * My Reviews
     */
    public function myReviews()
    {
        $title = 'My Reviews';
        $user = Auth::user();

        $reviews = Review::where('user_id', $user->id)
            ->with(['booking', 'artist.user', 'company'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.pages.customer.reviews', compact('title', 'reviews'));
    }

    /**
     * Profile Settings
     */
    public function profile()
    {
        $title = 'My Profile';
        $user = Auth::user()->load('profile');

        return view('dashboard.pages.customer.profile', compact('title', 'user'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            $user->update($validated);

            DB::commit();

            return back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to update artist rating
     */
    private function updateArtistRating($artistId)
    {
        $avgRating = Review::where('artist_id', $artistId)
            ->where('status', 'approved')
            ->avg('rating');

        if ($avgRating) {
            \App\Models\Artist::where('id', $artistId)
                ->update(['rating' => round($avgRating, 2)]);
        }
    }
}
