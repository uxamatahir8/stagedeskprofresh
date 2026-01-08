<?php
namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
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

        return view('dashboard.pages.bookings.manage', compact(
            'title',
            'mode',
            'customers'
        ));
    }

}
