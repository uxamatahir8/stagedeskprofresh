<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\Company;
use App\Models\EventType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_bookings_index(): void
    {
        $response = $this->get(route('bookings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_booking_show(): void
    {
        $eventType = EventType::create(['event_type' => 'Party']);
        $role = Role::create([
            'name' => 'Customer',
            'description' => 'Customer',
            'role_key' => 'customer',
        ]);
        $company = Company::create([
            'name' => 'Test Co',
            'email' => 'test@test.com',
            'status' => 'active',
        ]);
        $user = User::create([
            'name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'company_id' => $company->id,
        ]);
        $booking = BookingRequest::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'status' => 'pending',
            'event_type_id' => $eventType->id,
            'name' => 'C',
            'surname' => 'N',
            'date_of_birth' => '1990-01-01',
            'phone' => '123',
            'email' => 'c@test.com',
            'address' => 'Addr',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response = $this->get(route('bookings.show', $booking));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_bookings_index(): void
    {
        $role = Role::create([
            'name' => 'Master Admin',
            'description' => 'Admin',
            'role_key' => 'master_admin',
        ]);
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'company_id' => null,
        ]);

        $response = $this->actingAs($user)->get(route('bookings.index'));

        $response->assertOk();
    }
}
