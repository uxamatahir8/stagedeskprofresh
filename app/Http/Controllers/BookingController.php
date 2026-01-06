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

        $user_role = Auth::user()->role->role_key;

        switch ($user_role) {
            case 'master_admin':
                $bookings = BookingRequest::all();
                break;
            case 'company_admin':
                $bookings = BookingRequest::companyBookings()->get();
                break;
            case 'customer':
                $bookings = BookingRequest::userBookings()->get();
                break;
            default:
                $bookings = collect(); // empty collection for unauthorized roles
                break;
        }

        return view('dashboard.pages.bookings.index', compact('title', 'bookings'));

    }

    public function create()
    {
        //
        $title = 'Create Booking Request';
        $mode  = 'create';

        $customers = Auth::user()->role->role_key == 'company_admin'
            ? User::companyCustomers()->get()
            : User::allCustomers()->get();

        return view('dashboard.pages.bookings.manage', compact('title', 'mode', 'customers'));
    }
}
