<?php
namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\EventType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function show(BookingRequest $booking)
    {
        $title = 'Booking Details';
        return view('dashboard.pages.bookings.show', compact('title', 'booking'));
    }

    //

    public function index()
    {
        $title = 'Booking Requests';

        $roleKey = Auth::user()->role->role_key;

        $bookingResolvers = [
            'master_admin'  => fn()  => BookingRequest::query(),
            'company_admin' => fn() => BookingRequest::companyBookings(),
            'customer'      => fn()      => BookingRequest::userBookings(),
        ];

        $bookings = isset($bookingResolvers[$roleKey])
            ? $bookingResolvers[$roleKey]()->get()
            : collect();

        return view('dashboard.pages.bookings.index', compact('title', 'bookings'));
    }

    public function create()
    {
        $title = 'Create Booking Request';
        $mode  = 'create';

        $roleKey = Auth::user()->role->role_key;

        $customers = match ($roleKey) {
            'company_admin' => User::companyCustomers()->get(),
            default         => User::allCustomers()->get(),
        };

        $event_types = EventType::all();

        return view('dashboard.pages.bookings.manage', compact(
            'title',
            'mode',
            'customers',
            'event_types'
        ));
    }

    public function edit(BookingRequest $booking)
    {
        $title = 'Edit Booking Request';
        $mode  = 'edit';

        $roleKey = Auth::user()->role->role_key;

        $customers = match ($roleKey) {
            'company_admin' => User::companyCustomers()->get(),
            default         => User::allCustomers()->get(),
        };

        $event_types = EventType::all();

        return view('dashboard.pages.bookings.manage', compact(
            'title',
            'mode',
            'customers',
            'event_types',
            'booking'
        ));
    }

    public function store(Request $request)
    {
        $eventType = EventType::find($request->event_type_id);
        $isWedding = $eventType && str_contains(strtolower($eventType->event_type), 'wedding');

        $validated = $request->validate([
            'event_date'       => 'required|date|after:today',
            'user_id'          => 'required|exists:users,id',
            'event_type_id'    => 'required|exists:event_types,id',
            'name'             => 'required|string|max:255',
            'surname'          => 'required|string|max:255',
            'date_of_birth'    => 'required|date|before:' . now()->subDays(5)->format('Y-m-d'),
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|max:255',
            'address'          => 'required|string|max:255',
            'partner_name'     => $isWedding ? 'required|string|max:255' : 'nullable|string|max:255',
            'wedding_date'     => $isWedding ? 'required|date|after:today' : 'nullable|date',
            'wedding_time'     => $isWedding ? 'required|string' : 'nullable|string',
            'dos'              => 'nullable|string',
            'donts'            => 'nullable|string',
            'playlist_spotify' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        BookingRequest::create($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking saved successfully.');
    }

    public function update(Request $request, BookingRequest $booking)
    {
        $eventType = EventType::find($request->event_type_id);
        $isWedding = $eventType && str_contains(strtolower($eventType->event_type), 'wedding');

        $validated = $request->validate([
            'event_date'       => 'required|date|after:today',
            'user_id'          => 'required|exists:users,id',
            'event_type_id'    => 'required|exists:event_types,id',
            'name'             => 'required|string|max:255',
            'surname'          => 'required|string|max:255',
            'date_of_birth'    => 'required|date|before:' . now()->subDays(5)->format('Y-m-d'),
            'phone'            => 'required|string|max:20',
            'email'            => 'required|email|max:255',
            'address'          => 'required|string|max:255',
            'partner_name'     => $isWedding ? 'required|string|max:255' : 'nullable|string|max:255',
            'wedding_date'     => $isWedding ? 'required|date|after:today' : 'nullable|date',
            'wedding_time'     => $isWedding ? 'required|string' : 'nullable|string',
            'dos'              => 'nullable|string',
            'donts'            => 'nullable|string',
            'playlist_spotify' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(BookingRequest $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

}
