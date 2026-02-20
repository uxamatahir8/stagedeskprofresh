<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\BookingRequest;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index()
    {
        $title = 'Reviews';
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        $query = Review::with(['user', 'artist', 'company', 'booking']);

        if ($roleKey === 'company_admin') {
            $query->where('company_id', $user->company_id);
        } elseif ($roleKey === 'artist') {
            $artist = Artist::where('user_id', $user->id)->first();
            if ($artist) {
                $query->where('artist_id', $artist->id);
            }
        } elseif ($roleKey === 'customer') {
            $query->where('user_id', $user->id);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboard.pages.reviews.index', compact('title', 'reviews'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create(Request $request)
    {
        $bookingRef = $request->query('booking_id');
        $booking = BookingRequest::with(['assignedArtist', 'company'])
            ->where('tracking_code', $bookingRef)
            ->orWhere('id', $bookingRef)
            ->firstOrFail();

        // Check if user can review
        if (!$booking->canBeReviewed()) {
            return redirect()->back()->with('error', 'This booking cannot be reviewed.');
        }

        $title = 'Write a Review';
        return view('dashboard.pages.reviews.create', compact('title', 'booking'));
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:booking_requests,id',
            'artist_id' => 'nullable|exists:artists,id',
            'company_id' => 'nullable|exists:companies,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending'; // Default status, requires admin approval

        $review = Review::create($validated);

        // Update artist rating if applicable
        if ($review->artist_id) {
            $this->updateArtistRating($review->artist_id);
        }

        $booking = BookingRequest::findOrFail($validated['booking_id']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Review submitted successfully and is pending approval.');
    }

    /**
     * Display the specified review
     */
    public function show(Review $review)
    {
        $title = 'Review Details';
        return view('dashboard.pages.reviews.show', compact('title', 'review'));
    }

    /**
     * Update review status (approve/reject)
     */
    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'is_featured' => 'nullable|boolean',
        ]);

        $review->update($validated);

        if ($review->artist_id) {
            $this->updateArtistRating($review->artist_id);
        }

        return redirect()->back()->with('success', 'Review status updated successfully.');
    }

    /**
     * Remove the specified review
     */
    public function destroy(Review $review)
    {
        // Check authorization
        if (Auth::user()->role->role_key !== 'master_admin' && $review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $artistId = $review->artist_id;
        $review->delete();

        if ($artistId) {
            $this->updateArtistRating($artistId);
        }

        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }

    /**
     * Update artist average rating
     */
    private function updateArtistRating($artistId)
    {
        $averageRating = Review::where('artist_id', $artistId)
            ->whereIn('status', ['pending', 'approved'])
            ->avg('rating');

        Artist::where('id', $artistId)->update([
            'rating' => round($averageRating ?? 0, 2)
        ]);
    }
}
