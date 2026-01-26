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
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function show(BookingRequest $booking)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Artists should use their own portal
        if ($roleKey === 'artist') {
            return redirect()->route('artist.bookings.details', $booking);
        }

        // Customers should use their own portal
        if ($roleKey === 'customer') {
            return redirect()->route('customer.bookings.details', $booking);
        }

        // Safety check: Verify access permission (for admins)
        if (!$this->canAccessBooking($booking, $user, $roleKey)) {
            abort(403, 'You do not have permission to view this booking');
        }

        $title = 'Booking Details';

        // Load relationships
        $booking->load(['user', 'eventType', 'company', 'assignedArtist.user', 'artistRequests.artist.user', 'payments', 'reviews']);

        // Get artists for assignment (admin only)
        $artists = match ($roleKey) {
            'company_admin' => Artist::where('company_id', $user->company_id)->with('user')->get(),
            'master_admin'  => Artist::with(['user', 'company'])->get(),
            default         => collect([]),
        };

        return view('dashboard.pages.bookings.show', compact('title', 'booking', 'artists'));
    }

    //

    public function index()
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Redirect to role-specific portals
        if ($roleKey === 'artist') {
            return redirect()->route('artist.bookings');
        }

        if ($roleKey === 'customer') {
            return redirect()->route('customer.bookings');
        }

        // Only admins should reach here
        $title = 'Booking Requests';

        $bookingResolvers = [
            'master_admin'  => fn()  => BookingRequest::with(['user', 'eventType', 'company', 'assignedArtist']),
            'company_admin' => fn() => BookingRequest::where('company_id', $user->company_id)
                                                      ->with(['user', 'eventType', 'assignedArtist']),
        ];

        $bookings = isset($bookingResolvers[$roleKey])
            ? $bookingResolvers[$roleKey]()->orderBy('created_at', 'desc')->get()
            : collect();

        // Get artists for assignment (admin only)
        $artists = match ($roleKey) {
            'company_admin' => Artist::where('company_id', $user->company_id)->with('user')->get(),
            'master_admin'  => Artist::with(['user', 'company'])->get(),
            default         => collect([]),
        };

        return view('dashboard.pages.bookings.index', compact('title', 'bookings', 'artists'));
    }

    public function create()
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Customers should use their own portal
        if ($roleKey === 'customer') {
            return redirect()->route('customer.bookings.create');
        }

        // Artists cannot create bookings
        if ($roleKey === 'artist') {
            abort(403, 'Artists cannot create bookings');
        }

        // Only admins should reach here
        $title = 'Create Booking Request';
        $mode  = 'create';

        $customers = match ($roleKey) {
            'company_admin' => User::companyCustomers()->with('profile:user_id,phone')->select('id', 'name', 'email', 'company_id')->get()->map(function($customer) {
                $nameParts = explode(' ', $customer->name, 2);
                $customer->first_name = $nameParts[0] ?? '';
                $customer->surname = $nameParts[1] ?? '';
                $customer->phone = $customer->profile->phone ?? '';
                return $customer;
            }),
            default         => User::allCustomers()->with('profile:user_id,phone')->select('id', 'name', 'email', 'company_id')->get()->map(function($customer) {
                $nameParts = explode(' ', $customer->name, 2);
                $customer->first_name = $nameParts[0] ?? '';
                $customer->surname = $nameParts[1] ?? '';
                $customer->phone = $customer->profile->phone ?? '';
                return $customer;
            }),
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
        $user = Auth::user();
        $roleKey = $user->role->role_key;

        // Artists cannot edit bookings
        if ($roleKey === 'artist') {
            abort(403, 'Artists cannot edit bookings');
        }

        // Customers should use their own portal (if they have one)
        // Currently customers don't have edit functionality, so block them
        if ($roleKey === 'customer') {
            abort(403, 'Customers cannot edit bookings directly. Please contact support.');
        }

        // Safety check: Verify edit permission (for admins)
        if (!$this->canEditBooking($booking, $user, $roleKey)) {
            abort(403, 'You do not have permission to edit this booking');
        }

        $title = 'Edit Booking Request';
        $mode  = 'edit';

        $customers = match ($roleKey) {
            'company_admin' => User::companyCustomers()->with('profile:user_id,phone')->select('id', 'name', 'email', 'company_id')->get()->map(function($customer) {
                $nameParts = explode(' ', $customer->name, 2);
                $customer->first_name = $nameParts[0] ?? '';
                $customer->surname = $nameParts[1] ?? '';
                $customer->phone = $customer->profile->phone ?? '';
                return $customer;
            }),
            default         => User::allCustomers()->with('profile:user_id,phone')->select('id', 'name', 'email', 'company_id')->get()->map(function($customer) {
                $nameParts = explode(' ', $customer->name, 2);
                $customer->first_name = $nameParts[0] ?? '';
                $customer->surname = $nameParts[1] ?? '';
                $customer->phone = $customer->profile->phone ?? '';
                return $customer;
            }),
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

        // Modified validation - user_id is optional for admins creating bookings
        $validated = $request->validate([
            'event_date'          => 'required|date|after:today',
            'user_id'             => in_array($roleKey, ['master_admin', 'company_admin']) ? 'nullable' : 'required|exists:users,id',
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
            'create_customer_account' => 'nullable|boolean', // New field for creating customer account
        ]);

        // Auto-assign company for company admins
        if ($roleKey === 'company_admin' && empty($validated['company_id'])) {
            $validated['company_id'] = $user->company_id;
        }

        // Auto-set user_id for customers
        if ($roleKey === 'customer') {
            $validated['user_id'] = $user->id;
        }

        DB::beginTransaction();
        try {
            $newCustomerCreated = false;
            $generatedPassword = null;
            $customerUser = null;

            // Handle customer creation for admin users
            if (in_array($roleKey, ['master_admin', 'company_admin']) && empty($validated['user_id'])) {
                // Check if customer account should be created
                if ($request->has('create_customer_account') && $request->create_customer_account) {
                    // Check if user already exists with this email
                    $existingUser = User::where('email', $validated['email'])->first();

                    if ($existingUser) {
                        // Use existing user
                        $validated['user_id'] = $existingUser->id;
                        $customerUser = $existingUser;
                    } else {
                        // Create new customer user
                        $generatedPassword = $this->generateSecurePassword();
                        $customerRole = \App\Models\Role::where('role_key', 'customer')->first();

                        if (!$customerRole) {
                            throw new \Exception('Customer role not found in system');
                        }

                        $customerUser = User::create([
                            'name' => $validated['name'] . ' ' . $validated['surname'],
                            'email' => $validated['email'],
                            'password' => bcrypt($generatedPassword),
                            'role_id' => $customerRole->id,
                            'company_id' => $validated['company_id'] ?? ($roleKey === 'company_admin' ? $user->company_id : null),
                            'email_verified_at' => now(), // Auto-verify since created by admin
                        ]);

                        $validated['user_id'] = $customerUser->id;
                        $newCustomerCreated = true;

                        // Log customer creation
                        ActivityLog::log(
                            'created',
                            $customerUser,
                            'Customer account created via booking creation by ' . $user->name,
                            ['created_by' => $user->id, 'customer_id' => $customerUser->id]
                        );
                    }
                } else {
                    // No user selected and not creating account - validation error
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Please select a customer or enable "Create Customer Account" option.');
                }
            }

            // Set initial status
            $validated['status'] = 'pending';

            // If artist is assigned immediately, update status
            if (!empty($validated['assigned_artist_id'])) {
                $validated['status'] = 'confirmed';
                $validated['confirmed_at'] = now();
            }

            // Remove the create_customer_account flag before creating booking
            unset($validated['create_customer_account']);

            $booking = BookingRequest::create($validated);

            // Log booking creation
            ActivityLog::log(
                'created',
                $booking,
                'Created new booking request for ' . $booking->name . ' ' . $booking->surname,
                ['booking_id' => $booking->id, 'status' => $booking->status, 'new_customer' => $newCustomerCreated]
            );

            // Send appropriate emails
            if ($newCustomerCreated && $customerUser && $generatedPassword) {
                // Send account creation email with credentials
                Mail::to($customerUser->email)->send(new \App\Mail\CustomerAccountCreated($customerUser, $generatedPassword, $booking));
            } elseif ($booking->user && $booking->user->email) {
                // Send standard booking confirmation to existing customer
                Mail::to($booking->user->email)->send(new \App\Mail\BookingCreated($booking));
            }

            // Fire booking created event if company is assigned
            if ($booking->company_id && $booking->company) {
                event(new BookingCreated($booking, $booking->company));

                // Send email to company admin if master admin created this booking
                if ($roleKey === 'master_admin') {
                    $companyAdmin = User::where('company_id', $booking->company_id)
                        ->whereHas('role', function($q) {
                            $q->where('role_key', 'company_admin');
                        })->first();

                    if ($companyAdmin && $companyAdmin->email) {
                        try {
                            \Mail::to($companyAdmin->email)->send(
                                new \App\Mail\NewBookingForCompany($booking, $companyAdmin)
                            );
                        } catch (\Exception $e) {
                            \Log::error('Failed to send booking notification to company admin: ' . $e->getMessage());
                        }
                    }
                }
            }

            DB::commit();

            $successMessage = 'Booking created successfully!';
            if ($newCustomerCreated) {
                $successMessage .= ' A new customer account has been created and login credentials have been sent to ' . $customerUser->email;
            }

            return redirect()->route('bookings.index')->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error to database
            \App\Services\ErrorLogger::log($e, 'booking_creation_error', 'error', [
                'user_id' => Auth::id(),
                'action' => 'create_booking',
                'validated_data' => $validated ?? null,
            ]);

            return back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Generate a secure random password
     */
    private function generateSecurePassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $special = '!@#$%^&*';

        $allChars = $uppercase . $lowercase . $numbers . $special;

        // Ensure at least one of each type
        $password = '';
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];

        // Fill the rest randomly
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Shuffle the password
        return str_shuffle($password);
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

            // Log error to database
            \App\Services\ErrorLogger::log($e, 'booking_update_error', 'error', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'action' => 'update_booking',
            ]);

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
            $isReassignment = $booking->assigned_artist_id !== null;
            $previousArtist = $isReassignment ? Artist::find($booking->assigned_artist_id) : null;

            // Assign artist but keep status as PENDING (waiting for artist acceptance)
            $booking->update([
                'assigned_artist_id' => $request->artist_id,
                'status' => 'pending',
                'confirmed_at' => null,
                'company_notes' => $request->company_notes ?? $booking->company_notes
            ]);

            $artist = Artist::find($request->artist_id);

            ActivityLog::log(
                'updated',
                $booking,
                ($isReassignment ? 'Artist reassigned' : 'Artist assigned') . ' to booking: ' . $artist->user->name,
                ['booking_id' => $booking->id, 'artist_id' => $request->artist_id, 'is_reassignment' => $isReassignment]
            );

            // Send email to artist about new booking assignment
            if ($artist->user && $artist->user->email) {
                try {
                    \Mail::to($artist->user->email)->send(
                        new \App\Mail\NewBookingForArtist($booking->fresh())
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send assignment email to artist: ' . $e->getMessage());
                }
            }

            // Send email to company admin if master admin assigned the artist
            if ($roleKey === 'master_admin' && $booking->company_id) {
                $companyAdmin = User::where('company_id', $booking->company_id)
                    ->whereHas('role', function($q) {
                        $q->where('role_key', 'company_admin');
                    })->first();

                if ($companyAdmin && $companyAdmin->email) {
                    try {
                        \Mail::to($companyAdmin->email)->send(
                            new \App\Mail\ArtistAssignedToCompany($booking->fresh(), $artist, $companyAdmin)
                        );
                    } catch (\Exception $e) {
                        \Log::error('Failed to send artist assignment notification to company admin: ' . $e->getMessage());
                    }
                }
            }

            // Send email to customer about artist assignment
            if ($booking->user && $booking->user->email) {
                try {
                    \Mail::to($booking->user->email)->send(
                        new \App\Mail\ArtistAssigned($booking->fresh(), $isReassignment)
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send assignment email to customer: ' . $e->getMessage());
                }
            }

            // TODO: Notify previous artist if reassignment
            // Requires BookingReassigned mail class to be created
            /*
            if ($isReassignment && $previousArtist && $previousArtist->user && $previousArtist->user->email) {
                try {
                    \Mail::to($previousArtist->user->email)->send(
                        new \App\Mail\BookingReassigned($booking->fresh(), $previousArtist->user->name)
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send reassignment notification: ' . $e->getMessage());
                }
            }
            */

            DB::commit();

            return back()->with('success', 'Artist assigned successfully! The artist will be notified to accept or reject the booking.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error to database
            \App\Services\ErrorLogger::log($e, 'artist_assignment_error', 'error', [
                'booking_id' => $booking->id,
                'artist_id' => $request->artist_id ?? null,
                'user_id' => Auth::id(),
            ]);

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
