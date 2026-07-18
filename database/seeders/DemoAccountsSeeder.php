<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Payment;
use App\Models\Package;
use App\Models\Artist;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DemoAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Demo Accounts Seeder...');

        // 1. Seed Packages if not already present
        $this->seedPackages();

        // 2. Fetch role IDs dynamically from the database
        $roleIds = DB::table('roles')->pluck('id', 'role_key')->all();
        if (empty($roleIds)) {
            $this->command->error('Roles table is empty. Please run UserRolesSeeder first.');
            return;
        }

        // 3. Define Demo Companies data
        $demoData = [
            [
                'company' => [
                    'name' => 'VibeStream Productions',
                    'email' => 'vibestream@example.com',
                    'phone' => '+31698765432',
                    'website' => 'https://vibestream.example.com',
                    'kvk_number' => '12345678',
                    'contact_name' => 'Alice Johnson',
                    'contact_phone' => '+31698765432',
                    'contact_email' => 'alice@vibestream.com',
                    'status' => 'active',
                    'address' => 'Prinsengracht 123, Amsterdam',
                    'is_verified' => true,
                    'verified_at' => now(),
                ],
                'admin' => [
                    'name' => 'VibeStream Admin',
                    'email' => 'admin@vibestream.com',
                    'password' => 'Password123',
                ],
                'artist' => [
                    'name' => 'DJ Vibe',
                    'email' => 'artist@vibestream.com',
                    'password' => 'Password123',
                    'artist_details' => [
                        'stage_name' => 'DJ Vibe',
                        'experience_years' => '6',
                        'genres' => 'Electronic, House, Techno',
                        'specialization' => 'DJ',
                        'rating' => '4.9',
                        'bio' => 'Resident DJ specializing in progressive house and techno sets.',
                        'share_percentage' => '75.00',
                    ]
                ],
                'customer' => [
                    'name' => 'John Vibe',
                    'email' => 'customer@vibestream.com',
                    'password' => 'Password123',
                ]
            ],
            [
                'company' => [
                    'name' => 'MelodyLine Events',
                    'email' => 'melodyline@example.com',
                    'phone' => '+31687654321',
                    'website' => 'https://melodyline.example.com',
                    'kvk_number' => '87654321',
                    'contact_name' => 'Bob Miller',
                    'contact_phone' => '+31687654321',
                    'contact_email' => 'bob@melodyline.com',
                    'status' => 'active',
                    'address' => 'Keizersgracht 456, Amsterdam',
                    'is_verified' => true,
                    'verified_at' => now(),
                ],
                'admin' => [
                    'name' => 'MelodyLine Admin',
                    'email' => 'admin@melodyline.com',
                    'password' => 'Password123',
                ],
                'artist' => [
                    'name' => 'Melody Star',
                    'email' => 'artist@melodyline.com',
                    'password' => 'Password123',
                    'artist_details' => [
                        'stage_name' => 'Melody Star',
                        'experience_years' => '8',
                        'genres' => 'Jazz, Soul, R&B',
                        'specialization' => 'Singer',
                        'rating' => '4.8',
                        'bio' => 'Professional vocalist delivering soulful performances for premium events.',
                        'share_percentage' => '80.00',
                    ]
                ],
                'customer' => [
                    'name' => 'Mary Melody',
                    'email' => 'customer@melodyline.com',
                    'password' => 'Password123',
                ]
            ]
        ];

        // 4. Fetch locations for Profile records
        $countryId = DB::table('countries')->exists() ? DB::table('countries')->min('id') : null;
        $stateId = DB::table('states')->exists() ? DB::table('states')->min('id') : null;
        $cityId = DB::table('cities')->exists() ? DB::table('cities')->min('id') : null;

        $createdAccounts = [];

        foreach ($demoData as $data) {
            $companyInfo = $data['company'];

            // Clean up existing company data to ensure idempotency
            $existingCompany = Company::where('email', $companyInfo['email'])->first();
            if ($existingCompany) {
                $this->command->warn("Company '{$companyInfo['name']}' already exists. Deleting it and its related models...");
                // Eloquent relationships will be soft/force deleted accordingly
                $existingCompany->users()->forceDelete();
                $existingCompany->subscriptions()->delete();
                $existingCompany->forceDelete();
            }

            // Create Company
            $company = Company::create($companyInfo);
            $this->command->info("Created Company: {$company->name}");

            // Assign Active Subscription
            $package = Package::where('name', 'Basic Monthly')->first();
            if ($package) {
                $sub = CompanySubscription::create([
                    'company_id' => $company->id,
                    'package_id' => $package->id,
                    'start_date' => now(),
                    'end_date' => now()->addMonth(),
                    'status' => 'active',
                    'auto_renew' => true,
                ]);

                // Create a completed Payment for this subscription (required for company admin login)
                Payment::create([
                    'subscription_id' => $sub->id,
                    'user_id' => null, // Will set below after admin user is created
                    'amount' => $package->price,
                    'currency' => 'EUR',
                    'transaction_id' => 'TXN-' . Str::random(12),
                    'payment_method' => 'card',
                    'type' => 'subscription',
                    'status' => 'completed',
                    'verified_at' => now(),
                ]);
            }

            // Create Company Admin
            $adminData = $data['admin'];
            $adminUser = User::create([
                'role_id' => $roleIds['company_admin'],
                'company_id' => $company->id,
                'name' => $adminData['name'],
                'email' => $adminData['email'],
                'password' => $adminData['password'], // hashed by Eloquent cast
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Link payment to admin user
            if (isset($sub)) {
                Payment::where('subscription_id', $sub->id)->update(['user_id' => $adminUser->id]);
            }

            // Create UserProfile for Admin
            UserProfile::create([
                'user_id' => $adminUser->id,
                'phone' => $companyInfo['phone'],
                'address' => $companyInfo['address'],
                'zipcode' => '1000AA',
                'about' => 'Administrator for ' . $company->name,
                'country_id' => $countryId,
                'state_id' => $stateId,
                'city_id' => $cityId,
            ]);

            $createdAccounts[] = [
                'role' => 'Company Admin',
                'company' => $company->name,
                'name' => $adminUser->name,
                'email' => $adminUser->email,
                'password' => $adminData['password'],
            ];

            // Create Artist User
            $artistData = $data['artist'];
            $artistUser = User::create([
                'role_id' => $roleIds['artist'],
                'company_id' => $company->id,
                'name' => $artistData['name'],
                'email' => $artistData['email'],
                'password' => $artistData['password'],
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Create Artist Profile
            UserProfile::create([
                'user_id' => $artistUser->id,
                'phone' => '+31655551212',
                'address' => $companyInfo['address'],
                'zipcode' => '1000AA',
                'about' => $artistData['artist_details']['bio'],
                'country_id' => $countryId,
                'state_id' => $stateId,
                'city_id' => $cityId,
            ]);

            // Create Artist table record
            $artistDetails = $artistData['artist_details'];
            Artist::create([
                'company_id' => $company->id,
                'user_id' => $artistUser->id,
                'stage_name' => $artistDetails['stage_name'],
                'experience_years' => $artistDetails['experience_years'],
                'genres' => $artistDetails['genres'],
                'specialization' => $artistDetails['specialization'],
                'rating' => $artistDetails['rating'],
                'bio' => $artistDetails['bio'],
                'share_percentage' => $artistDetails['share_percentage'],
            ]);

            $createdAccounts[] = [
                'role' => 'Artist',
                'company' => $company->name,
                'name' => $artistUser->name,
                'email' => $artistUser->email,
                'password' => $artistData['password'],
                'details' => $artistDetails,
            ];

            // Create Customer User
            $customerData = $data['customer'];
            $customerUser = User::create([
                'role_id' => $roleIds['customer'],
                'company_id' => $company->id,
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => $customerData['password'],
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Create UserProfile for Customer
            UserProfile::create([
                'user_id' => $customerUser->id,
                'phone' => '+31655551313',
                'address' => $companyInfo['address'],
                'zipcode' => '1000AA',
                'about' => 'Customer of ' . $company->name,
                'country_id' => $countryId,
                'state_id' => $stateId,
                'city_id' => $cityId,
            ]);

            $createdAccounts[] = [
                'role' => 'Customer',
                'company' => $company->name,
                'name' => $customerUser->name,
                'email' => $customerUser->email,
                'password' => $customerData['password'],
            ];
        }

        // 5. Output detailed login information
        $this->printAndLogCreatedAccounts($createdAccounts);
    }

    /**
     * Seeds the standard packages if the packages table is currently empty.
     */
    protected function seedPackages(): void
    {
        if (DB::table('packages')->exists()) {
            return;
        }

        $packages = [
            ['name' => 'Starter', 'description' => 'Free starter package to get you going.', 'price' => '0', 'duration_type' => 'monthly', 'max_users_allowed' => 2, 'max_requests_allowed' => 5, 'max_responses_allowed' => 3, 'status' => 'active'],
            ['name' => 'Basic Monthly', 'description' => 'Essential features for small teams.', 'price' => '49', 'duration_type' => 'monthly', 'max_users_allowed' => 5, 'max_requests_allowed' => 25, 'max_responses_allowed' => 15, 'status' => 'active'],
            ['name' => 'Pro Monthly', 'description' => 'Advanced features for growing businesses.', 'price' => '99', 'duration_type' => 'monthly', 'max_users_allowed' => 15, 'max_requests_allowed' => 100, 'max_responses_allowed' => 50, 'status' => 'active'],
            ['name' => 'Standard Yearly', 'description' => 'Best value when billed annually.', 'price' => '399', 'duration_type' => 'yearly', 'max_users_allowed' => 5, 'max_requests_allowed' => 300, 'max_responses_allowed' => 150, 'status' => 'active'],
            ['name' => 'Growth Yearly', 'description' => 'For teams that scale.', 'price' => '799', 'duration_type' => 'yearly', 'max_users_allowed' => 20, 'max_requests_allowed' => 1000, 'max_responses_allowed' => 500, 'status' => 'active'],
            ['name' => 'Enterprise Yearly', 'description' => 'Unlimited usage for large organizations.', 'price' => '1499', 'duration_type' => 'yearly', 'max_users_allowed' => null, 'max_requests_allowed' => null, 'max_responses_allowed' => null, 'status' => 'active'],
        ];

        foreach ($packages as $p) {
            Package::create($p);
        }

        $this->command->info('Standard subscription packages seeded.');
    }

    /**
     * Logs the created credentials to laravel.log and outputs them to the console and a local markdown file.
     */
    protected function printAndLogCreatedAccounts(array $accounts): void
    {
        $logHeader = "\n=============================================\n" .
                     "         DEMO ACCOUNTS LOGIN DETAILS         \n" .
                     "=============================================\n";
        $logContent = "";

        $markdownContent = "# Demo Accounts Login Credentials\n\n" .
                           "This file contains the automatically generated credentials for the 2 demo companies, including admins, artists, and customers.\n\n" .
                           "| Company | Role | Name | Email | Password |\n" .
                           "| --- | --- | --- | --- | --- |\n";

        foreach ($accounts as $acc) {
            $logContent .= sprintf(
                "Company:  %s\nRole:     %s\nName:     %s\nEmail:    %s\nPassword: %s\n---------------------------------------------\n",
                $acc['company'],
                $acc['role'],
                $acc['name'],
                $acc['email'],
                $acc['password']
            );

            $markdownContent .= sprintf(
                "| %s | %s | %s | **%s** | `%s` |\n",
                $acc['company'],
                $acc['role'],
                $acc['name'],
                $acc['email'],
                $acc['password']
            );
        }

        // Print to console
        $this->command->info($logHeader . $logContent);

        // Write to Laravel Logs
        Log::info($logHeader . $logContent);

        // Write to a local markdown file in the project root
        $outputPath = base_path('demo_accounts.md');
        file_put_contents($outputPath, $markdownContent);
        $this->command->info("Login details saved to [demo_accounts.md](file:///c:/xampp/htdocs/stagedeskprofresh/demo_accounts.md)");
    }
}
