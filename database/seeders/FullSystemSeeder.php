<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Affiliate;
use App\Models\AffiliateComission;
use App\Models\Artist;
use App\Models\ArtistEarning;
use App\Models\ArtistRequest;
use App\Models\ArtistWithdrawalRequest;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BookedService;
use App\Models\BookingRequest;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\EventType;
use App\Models\Notification;
use App\Models\Package;
use App\Models\PackageFeatures;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Role;
use App\Models\SharedArtist;
use App\Models\SocialLink;
use App\Models\SupportTicket;
use App\Models\Testimonials;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;

class FullSystemSeeder extends Seeder
{
    /** Date range for generated data (YYYY-MM-DD). */
    protected string $startDate = '2022-05-02';
    protected string $endDate = '2026-02-26';

    /** Counts (configurable). */
    protected int $companiesCount = 3520;
    protected int $artistsCount = 5975;
    protected int $affiliatesCount = 200;
    protected int $bookingsCount = 45751;
    protected int $customersPerCompany = 154; // 200 * 4 = 800 customers
    protected int $blogsCount = 200;
    protected int $testimonialsCount = 100;
    protected int $notificationsCount = 2000;
    protected int $reviewsCount = 2000;
    protected int $bookedServicesPerBooking = 2;
    protected int $artistRequestsPerBooking = 9;
    protected int $socialLinksPerCompany = 4;
    protected int $affiliateCommissionsCount = 500;
    protected int $artistEarningsCount = 2000;
    protected int $artistWithdrawalsCount = 300;
    protected int $commentsCount = 600;
    protected int $supportTicketsCount = 300;
    protected int $sharedArtistsCount = 350;
    protected int $activityLogsCount = 1500;
    protected int $packageFeaturesPerPackage = 8;

    /** Cached IDs (populated during run). */
    protected array $roleIds = [];
    protected array $packageIds = [];
    protected array $eventTypeIds = [];
    protected array $blogCategoryIds = [];
    protected array $companyIds = [];
    /** @var array<int, array{id: int, company_id: int}> */
    protected array $customerUsers = [];
    /** @var array<int, int> artist_id => company_id */
    protected array $artistCompanyMap = [];
    protected array $bookingIds = [];
    protected array $subscriptionIds = [];
    /** @var array<int, array{user_id: int, company_id: int}> */
    protected array $artistUserIds = [];
    /** @var array<int, int> */
    protected array $affiliateUserIds = [];

    public function run(): void
    {
        @ini_set('memory_limit', '1G');

        $start = Carbon::parse($this->startDate)->startOfDay();

        $this->command->info('Seeding roles…');
        $this->seedRoles();

        $this->command->info('Seeding packages…');
        $this->seedPackages();

        $this->command->info('Seeding event types…');
        $this->seedEventTypes();

        $this->command->info('Seeding blog categories…');
        $this->seedBlogCategories();

        $this->command->info('Seeding package features…');
        $this->seedPackageFeatures();

        $this->command->info('Seeding companies and users (admins, artists, customers, affiliates)…');
        $this->seedCompaniesAndUsers($start);

        $this->command->info('Seeding payment methods (master + company)…');
        $this->seedPaymentMethods();

        $this->command->info('Seeding company subscriptions and payments…');
        $this->seedCompanySubscriptionsAndPayments($start);

        $this->command->info('Seeding affiliates…');
        $this->seedAffiliates();

        $this->command->info('Seeding artists…');
        $this->seedArtists($start);

        $this->command->info('Seeding social links…');
        $this->seedSocialLinks();

        $this->command->info('Seeding shared artists…');
        $this->seedSharedArtists($start);

        $this->command->info('Seeding booking requests…');
        $this->seedBookingRequests($start);

        $this->command->info('Seeding booked services…');
        $this->seedBookedServices();

        $this->command->info('Seeding artist requests…');
        $this->seedArtistRequests();

        $this->command->info('Seeding payments (booking payments)…');
        $this->seedBookingPayments($start);

        $this->command->info('Seeding reviews…');
        $this->seedReviews($start);

        $this->command->info('Seeding affiliate commissions…');
        $this->seedAffiliateCommissions($start);

        $this->command->info('Seeding artist earnings…');
        $this->seedArtistEarnings($start);

        $this->command->info('Seeding artist withdrawal requests…');
        $this->seedArtistWithdrawalRequests($start);

        $this->command->info('Seeding blogs…');
        $this->seedBlogs($start);

        $this->command->info('Seeding testimonials…');
        $this->seedTestimonials($start);

        $this->command->info('Seeding notifications…');
        $this->seedNotifications($start);

        $this->command->info('Seeding comments…');
        $this->seedComments($start);

        $this->command->info('Seeding support tickets…');
        $this->seedSupportTickets($start);

        $this->command->info('Seeding activity logs…');
        $this->seedActivityLogs($start);

        $this->command->info('Full system seed completed.');
    }

