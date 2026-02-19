<?php

namespace Tests\Unit;

use App\Models\Artist;
use App\Models\BookingRequest;
use App\Models\Company;
use App\Models\EventType;
use App\Models\Role;
use App\Models\User;
use App\Policies\BookingRequestPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingRequestPolicyTest extends TestCase
{
    use RefreshDatabase;

    private Role $masterAdminRole;
    private Role $companyAdminRole;
    private Role $customerRole;
    private Role $artistRole;
    private Company $company;
    private EventType $eventType;
    private User $masterAdmin;
    private User $companyAdmin;
    private User $customer;
    private User $artistUser;
    private Artist $artist;
    private BookingRequest $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->masterAdminRole = Role::create([
            'name' => 'Master Admin',
            'description' => 'Platform administrator',
            'role_key' => 'master_admin',
        ]);
        $this->companyAdminRole = Role::create([
            'name' => 'Company Admin',
            'description' => 'Company administrator',
            'role_key' => 'company_admin',
        ]);
        $this->customerRole = Role::create([
            'name' => 'Customer',
            'description' => 'Customer',
            'role_key' => 'customer',
        ]);
        $this->artistRole = Role::create([
            'name' => 'Artist',
            'description' => 'Artist',
            'role_key' => 'artist',
        ]);

        $this->company = Company::create([
            'name' => 'Test Company',
            'email' => 'company@test.com',
            'status' => 'active',
        ]);

        $this->eventType = EventType::create([
            'event_type' => 'Party',
        ]);

        $this->masterAdmin = User::create([
            'name' => 'Master Admin',
            'email' => 'master@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->masterAdminRole->id,
            'company_id' => null,
        ]);

        $this->companyAdmin = User::create([
            'name' => 'Company Admin',
            'email' => 'companyadmin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->companyAdminRole->id,
            'company_id' => $this->company->id,
        ]);

        $this->customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->customerRole->id,
            'company_id' => $this->company->id,
        ]);

        $this->artistUser = User::create([
            'name' => 'Test Artist',
            'email' => 'artist@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->artistRole->id,
            'company_id' => $this->company->id,
        ]);

        $this->artist = Artist::create([
            'user_id' => $this->artistUser->id,
            'company_id' => $this->company->id,
            'stage_name' => 'Test Artist',
            'experience_years' => '5',
            'genres' => 'Pop',
            'specialization' => 'DJ',
            'rating' => '5',
        ]);

        $this->booking = BookingRequest::create([
            'user_id' => $this->customer->id,
            'company_id' => $this->company->id,
            'assigned_artist_id' => $this->artist->id,
            'status' => 'pending',
            'event_type_id' => $this->eventType->id,
            'name' => 'Customer',
            'surname' => 'Name',
            'date_of_birth' => '1990-01-01',
            'phone' => '1234567890',
            'email' => 'customer@test.com',
            'address' => '123 Test St',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);
    }

    public function test_master_admin_can_view_any_booking(): void
    {
        $policy = new BookingRequestPolicy;
        $this->assertTrue($policy->view($this->masterAdmin, $this->booking));
    }

    public function test_company_admin_can_view_booking_from_own_company(): void
    {
        $policy = new BookingRequestPolicy;
        $this->assertTrue($policy->view($this->companyAdmin, $this->booking));
    }

    public function test_company_admin_cannot_view_booking_from_other_company(): void
    {
        $otherCompany = Company::create(['name' => 'Other', 'email' => 'other@test.com', 'status' => 'active']);
        $otherBooking = BookingRequest::create([
            'user_id' => $this->customer->id,
            'company_id' => $otherCompany->id,
            'status' => 'pending',
            'event_type_id' => $this->eventType->id,
            'name' => 'C',
            'surname' => 'N',
            'date_of_birth' => '1990-01-01',
            'phone' => '123',
            'email' => 'c@test.com',
            'address' => 'Addr',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);
        $policy = new BookingRequestPolicy;
        $this->assertFalse($policy->view($this->companyAdmin, $otherBooking));
    }

    public function test_customer_can_view_own_booking(): void
    {
        $policy = new BookingRequestPolicy;
        $this->assertTrue($policy->view($this->customer, $this->booking));
    }

    public function test_customer_cannot_view_other_booking(): void
    {
        $otherCustomer = User::create([
            'name' => 'Other Customer',
            'email' => 'othercustomer@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->customerRole->id,
            'company_id' => $this->company->id,
        ]);
        $otherBooking = BookingRequest::create([
            'user_id' => $otherCustomer->id,
            'company_id' => $this->company->id,
            'status' => 'pending',
            'event_type_id' => $this->eventType->id,
            'name' => 'O',
            'surname' => 'C',
            'date_of_birth' => '1990-01-01',
            'phone' => '123',
            'email' => 'oc@test.com',
            'address' => 'Addr',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);
        $policy = new BookingRequestPolicy;
        $this->assertFalse($policy->view($this->customer, $otherBooking));
    }

    public function test_artist_can_view_assigned_booking(): void
    {
        $policy = new BookingRequestPolicy;
        $this->assertTrue($policy->view($this->artistUser, $this->booking));
    }

    public function test_artist_cannot_view_unassigned_booking(): void
    {
        $unassignedBooking = BookingRequest::create([
            'user_id' => $this->customer->id,
            'company_id' => $this->company->id,
            'assigned_artist_id' => null,
            'status' => 'pending',
            'event_type_id' => $this->eventType->id,
            'name' => 'C',
            'surname' => 'N',
            'date_of_birth' => '1990-01-01',
            'phone' => '123',
            'email' => 'c@test.com',
            'address' => 'Addr',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);
        $policy = new BookingRequestPolicy;
        $this->assertFalse($policy->view($this->artistUser, $unassignedBooking));
    }

    public function test_update_denied_for_completed_booking(): void
    {
        $this->booking->update(['status' => 'completed']);
        $policy = new BookingRequestPolicy;
        $this->assertFalse($policy->update($this->companyAdmin, $this->booking));
    }

    public function test_delete_only_allowed_for_master_and_company_admin(): void
    {
        $policy = new BookingRequestPolicy;
        $this->assertTrue($policy->delete($this->masterAdmin, $this->booking));
        $this->assertTrue($policy->delete($this->companyAdmin, $this->booking));
        $this->assertFalse($policy->delete($this->customer, $this->booking));
        $this->assertFalse($policy->delete($this->artistUser, $this->booking));
    }

    public function test_cancel_denied_for_completed_booking(): void
    {
        $this->booking->update(['status' => 'completed']);
        $policy = new BookingRequestPolicy;
        $this->assertFalse($policy->cancel($this->customer, $this->booking));
    }
}
