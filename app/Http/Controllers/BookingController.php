<?php
namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\EventType;
use App\Models\User;
use App\Models\Artist;
use App\Models\Company;
use App\Models\ActivityLog;
use App\Events\BookingCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function show(BookingRequest $booking)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Verify access permission
        if (!$this->canAccessBooking($booking, $user, $roleKey)) {
            abort(403, 'You do not have permission to view this booking');
        }

        $title = 'Booking Details';

        // Load relationships
        $booking->load(['user', 'eventType', 'company', 'assignedArtist.user', 'artistRequests.artist.user', 'payments', 'reviews']);

        return view('dashboard.pages.bookings.show', compact('title', 'booking'));
    }

    //

    public function index()
    {
        $title = 'Booking Requests';
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        $bookingResolvers = [
            'master_admin'  => fn()  => BookingRequest::with(['user', 'eventType', 'company', 'assignedArtist']),
            'company_admin' => fn() => BookingRequest::where('company_id', $user->company_id)
                                                      ->with(['user', 'eventType', 'assignedArtist']),
            'customer'      => fn()  => BookingRequest::where('user_id', $user->id)
                                                       ->with(['eventType', 'company', 'assignedArtist']),
            'artist'        => fn()  => BookingRequest::where('assigned_artist_id', optional($user->artist)->id)
                                                       ->with(['user', 'eventType', 'company']),
        ];

        $bookings = isset($bookingResolvers[$roleKey])
            ? $bookingResolvers[$roleKey]()->orderBy('created_at', 'desc')->get()
            : collect();

        return view('dashboard.pages.bookings.index', compact('title', 'bookings'));
    }

    public function create()
    {
        $title = 'Create Booking Request';
        $mode  = 'create';
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Only admins and customers can create bookings
        if (!in_array($roleKey, ['master_admin', 'company_admin', 'customer'])) {
            abort(403, 'You do not have permission to create bookings');
        }

        $customers = match ($roleKey) {
            'company_admin' => User::companyCustomers()->get(),
            'customer'      => collect([]),  // Customers create for themselves
            default         => User::allCustomers()->get(),
        };

        // Get companies for admin assignment
        $companies = in_array($roleKey, ['master_admin', 'company_admin'])
            ? ($roleKey === 'master_admin' ? Company::all() : Company::where('id', $user->company_id)->get())
            : collect([]);

        // Get artists for assignment
        $artists = match ($roleKey) {
            'company_admin' => Artist::where('company_id', $user->company_id)->with('user')->get(),
            'master_admin'  => Artist::with(['user', 'company'])->get(),
            default         => collect([]),
        };

        $event_types = EventType::all();

        return view('dashboard.pages.bookings.manage', compact(
            'title',
            'mode',
            'customers',
            'companies',
            'artists',
            'event_types'
        ));
    }

    public function edit(BookingRequest $booking)
    {
        $title = 'Edit Booking Request';
        $mode  = 'edit';
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Verify edit permission
        if (!$this->canEditBooking($booking, $user, $roleKey)) {
            abort(403, 'You do not have permission to edit this booking');
        }

        $customers = match ($roleKey) {
            'company_admin' => User::companyCustomers()->get(),
            'customer'      => collect([]),
            default         => User::allCustomers()->get(),
        };

        // Get companies for admin assignment
        $companies = in_array($roleKey, ['master_admin', 'company_admin'])
            ? ($roleKey === 'master_admin' ? Company::all() : Company::where('id', $user->company_id)->get())
            : collect([]);

        // Get artists for assignment
        $artists = match ($roleKey) {
            'company_admin' => Artist::where('company_id', $user->company_id)->with('user')->get(),
            'master_admin'  => Artist::with(['user', 'company'])->get(),
            default         => collect([]),
        };

        $event_types = EventType::all();

        return view('dashboard.pages.bookings.manage', compact(
            'title',
            'mode',
            'customers',
            'companies',
            'artists',
            'event_types',
            'booking'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        $eventType = EventType::find($request->event_type_id);
        $isWedding = $eventType && str_contains(strtolower($eventType->event_type), 'wedding');

        $validated = $request->validate([
            'event_date'          => 'required|date|after:today',
            'user_id'             => 'required|exists:users,id',
            'event_type_id'       => 'required|exists:event_types,id',
            'company_id'          => 'nullable|exists:companies,id',
            'assigned_artist_id'  => 'nullable|exists:artists,id',
            'name'                => 'required|string|max:255',
            'surname'             => 'required|string|max:255',
            'date_of_birth'       => 'required|date|before:' . now()->subDays(5)->format('Y-m-d'),
            'phone'               => 'required|string|max:20',
            'email'               => 'required|email|max:255',
            'address'             => 'required|string|max:255',
            'partner_name'        => $isWedding ? 'required|string|max:255' : 'nullable|string|max:255',
            'wedding_date'        => $isWedding ? 'required|date|after:today' : 'nullable|date',
            'wedding_time'        => $isWedding ? 'required|string' : 'nullable|string',
            'dos'                 => 'nullable|string',
            'donts'               => 'nullable|string',
            'playlist_spotify'    => 'nullable|string',
            'additional_notes'    => 'nullable|string',
            'company_notes'       => 'nullable|string',
        ]);

        // Auto-assign company for company admins
        if ($roleKey === 'company_admin' && !$validated['company_id']) {
            $validated['company_id'] = $user->company_id;
        }

        // Auto-set user_id for customers
        if ($roleKey === 'customer') {
            $validated['user_id'] = $user->id;
        }

        // Set initial status
        $validated['status'] = 'pending';

        // If artist is assigned immediately, update status
        if (!empty($validated['assigned_artist_id'])) {
            $validated['status'] = 'confirmed';
            $validated['confirmed_at'] = now();
        }

        DB::beginTransaction();
        try {
            $booking = BookingRequest::create($validated);

            // Log activity
            ActivityLog::log(
                'created',
                $booking,
                'Created new booking request for ' . $booking->name . ' ' . $booking->surname,
                ['booking_id' => $booking->id, 'status' => $booking->status]
            );

            // Fire booking created event if company is assigned
            if ($booking->company_id && $booking->company) {
                event(new BookingCreated($booking, $booking->company));
            }

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function update(Request $request, BookingRequest $booking)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Verify edit permission
        if (!$this->canEditBooking($booking, $user, $roleKey)) {
            abort(403, 'You do not have permission to update this booking');
        }

        $eventType = EventType::find($request->event_type_id);
        $isWedding = $eventType && str_contains(strtolower($eventType->event_type), 'wedding');

        $validated = $request->validate([
            'event_date'          => 'required|date|after:today',
            'user_id'             => 'required|exists:users,id',
            'event_type_id'       => 'required|exists:event_types,id',
            'company_id'          => 'nullable|exists:companies,id',
            'assigned_artist_id'  => 'nullable|exists:artists,id',
            'name'                => 'required|string|max:255',
            'surname'             => 'required|string|max:255',
            'date_of_birth'       => 'required|date|before:' . now()->subDays(5)->format('Y-m-d'),
            'phone'               => 'required|string|max:20',
            'email'               => 'required|email|max:255',
            'address'             => 'required|string|max:255',
            'partner_name'        => $isWedding ? 'required|string|max:255' : 'nullable|string|max:255',
            'wedding_date'        => $isWedding ? 'required|date|after:today' : 'nullable|date',
            'wedding_time'        => $isWedding ? 'required|string' : 'nullable|string',
            'dos'                 => 'nullable|string',
            'donts'               => 'nullable|string',
            'playlist_spotify'    => 'nullable|string',
            'additional_notes'    => 'nullable|string',
            'company_notes'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Track if artist was newly assigned
            $wasArtistAssigned = empty($booking->assigned_artist_id) && !empty($validated['assigned_artist_id']);

            $booking->update($validated);

            // If artist was just assigned, update status to confirmed
            if ($wasArtistAssigned) {
                $booking->update([
                    'status' => 'confirmed',
                    'confirmed_at' => now()
                ]);

                ActivityLog::log(
                    'updated',
                    $booking,
                    'Artist assigned to booking: ' . $booking->assignedArtist->user->name,
                    ['booking_id' => $booking->id, 'artist_id' => $validated['assigned_artist_id']]
                );
            } else {
                ActivityLog::log(
                    'updated',
                    $booking,
                    'Booking updated for ' . $booking->name . ' ' . $booking->surname,
                    ['booking_id' => $booking->id]
                );
            }

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update booking: ' . $e->getMessage());
        }
    }

    public function destroy(BookingRequest $booking)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Only admins can delete bookings
        if (!in_array($roleKey, ['master_admin', 'company_admin'])) {
            abort(403, 'You do not have permission to delete bookings');
        }

        // Safety check: Company admin can only delete bookings from their company
        if ($roleKey === 'company_admin' && $booking->company_id !== $user->company_id) {
            abort(403, 'You can only delete bookings from your company');
        }

        DB::beginTransaction();
        try {
            ActivityLog::log(
                'deleted',
                $booking,
                'Booking deleted for ' . $booking->name . ' ' . $booking->surname,
                ['booking_id' => $booking->id, 'status' => $booking->status]
            );

            $booking->delete();

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }

    /**
     * Assign an artist to a booking (Company Admin)
     */
    public function assignArtist(Request $request, BookingRequest $booking)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Only admins can assign artists
        if (!in_array($roleKey, ['master_admin', 'company_admin'])) {
            abort(403, 'You do not have permission to assign artists');
        }

        // Safety check: Verify company scope
        if ($roleKey === 'company_admin' && $booking->company_id !== $user->company_id) {
            abort(403, 'You can only assign artists to bookings from your company');
        }

        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'company_notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $booking->update([
                'assigned_artist_id' => $request->artist_id,
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'company_notes' => $request->company_notes ?? $booking->company_notes
            ]);

            $artist = Artist::find($request->artist_id);

            ActivityLog::log(
                'updated',
                $booking,
                'Artist assigned to booking: ' . $artist->user->name,
                ['booking_id' => $booking->id, 'artist_id' => $request->artist_id]
            );

            DB::commit();

            return back()->with('success', 'Artist assigned successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to assign artist: ' . $e->getMessage());
        }
    }

    /**
     * Mark booking as completed
     */
    public function markCompleted(BookingRequest $booking)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Only admins and assigned artist can mark as completed
        $canComplete = in_array($roleKey, ['master_admin', 'company_admin']) ||
                      ($roleKey === 'artist' && $booking->assigned_artist_id == optional($user->artist)->id);

        if (!$canComplete) {
            abort(403, 'You do not have permission to mark this booking as completed');
        }

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked as completed');
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            ActivityLog::log(
                'updated',
                $booking,
                'Booking marked as completed',
                ['booking_id' => $booking->id, 'completed_by' => $user->name]
            );

            DB::commit();

            return back()->with('success', 'Booking marked as completed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to mark booking as completed: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, BookingRequest $booking)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Safety check: Admins and customers can cancel
        $canCancel = in_array($roleKey, ['master_admin', 'company_admin', 'customer']);

        if (!$canCancel) {
            abort(403, 'You do not have permission to cancel this booking');
        }

        // Customer can only cancel their own bookings
        if ($roleKey === 'customer' && $booking->user_id !== $user->id) {
            abort(403, 'You can only cancel your own bookings');
        }

        // Company admin can only cancel bookings from their company
        if ($roleKey === 'company_admin' && $booking->company_id !== $user->company_id) {
            abort(403, 'You can only cancel bookings from your company');
        }

        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Cannot cancel a booking that is already ' . $booking->status);
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'company_notes' => ($booking->company_notes ?? '') . "\n\nCancellation reason: " . ($request->cancellation_reason ?? 'No reason provided')
            ]);

            ActivityLog::log(
                'updated',
                $booking,
                'Booking cancelled by ' . $user->name,
                ['booking_id' => $booking->id, 'reason' => $request->cancellation_reason]
            );

            DB::commit();

            return back()->with('success', 'Booking cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to check if user can access a booking
     */
    private function canAccessBooking(BookingRequest $booking, $user, string $roleKey): bool
    {
        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            'customer' => $booking->user_id === $user->id,
            'artist' => $booking->assigned_artist_id == optional($user->artist)->id,
            default => false
        };
    }

    /**
     * Helper method to check if user can edit a booking
     */
    private function canEditBooking(BookingRequest $booking, $user, string $roleKey): bool
    {
        // Completed or cancelled bookings cannot be edited
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return false;
        }

        return match ($roleKey) {
            'master_admin' => true,
            'company_admin' => $booking->company_id === $user->company_id,
            'customer' => $booking->user_id === $user->id && $booking->status === 'pending',
            default => false
        };
    }

}