    /** Number of days between start and end date. */
    protected function daysSpread(): int
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();
        return (int) $start->diffInDays($end) + 1;
    }

    /** Random date/time between start and end (inclusive). Uses timestamps so range is guaranteed 2022–2026. */
    protected function randomDateInRange(Carbon $start): Carbon
    {
        $startTs = Carbon::parse($this->startDate)->startOfDay()->timestamp;
        $endTs = Carbon::parse($this->endDate)->endOfDay()->timestamp;
        if ($endTs <= $startTs) {
            return $start->copy();
        }
        $randomTs = rand($startTs, $endTs);
        return Carbon::createFromTimestamp($randomTs);
    }

    protected function progressBar(int $max): ProgressBar
    {
        $bar = new ProgressBar($this->command->getOutput(), max(1, $max));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%');
        return $bar;
    }

    protected function seedRoles(): void
    {
        $roles = [
            ['name' => 'Master Admin', 'role_key' => 'master_admin', 'description' => 'Master Admin'],
            ['name' => 'Company Admin', 'role_key' => 'company_admin', 'description' => 'Company Admin'],
            ['name' => 'Customer', 'role_key' => 'customer', 'description' => 'Customer'],
            ['name' => 'Artist', 'role_key' => 'artist', 'description' => 'Artist'],
            ['name' => 'Affiliate', 'role_key' => 'affiliate', 'description' => 'Affiliate'],
        ];
        $bar = $this->progressBar(count($roles));
        $bar->start();
        foreach ($roles as $r) {
            $role = Role::firstOrCreate(['role_key' => $r['role_key']], $r);
            $this->roleIds[$r['role_key']] = $role->id;
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedPackages(): void
    {
        $packages = [
            // 3 monthly packages (1 free/starter)
            ['name' => 'Starter', 'description' => 'Free starter package to get you going.', 'price' => '0', 'duration_type' => 'monthly', 'max_users_allowed' => 2, 'max_requests_allowed' => 5, 'max_responses_allowed' => 3, 'status' => 'active'],
            ['name' => 'Basic Monthly', 'description' => 'Essential features for small teams.', 'price' => '49', 'duration_type' => 'monthly', 'max_users_allowed' => 5, 'max_requests_allowed' => 25, 'max_responses_allowed' => 15, 'status' => 'active'],
            ['name' => 'Pro Monthly', 'description' => 'Advanced features for growing businesses.', 'price' => '99', 'duration_type' => 'monthly', 'max_users_allowed' => 15, 'max_requests_allowed' => 100, 'max_responses_allowed' => 50, 'status' => 'active'],
            // 3 yearly packages
            ['name' => 'Standard Yearly', 'description' => 'Best value when billed annually.', 'price' => '399', 'duration_type' => 'yearly', 'max_users_allowed' => 5, 'max_requests_allowed' => 300, 'max_responses_allowed' => 150, 'status' => 'active'],
            ['name' => 'Growth Yearly', 'description' => 'For teams that scale.', 'price' => '799', 'duration_type' => 'yearly', 'max_users_allowed' => 20, 'max_requests_allowed' => 1000, 'max_responses_allowed' => 500, 'status' => 'active'],
            ['name' => 'Enterprise Yearly', 'description' => 'Unlimited usage for large organizations.', 'price' => '1499', 'duration_type' => 'yearly', 'max_users_allowed' => null, 'max_requests_allowed' => null, 'max_responses_allowed' => null, 'status' => 'active'],
        ];
        $bar = $this->progressBar(count($packages));
        $bar->start();
        foreach ($packages as $p) {
            $package = Package::firstOrCreate(['name' => $p['name']], $p);
            $this->packageIds[] = $package->id;
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedEventTypes(): void
    {
        $types = ['Wedding', 'Birthday', 'Corporate', 'Party', 'Festival', 'Private', 'Other'];
        $bar = $this->progressBar(count($types));
        $bar->start();
        foreach ($types as $eventType) {
            $et = EventType::firstOrCreate(['event_type' => $eventType], ['event_type' => $eventType]);
            $this->eventTypeIds[] = $et->id;
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedBlogCategories(): void
    {
        $names = ['News', 'Tips', 'Events', 'Interviews', 'Reviews', 'Guides'];
        $bar = $this->progressBar(count($names));
        $bar->start();
        foreach ($names as $name) {
            $cat = BlogCategory::firstOrCreate(['name' => $name], ['name' => $name]);
            $this->blogCategoryIds[] = $cat->id;
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedPackageFeatures(): void
    {
        $features = ['Priority support', 'Analytics dashboard', 'Custom branding', 'API access', 'Multi-user', 'Export data', 'SSL certificate', 'Backup & restore'];
        $total = 0;
        foreach ($this->packageIds as $packageId) {
            $total += min($this->packageFeaturesPerPackage, count($features));
        }
        $bar = $this->progressBar($total ?: 1);
        $bar->start();
        foreach ($this->packageIds as $packageId) {
            $n = min($this->packageFeaturesPerPackage, count($features));
            $indices = array_rand($features, $n);
            $indices = is_array($indices) ? $indices : [$indices];
            foreach ($indices as $idx) {
                $desc = $features[$idx];
                PackageFeatures::firstOrCreate(
                    ['package_id' => $packageId, 'feature_description' => $desc],
                    ['package_id' => $packageId, 'feature_description' => $desc]
                );
                $bar->advance();
            }
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedPaymentMethods(): void
    {
        $bar = $this->progressBar(1 + count($this->companyIds));
        $bar->start();
        PaymentMethod::firstOrCreate(
            ['scope' => 'master', 'company_id' => null],
            [
                'scope' => 'master',
                'company_id' => null,
                'display_name' => 'Master Bank Transfer',
                'method_type' => 'bank_transfer',
                'account_name' => 'StageDesk Pro BV',
                'iban' => 'NL00BANK0123456789',
                'swift_code' => 'ABNANL2A',
                'instructions' => 'Transfer to the account above.',
                'is_active' => true,
            ]
        );
        $bar->advance();
        foreach ($this->companyIds as $companyId) {
            PaymentMethod::firstOrCreate(
                ['scope' => 'company', 'company_id' => $companyId],
                [
                    'scope' => 'company',
                    'company_id' => $companyId,
                    'display_name' => 'Company ' . $companyId . ' Bank',
                    'method_type' => 'bank_transfer',
                    'account_name' => 'Company ' . $companyId,
                    'iban' => 'NL00BANK' . str_pad((string) $companyId, 10, '0', STR_PAD_LEFT),
                    'is_active' => true,
                ]
            );
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedCompaniesAndUsers(Carbon $start): void
    {
        $masterAdmin = User::firstOrCreate(
            ['email' => 'info@stagedeskpro.com'],
            [
                'role_id' => $this->roleIds['master_admin'],
                'company_id' => null,
                'name' => 'StageDesk Pro Admin',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => $start,
            ]
        );

        $startStr = $start->format('Y-m-d H:i:s');
        $now = now()->format('Y-m-d H:i:s');
        $hashedPassword = Hash::make('password123');
        $artistsPerCompany = (int) ceil($this->artistsCount / $this->companiesCount);

        $companyChunkSize = 200;
        $userChunkCompanies = 50;
        $totalSteps = (int) (ceil($this->companiesCount / $companyChunkSize) + ceil($this->companiesCount / $userChunkCompanies) + 1);
        $bar = null;
        if ($this->command) {
            $bar = $this->progressBar(max(1, $totalSteps));
            $bar->setRedrawFrequency(1);
            $bar->start();
            $bar->display();
        }

        // ---- Companies: insert in chunks, then get IDs without loading Eloquent models ----
        $this->companyIds = [];
        for ($offset = 1; $offset <= $this->companiesCount; $offset += $companyChunkSize) {
            $chunkSize = min($companyChunkSize, $this->companiesCount - $offset + 1);
            $companyRows = [];
            $chunkEmails = [];
            for ($i = 0; $i < $chunkSize; $i++) {
                $num = $offset + $i;
                $email = 'company' . $num . '@example.com';
                $chunkEmails[] = $email;
                $companyRows[] = [
                    'name' => 'Company ' . $num . ' ' . Str::random(4),
                    'email' => $email,
                    'phone' => '+3161234567' . str_pad((string) ($num % 10), 1, '0'),
                    'website' => 'https://company' . $num . '.example.com',
                    'kvk_number' => (string) (10000000 + $num),
                    'contact_name' => 'Contact ' . $num,
                    'contact_phone' => '+3161234567' . ($num % 10),
                    'contact_email' => 'contact' . $num . '@company' . $num . '.com',
                    'status' => 'active',
                    'address' => 'Address ' . $num . ', City',
                    'logo' => null,
                    'email_verified_at' => $startStr,
                    'is_verified' => true,
                    'verified_at' => $startStr,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
            }
            DB::table('companies')->insert($companyRows);
            $ids = DB::table('companies')->whereIn('email', $chunkEmails)->orderBy('id')->pluck('id')->all();
            $this->companyIds = array_merge($this->companyIds, $ids);
            unset($companyRows, $chunkEmails, $ids);
            if ($bar) {
                $bar->advance();
            }
        }

        // ---- Users: process companies in chunks to avoid holding all rows in memory ----
        $this->customerUsers = [];
        $this->artistUserIds = [];
        $this->affiliateUserIds = [];

        for ($cOffset = 0; $cOffset < $this->companiesCount; $cOffset += $userChunkCompanies) {
            $cLimit = min($userChunkCompanies, $this->companiesCount - $cOffset);
            $userRows = [];
            $chunkMeta = []; // email => [company_id, role]

            for ($i = 0; $i < $cLimit; $i++) {
                $idx = $cOffset + $i;
                $companyId = $this->companyIds[$idx];
                $companyName = 'Company ' . ($idx + 1);

                $adminEmail = 'admin' . $companyId . '@company.com';
                $userRows[] = [
                    'role_id' => $this->roleIds['company_admin'],
                    'company_id' => $companyId,
                    'name' => 'Admin ' . $companyName,
                    'email' => $adminEmail,
                    'password' => $hashedPassword,
                    'status' => 'active',
                    'email_verified_at' => $startStr,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
                $chunkMeta[$adminEmail] = ['company_id' => $companyId, 'role' => 'admin'];

                $artistNum = $idx * $artistsPerCompany;
                for ($a = 0; $a < $artistsPerCompany && $artistNum + $a < $this->artistsCount; $a++) {
                    $aidx = $artistNum + $a + 1;
                    $artistEmail = 'artist' . $aidx . '@example.com';
                    $userRows[] = [
                        'role_id' => $this->roleIds['artist'],
                        'company_id' => $companyId,
                        'name' => 'Artist ' . $aidx . ' ' . Str::random(4),
                        'email' => $artistEmail,
                        'password' => $hashedPassword,
                        'status' => 'active',
                        'email_verified_at' => $startStr,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'deleted_at' => null,
                    ];
                    $chunkMeta[$artistEmail] = ['company_id' => $companyId, 'role' => 'artist'];
                }

                for ($c = 0; $c < $this->customersPerCompany; $c++) {
                    $custIdx = $idx * $this->customersPerCompany + $c + 1;
                    $customerEmail = 'customer' . $custIdx . '@example.com';
                    $userRows[] = [
                        'role_id' => $this->roleIds['customer'],
                        'company_id' => $companyId,
                        'name' => 'Customer ' . $custIdx . ' ' . Str::random(4),
                        'email' => $customerEmail,
                        'password' => $hashedPassword,
                        'status' => 'active',
                        'email_verified_at' => $startStr,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'deleted_at' => null,
                    ];
                    $chunkMeta[$customerEmail] = ['company_id' => $companyId, 'role' => 'customer'];
                }
            }

            if (empty($userRows)) {
                continue;
            }
            foreach (array_chunk($userRows, 500) as $insertChunk) {
                DB::table('users')->insert($insertChunk);
            }
            $chunkEmails = array_keys($chunkMeta);
            $emailToId = DB::table('users')->whereIn('email', $chunkEmails)->pluck('id', 'email')->all();
            foreach ($chunkMeta as $email => $meta) {
                $userId = $emailToId[$email] ?? null;
                if (!$userId) {
                    continue;
                }
                if ($meta['role'] === 'customer') {
                    $this->customerUsers[] = ['id' => $userId, 'company_id' => $meta['company_id']];
                } elseif ($meta['role'] === 'artist') {
                    $this->artistUserIds[] = ['user_id' => $userId, 'company_id' => $meta['company_id']];
                }
            }
            unset($userRows, $chunkMeta, $emailToId, $chunkEmails);
            if ($bar) {
                $bar->advance();
            }
        }

        // ---- Affiliates: one small chunk ----
        $affiliateRows = [];
        $affiliateMeta = [];
        for ($i = 1; $i <= $this->affiliatesCount; $i++) {
            $email = 'affiliate' . $i . '@example.com';
            $affiliateRows[] = [
                'role_id' => $this->roleIds['affiliate'],
                'company_id' => null,
                'name' => 'Affiliate ' . $i . ' ' . Str::random(4),
                'email' => $email,
                'password' => $hashedPassword,
                'status' => 'active',
                'email_verified_at' => $startStr,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ];
            $affiliateMeta[$email] = true;
        }
        if (!empty($affiliateRows)) {
            foreach (array_chunk($affiliateRows, 500) as $chunk) {
                DB::table('users')->insert($chunk);
            }
            $affiliateEmails = array_keys($affiliateMeta);
            $this->affiliateUserIds = DB::table('users')->whereIn('email', $affiliateEmails)->pluck('id')->all();
        }
        if ($bar) {
            $bar->advance();
            $bar->finish();
            $this->command->newLine();
        }

        // ---- User profiles: minimal set, raw insert ----
        $userIdsForProfiles = array_merge(
            [$masterAdmin->id],
            array_column($this->customerUsers, 'id'),
            array_slice(array_column($this->artistUserIds, 'user_id'), 0, 100)
        );
        $userIdsForProfiles = array_slice(array_unique($userIdsForProfiles), 0, 200);
        $countryId = DB::table('countries')->exists() ? DB::table('countries')->min('id') : null;
        $stateId = DB::table('states')->exists() ? DB::table('states')->min('id') : null;
        $cityId = DB::table('cities')->exists() ? DB::table('cities')->min('id') : null;
        $existingProfileUserIds = DB::table('user_profiles')->whereIn('user_id', $userIdsForProfiles)->pluck('user_id')->all();
        $userIdsForProfiles = array_values(array_diff($userIdsForProfiles, $existingProfileUserIds));
        foreach (array_chunk($userIdsForProfiles, 200) as $idChunk) {
            $profileRows = [];
            foreach ($idChunk as $uid) {
                $profileRows[] = [
                    'user_id' => $uid,
                    'phone' => '+31612345678',
                    'address' => 'Sample Street 1',
                    'zipcode' => '1234AB',
                    'profile_image' => null,
                    'about' => 'About me',
                    'country_id' => $countryId,
                    'state_id' => $stateId,
                    'city_id' => $cityId,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ];
            }
            if (!empty($profileRows)) {
                DB::table('user_profiles')->insert($profileRows);
            }
        }
    }

    protected function seedCompanySubscriptionsAndPayments(Carbon $start): void
    {
        $packages = Package::whereIn('id', $this->packageIds)->get();
        $bar = $this->progressBar(count($this->companyIds));
        $bar->start();
        foreach ($this->companyIds as $companyId) {
            $package = $packages->random();
            $startDate = $this->randomDateInRange($start);
            $endDate = $startDate->copy()->addMonth();
            $sub = CompanySubscription::create([
                'company_id' => $companyId,
                'package_id' => $package->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'auto_renew' => 1,
                'status' => 'active',
            ]);
            $this->subscriptionIds[] = $sub->id;

            $user = User::where('company_id', $companyId)->where('role_id', $this->roleIds['company_admin'])->first();
            $payment = Payment::create([
                'subscription_id' => $sub->id,
                'user_id' => $user?->id,
                'amount' => $package->price,
                'currency' => 'EUR',
                'transaction_id' => 'TXN-' . Str::random(12),
                'payment_method' => 'card',
                'type' => 'subscription',
                'status' => 'completed',
            ]);
            $paymentCreatedAt = $this->randomDateInRange($start)->format('Y-m-d H:i:s');
            DB::table('payments')->where('id', $payment->id)->update([
                'created_at' => $paymentCreatedAt,
                'updated_at' => $paymentCreatedAt,
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedAffiliates(): void
    {
        $bar = $this->progressBar(count($this->affiliateUserIds));
        $bar->start();
        foreach ($this->affiliateUserIds as $userId) {
            Affiliate::firstOrCreate(
                ['user_id' => $userId],
                [
                    'refferal_code' => strtoupper(Str::random(8)),
                    'refferal_link' => 'https://example.com/ref/' . Str::random(8),
                    'commision_rate' => (string) rand(5, 20),
                    'total_earning' => '0',
                ]
            );
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedArtists(Carbon $start): void
    {
        $genres = ['Pop', 'Rock', 'Jazz', 'Classical', 'Electronic', 'Hip-Hop', 'R&B', 'Country'];
        $specializations = ['DJ', 'Singer', 'Band', 'Solo', 'Duo', 'Orchestra'];
        $artistIndex = 0;
        $bar = $this->progressBar(count($this->artistUserIds));
        $bar->start();
        foreach ($this->artistUserIds as $item) {
            $artistIndex++;
            $artist = Artist::create([
                'company_id' => $item['company_id'],
                'user_id' => $item['user_id'],
                'stage_name' => 'Stage ' . $artistIndex . ' ' . Str::random(4),
                'experience_years' => (string) rand(1, 25),
                'genres' => $genres[array_rand($genres)] . ', ' . $genres[array_rand($genres)],
                'specialization' => $specializations[array_rand($specializations)],
                'rating' => (string) round(3 + (mt_rand() / mt_getrandmax()) * 2, 1),
                'image' => null,
                'bio' => 'Bio for artist ' . $artistIndex,
                'share_percentage' => (string) rand(60, 85),
            ]);
            $this->artistCompanyMap[$artist->id] = $artist->company_id;
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedSocialLinks(): void
    {
        $handles = ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube'];
        $total = 0;
        foreach ($this->companyIds as $companyId) {
            $total += min($this->socialLinksPerCompany, count($handles));
        }
        $bar = $this->progressBar($total ?: 1);
        $bar->start();
        foreach ($this->companyIds as $companyId) {
            $user = User::where('company_id', $companyId)->first();
            if (!$user) {
                continue;
            }
            $n = min($this->socialLinksPerCompany, count($handles));
            $selected = array_rand(array_flip($handles), $n);
            $selected = is_array($selected) ? $selected : [$selected];
            foreach ($selected as $handle) {
                SocialLink::create([
                    'handle' => $handle,
                    'url' => 'https://' . $handle . '.com/company' . $companyId,
                    'user_id' => $user->id,
                    'company_id' => $companyId,
                ]);
                $bar->advance();
            }
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedSharedArtists(Carbon $start): void
    {
        $artistIds = array_keys($this->artistCompanyMap);
        if (count($artistIds) < 2 || count($this->companyIds) < 2) {
            return;
        }
        $bar = $this->progressBar($this->sharedArtistsCount);
        $bar->start();
        $created = 0;
        $maxAttempts = $this->sharedArtistsCount * 3;
        $attempt = 0;
        while ($created < $this->sharedArtistsCount && $attempt < $maxAttempts) {
            $attempt++;
            $artistId = $artistIds[array_rand($artistIds)];
            $ownerCompanyId = $this->artistCompanyMap[$artistId] ?? null;
            if (!$ownerCompanyId) {
                continue;
            }
            $otherCompanyIds = array_values(array_filter($this->companyIds, fn($c) => $c !== $ownerCompanyId));
            if (empty($otherCompanyIds)) {
                continue;
            }
            $sharedWithCompanyId = $otherCompanyIds[array_rand($otherCompanyIds)];
            $exists = SharedArtist::where('artist_id', $artistId)->where('shared_with_company_id', $sharedWithCompanyId)->exists();
            if ($exists) {
                continue;
            }
            $sharedAt = $this->randomDateInRange($start);
            SharedArtist::create([
                'artist_id' => $artistId,
                'owner_company_id' => $ownerCompanyId,
                'shared_with_company_id' => $sharedWithCompanyId,
                'status' => ['pending', 'accepted', 'rejected', 'revoked'][array_rand(['pending', 'accepted', 'rejected', 'revoked'])],
                'notes' => rand(0, 1) ? 'Shared for event.' : null,
                'shared_at' => $sharedAt,
                'accepted_at' => rand(0, 1) ? $sharedAt->copy()->addDays(rand(1, 5)) : null,
                'revoked_at' => null,
            ]);
            $created++;
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedBookingRequests(Carbon $start): void
    {
        $statuses = ['pending', 'pending', 'confirmed', 'confirmed', 'completed', 'completed', 'cancelled', 'rejected'];
        $paymentStatuses = ['unpaid', 'unpaid', 'paid', 'paid', 'internal_paid'];
        $firstNames = ['Jan', 'Anna', 'Peter', 'Maria', 'John', 'Lisa', 'David', 'Emma', 'Michael', 'Sophie'];
        $lastNames = ['de Vries', 'van den Berg', 'Bakker', 'Visser', 'Jansen', 'Smit', 'de Boer', 'Mulder'];

        $artistIds = array_keys($this->artistCompanyMap);
        $daysSpread = $this->daysSpread();
        $created = 0;
        $bar = $this->progressBar($this->bookingsCount);
        $bar->start();

        while ($created < $this->bookingsCount) {
            $customer = $this->customerUsers[array_rand($this->customerUsers)];
            $companyId = $customer['company_id'];
            $eventTypeId = $this->eventTypeIds[array_rand($this->eventTypeIds)];
            $status = $statuses[array_rand($statuses)];
            $assignedArtistId = null;
            if (in_array($status, ['confirmed', 'completed']) && !empty($artistIds)) {
                $companyArtistIds = array_filter($artistIds, fn($aid) => ($this->artistCompanyMap[$aid] ?? null) === $companyId);
                $companyArtistIds = $companyArtistIds ?: $artistIds;
                $assignedArtistId = $companyArtistIds[array_rand($companyArtistIds)];
            }

            $eventDate = $this->randomDateInRange($start);
            $eventDateStr = $eventDate->format('Y-m-d');
            $bookingCreatedAt = $this->randomDateInRange($start);

            $booking = BookingRequest::create([
                'user_id' => $customer['id'],
                'company_id' => $companyId,
                'assigned_artist_id' => $assignedArtistId,
                'status' => $status,
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'event_type_id' => $eventTypeId,
                'name' => $firstNames[array_rand($firstNames)],
                'surname' => $lastNames[array_rand($lastNames)],
                'date_of_birth' => $start->copy()->subYears(rand(18, 60))->format('Y-m-d'),
                'address' => 'Street ' . rand(1, 200),
                'phone' => '+316' . rand(10000000, 99999999),
                'email' => 'booking' . $created . '+' . Str::random(4) . '@example.com',
                'event_date' => $eventDateStr,
                'opening_songs' => 'Song 1, Song 2',
                'special_moments' => 'First dance',
                'dos' => null,
                'donts' => null,
                'playlist_spotify' => null,
                'additional_notes' => 'Notes for booking ' . $created,
                'company_notes' => null,
                'wedding_date' => null,
                'wedding_time' => null,
                'partner_name' => null,
                'confirmed_at' => in_array($status, ['confirmed', 'completed']) ? $eventDate->copy()->subDays(rand(1, 30)) : null,
                'completed_at' => $status === 'completed' ? $eventDate->copy()->addDay() : null,
                'cancelled_at' => $status === 'cancelled' ? $eventDate->copy() : null,
            ]);
            $createdAtStr = $bookingCreatedAt->format('Y-m-d H:i:s');
            DB::table('booking_requests')->where('id', $booking->id)->update([
                'created_at' => $createdAtStr,
                'updated_at' => $createdAtStr,
            ]);

            $this->bookingIds[] = ['id' => $booking->id, 'status' => $status, 'user_id' => $customer['id'], 'company_id' => $companyId, 'assigned_artist_id' => $assignedArtistId];
            $created++;
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedBookedServices(): void
    {
        $services = ['DJ Set', 'Live Band', 'Singer', 'MC', 'Sound System', 'Lighting', 'Full Package'];
        $bookings = array_column($this->bookingIds, 'id');
        $n = min($this->bookingsCount * $this->bookedServicesPerBooking, count($bookings) * 3);
        $bar = $this->progressBar($n ?: 1);
        $bar->start();
        for ($i = 0; $i < $n; $i++) {
            $bookingId = $bookings[array_rand($bookings)];
            $service = $services[array_rand($services)];
            BookedService::create([
                'booking_requests_id' => $bookingId,
                'service_requested' => $service,
                'total_price' => (string) rand(200, 3000),
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedArtistRequests(): void
    {
        $artistIds = array_keys($this->artistCompanyMap);
        $bookingsWithCompany = array_filter($this->bookingIds, fn($b) => !empty($b['company_id']));
        $n = min($this->bookingsCount * $this->artistRequestsPerBooking, count($bookingsWithCompany) * 2);
        $bookingsWithCompany = array_values($bookingsWithCompany);
        $bar = $this->progressBar($n ?: 1);
        $bar->start();
        for ($i = 0; $i < $n && !empty($bookingsWithCompany); $i++) {
            $b = $bookingsWithCompany[array_rand($bookingsWithCompany)];
            $companyArtists = array_filter($artistIds, fn($aid) => ($this->artistCompanyMap[$aid] ?? null) === $b['company_id']);
            if (empty($companyArtists)) {
                continue;
            }
            $artistId = $companyArtists[array_rand($companyArtists)];
            ArtistRequest::firstOrCreate(
                [
                    'artist_id' => $artistId,
                    'booking_requests_id' => $b['id'],
                ],
                [
                    'company_id' => $b['company_id'],
                    'status' => ['pending', 'accepted', 'rejected'][array_rand(['pending', 'accepted', 'rejected'])],
                    'assigned_at' => rand(0, 1) ? now() : null,
                ]
            );
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedBookingPayments(Carbon $start): void
    {
        $completedOrPaid = array_filter($this->bookingIds, fn($b) => in_array($b['status'], ['completed', 'confirmed']));
        $maxPayments = min(2000, count($completedOrPaid));
        $slice = array_slice($completedOrPaid, 0, $maxPayments);
        $bar = $this->progressBar(count($slice) ?: 1);
        $bar->start();
        foreach ($slice as $b) {
            $payment = Payment::create([
                'booking_requests_id' => $b['id'],
                'user_id' => $b['user_id'],
                'amount' => (string) rand(500, 5000),
                'currency' => 'EUR',
                'transaction_id' => 'BK-' . Str::random(10),
                'payment_method' => 'card',
                'type' => 'booking',
                'status' => 'completed',
            ]);
            $paymentCreatedAt = $this->randomDateInRange($start)->format('Y-m-d H:i:s');
            DB::table('payments')->where('id', $payment->id)->update([
                'created_at' => $paymentCreatedAt,
                'updated_at' => $paymentCreatedAt,
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedReviews(Carbon $start): void
    {
        $completedBookings = array_filter($this->bookingIds, fn($b) => $b['status'] === 'completed' && !empty($b['assigned_artist_id']));
        $completedBookings = array_slice(array_values($completedBookings), 0, $this->reviewsCount);
        $bar = $this->progressBar(count($completedBookings) ?: 1);
        $bar->start();
        foreach ($completedBookings as $b) {
            Review::firstOrCreate(
                ['user_id' => $b['user_id'], 'booking_id' => $b['id']],
                [
                    'artist_id' => $b['assigned_artist_id'],
                    'company_id' => $b['company_id'],
                    'rating' => rand(1, 5),
                    'review' => 'Great experience!',
                    'status' => 'approved',
                    'is_featured' => (bool) rand(0, 4),
                ]
            );
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedAffiliateCommissions(Carbon $start): void
    {
        $affiliates = Affiliate::pluck('id')->toArray();
        if (empty($affiliates) || empty($this->subscriptionIds)) {
            return;
        }
        $subs = CompanySubscription::whereIn('id', $this->subscriptionIds)->get();
        $n = min($this->affiliateCommissionsCount, count($subs) * 2);
        $bar = $this->progressBar($n ?: 1);
        $bar->start();
        for ($i = 0; $i < $n; $i++) {
            $sub = $subs->random();
            $affiliateId = $affiliates[array_rand($affiliates)];
            $exists = DB::table('affiliate_comissions')
                ->where('affiliate_id', $affiliateId)
                ->where('subscription_id', $sub->id)
                ->exists();
            if (!$exists) {
                $createdAt = $this->randomDateInRange($start);
                DB::table('affiliate_comissions')->insert([
                    'affiliate_id' => $affiliateId,
                    'company_id' => $sub->company_id,
                    'subscription_id' => $sub->id,
                    'amount' => (string) rand(10, 200),
                    'status' => ['pending', 'paid', 'canceled'][array_rand(['pending', 'paid', 'canceled'])],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedArtistEarnings(Carbon $start): void
    {
        $bookings = collect($this->bookingIds)->keyBy('id');
        $query = Payment::where('type', 'booking')
            ->where('status', 'completed')
            ->whereNotNull('booking_requests_id')
            ->limit($this->artistEarningsCount);
        $bar = $this->progressBar($this->artistEarningsCount ?: 1);
        $bar->start();
        $created = 0;
        $query->cursor()->each(function ($payment) use ($bookings, $bar, &$created) {
            $b = $bookings->get($payment->booking_requests_id);
            if (!$b || !$b['assigned_artist_id']) {
                $bar->advance();
                return;
            }
            $artist = Artist::find($b['assigned_artist_id']);
            $amount = (float) $payment->amount;
            $share = $artist ? (float) ($artist->share_percentage ?? 70) / 100 : 0.7;
            $earn = round($amount * $share, 2);
            ArtistEarning::firstOrCreate(
                [
                    'artist_id' => $b['assigned_artist_id'],
                    'booking_request_id' => $payment->booking_requests_id,
                    'payment_id' => $payment->id,
                ],
                [
                    'amount' => $earn,
                    'amount_paid_out' => 0,
                    'share_percentage' => $share * 100,
                    'status' => 'available',
                ]
            );
            $created++;
            $bar->advance();
        });
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedArtistWithdrawalRequests(Carbon $start): void
    {
        $artistIds = array_keys($this->artistCompanyMap);
        $n = min($this->artistWithdrawalsCount, count($artistIds));
        $picked = array_rand(array_flip($artistIds), min($n, count($artistIds)));
        $picked = is_array($picked) ? $picked : [$picked];
        $bar = $this->progressBar(count($picked) ?: 1);
        $bar->start();
        foreach ($picked as $artistId) {
            $companyId = $this->artistCompanyMap[$artistId] ?? null;
            if (!$companyId) {
                continue;
            }
            ArtistWithdrawalRequest::create([
                'artist_id' => $artistId,
                'company_id' => $companyId,
                'amount' => rand(100, 2000),
                'status' => ['pending', 'approved', 'rejected', 'paid'][array_rand(['pending', 'approved', 'rejected', 'paid'])],
                'artist_notes' => null,
                'admin_notes' => null,
                'processed_by' => null,
                'processed_at' => null,
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedBlogs(Carbon $start): void
    {
        $adminUser = User::where('role_id', $this->roleIds['master_admin'])->first();
        $userId = $adminUser?->id ?? User::first()?->id;
        if (!$userId || empty($this->blogCategoryIds)) {
            return;
        }
        $bar = $this->progressBar($this->blogsCount);
        $bar->start();
        for ($i = 1; $i <= $this->blogsCount; $i++) {
            $title = 'Blog Post ' . $i . ' ' . Str::random(4);
            Blog::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . $i,
                'content' => '<p>Content for blog ' . $i . '. ' . Str::random(100) . '</p>',
                'image' => null,
                'blog_category_id' => $this->blogCategoryIds[array_rand($this->blogCategoryIds)],
                'user_id' => $userId,
                'status' => ['published', 'draft', 'archived', 'unapproved'][array_rand(['published', 'draft', 'archived', 'unapproved'])],
                'feature_image' => null,
                'published_at' => rand(0, 1) ? $this->randomDateInRange($start) : null,
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedTestimonials(Carbon $start): void
    {
        $designations = ['Customer', 'Event Manager', 'Bride', 'Groom', 'Corporate Client'];
        $bar = $this->progressBar($this->testimonialsCount);
        $bar->start();
        for ($i = 1; $i <= $this->testimonialsCount; $i++) {
            Testimonials::create([
                'testimonial' => 'Great service! We had an amazing experience. ' . Str::random(50),
                'name' => 'Client ' . $i . ' ' . Str::random(4),
                'avatar' => null,
                'designation' => $designations[array_rand($designations)],
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedNotifications(Carbon $start): void
    {
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            return;
        }
        $titles = ['New booking', 'Payment received', 'Artist assigned', 'Reminder', 'Update', 'Review submitted', 'Subscription renewed'];
        $messages = ['You have a new notification.', 'Please check your dashboard.', 'An update is available.'];
        $categories = ['booking', 'payment', 'system', 'general'];
        $daysSpread = $this->daysSpread();
        $bar = $this->progressBar($this->notificationsCount);
        $bar->start();
        for ($i = 0; $i < $this->notificationsCount; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $companyId = User::find($userId)?->company_id;
            Notification::create([
                'user_id' => $userId,
                'company_id' => $companyId,
                'title' => $titles[array_rand($titles)],
                'message' => $messages[array_rand($messages)],
                'type' => 'general',
                'category' => $categories[array_rand($categories)],
                'priority' => rand(1, 3),
                'data' => null,
                'link' => null,
                'is_read' => (bool) rand(0, 1),
                'created_at' => $start->copy()->addDays(rand(0, $daysSpread)),
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedComments(Carbon $start): void
    {
        $blogIds = Blog::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();
        if (empty($blogIds) || empty($userIds)) {
            return;
        }
        $daysSpread = $this->daysSpread();
        $bar = $this->progressBar($this->commentsCount);
        $bar->start();
        for ($i = 0; $i < $this->commentsCount; $i++) {
            DB::table('comments')->insert([
                'user_id' => $userIds[array_rand($userIds)],
                'blog_id' => $blogIds[array_rand($blogIds)],
                'parent_id' => null,
                'content' => 'Comment content ' . $i . '. ' . Str::random(30),
                'status' => ['published', 'unapproved'][array_rand(['published', 'unapproved'])],
                'likes_count' => rand(0, 20),
                'created_at' => $start->copy()->addDays(rand(0, $daysSpread)),
                'updated_at' => now(),
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedSupportTickets(Carbon $start): void
    {
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            return;
        }
        $types = ['issue', 'complaint', 'dispute', 'refund', 'suggestion', 'other'];
        $statuses = ['open', 'in_progress', 'closed'];
        $daysSpread = $this->daysSpread();
        $bar = $this->progressBar($this->supportTicketsCount);
        $bar->start();
        for ($i = 0; $i < $this->supportTicketsCount; $i++) {
            SupportTicket::create([
                'user_id' => $userIds[array_rand($userIds)],
                'title' => 'Support ticket #' . ($i + 1) . ' – ' . Str::random(8),
                'type' => $types[array_rand($types)],
                'description' => 'Description for support ticket ' . ($i + 1) . '. ' . Str::random(100),
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $start->copy()->addDays(rand(0, $daysSpread)),
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }

    protected function seedActivityLogs(Carbon $start): void
    {
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            return;
        }
        $actions = ['login', 'logout', 'create', 'update', 'delete', 'view', 'booking.created', 'payment.completed'];
        $severities = ['info', 'warning', 'error'];
        $categories = ['auth', 'booking', 'payment', 'user', 'general'];
        $daysSpread = $this->daysSpread();
        $bar = $this->progressBar($this->activityLogsCount);
        $bar->start();
        for ($i = 0; $i < $this->activityLogsCount; $i++) {
            ActivityLog::create([
                'user_id' => $userIds[array_rand($userIds)],
                'action' => $actions[array_rand($actions)],
                'severity' => $severities[array_rand($severities)],
                'status' => rand(0, 1) ? 'success' : null,
                'category' => $categories[array_rand($categories)],
                'event_key' => 'seeded.event.' . Str::random(6),
                'description' => 'Activity log entry ' . ($i + 1),
                'properties' => ['source' => 'seeder'],
                'ip_address' => '127.0.0.' . rand(1, 255),
                'user_agent' => 'Seeder/1.0',
                'created_at' => $start->copy()->addDays(rand(0, $daysSpread)),
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->newLine();
    }
}
