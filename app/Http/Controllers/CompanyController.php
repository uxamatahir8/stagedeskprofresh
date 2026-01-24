<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Countries;
use App\Models\SocialLink;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Companies List';

        // Get stats for quick overview
        $stats = [
            'total' => Company::count(),
            'active' => Company::where('status', 'active')->count(),
            'inactive' => Company::where('status', 'inactive')->count(),
            'with_subscription' => Company::whereHas('activeSubscription')->count(),
        ];

        // Query builder
        $query = Company::query()->withCount(['artists', 'bookings']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('kvk_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by subscription
        if ($request->filled('subscription')) {
            if ($request->subscription === 'active') {
                $query->whereHas('activeSubscription');
            } elseif ($request->subscription === 'inactive') {
                $query->whereDoesntHave('activeSubscription');
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $companies = $query->paginate(15)->withQueryString();

        return view('dashboard.pages.companies.index_enhanced', compact('title', 'companies', 'stats'));
    }

    public function create()
    {
        $title     = 'Create Company';
        $mode      = 'create';
        $countries = Countries::all(); // For admin country select
        return view('dashboard.pages.companies.manage', compact('title', 'mode', 'countries'));
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $uploadedFiles = [];

        try {
            $validated = $this->getArr($request);

            // Company logo
            if ($request->hasFile('logo')) {
                $path              = $request->file('logo')->store('company_logos', 'public');
                $validated['logo'] = $path;
                $uploadedFiles[]   = $path;
            }

            $company = Company::create($validated);

            // Social links
            foreach ($request->input('social_links', []) as $handle => $url) {
                if (! empty($url)) {
                    SocialLink::create([
                        'handle'     => $handle,
                        'url'        => $url,
                        'company_id' => $company->id,
                        'user_id'    => null,
                    ]);
                }
            }

            // Handle company admin
            if ($request->boolean('is_admin')) {
                $adminUser = User::create([
                    'name'       => $request->contact_name,
                    'email'      => $request->contact_email,
                    'role_id'    => 2,
                    'company_id' => $company->id,
                    'password'   => Hash::make($request->password),
                ]);

                $profileData = [
                    'user_id'    => $adminUser->id,
                    'phone'      => $request->contact_phone,
                    'address'    => $request->admin_address,
                    'country_id' => $request->admin_country_id,
                    'state_id'   => $request->admin_state_id,
                    'city_id'    => $request->admin_city_id,
                ];

                if ($request->hasFile('admin_profile_image')) {
                    $path = $request->file('admin_profile_image')
                        ->store('profile_images', 'public');

                    $profileData['profile_image'] = $path;
                    $uploadedFiles[]              = $path;
                }

                UserProfile::create($profileData);
            }

            DB::commit();

            return redirect()
                ->route('companies')
                ->with('success', 'Company created successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $e;
        }
    }
    public function show(Company $company)
    {
        $title = $company->name;

        // Fetch company stats
        $stats = [
            'total_artists' => $company->artists()->count(),
            'total_bookings' => \App\Models\BookingRequest::where('company_id', $company->id)->count(),
            'avg_rating' => round($company->artists()->avg('rating') ?? 0, 1),
            'total_revenue' => \App\Models\Payment::whereHas('bookingRequest', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'completed')->sum('amount'),
        ];

        // Fetch artists with booking counts
        $artists = $company->artists()
            ->with('user')
            ->get();

        // Fetch recent bookings
        $bookings = \App\Models\BookingRequest::where('company_id', $company->id)
            ->with(['eventType', 'user'])
            ->latest()
            ->paginate(10);

        // Fetch active subscription
        $subscription = \App\Models\CompanySubscription::where('company_id', $company->id)
            ->where('status', 'active')
            ->with('package.packageFeatures')
            ->orderBy('end_date', 'desc')
            ->first();

        // Fetch recent activity logs
        $activityLogs = \App\Models\ActivityLog::whereHas('user', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.pages.companies.show_enhanced', compact(
            'title',
            'company',
            'stats',
            'artists',
            'bookings',
            'subscription',
            'activityLogs'
        ));
    }

    public function edit(Company $company)
    {
        $title     = 'Edit Company';
        $mode      = 'edit';
        $countries = Countries::all();
        return view('dashboard.pages.companies.manage', compact('title', 'mode', 'company', 'countries'));
    }

    /**
     * @throws \Throwable
     */
    public function update(Request $request, Company $company)
    {
        DB::beginTransaction();

        // Track uploaded files in case rollback is needed
        $uploadedFiles = [];

        try {
            $validated = $this->getArr($request);

            // Company logo
            if ($request->hasFile('logo')) {
                if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                    Storage::disk('public')->delete($company->logo);
                }

                $path              = $request->file('logo')->store('company_logos', 'public');
                $validated['logo'] = $path;
                $uploadedFiles[]   = $path;
            }

            $company->update($validated);

            // Social links
            $socialLinks = $request->input('social_links', []);
            foreach ($socialLinks as $handle => $url) {
                $link = $company->socialLinks()->where('handle', $handle)->first();

                if (! empty($url)) {
                    if ($link) {
                        $link->update(['url' => $url]);
                    } else {
                        SocialLink::create([
                            'handle'     => $handle,
                            'url'        => $url,
                            'company_id' => $company->id,
                            'user_id'    => null,
                        ]);
                    }
                } elseif ($link) {
                    $link->delete();
                }
            }

            // Handle company admin
            if ($request->boolean('is_admin')) {
                $adminUser = User::where('company_id', $company->id)
                    ->where('role_id', 2)
                    ->first();

                if (! $adminUser) {
                    $adminUser = User::create([
                        'name'       => $request->contact_name,
                        'email'      => $request->contact_email,
                        'role_id'    => 2,
                        'company_id' => $company->id,
                        'password'   => Hash::make($request->password),
                    ]);
                } else {
                    $adminUser->update([
                        'name'     => $request->contact_name,
                        'email'    => $request->contact_email,
                        'password' => $request->filled('password')
                            ? Hash::make($request->password)
                            : $adminUser->password,
                    ]);
                }

                $profileData = [
                    'phone'      => $request->contact_phone,
                    'address'    => $request->admin_address,
                    'country_id' => $request->admin_country_id,
                    'state_id'   => $request->admin_state_id,
                    'city_id'    => $request->admin_city_id,
                ];

                if ($request->hasFile('admin_profile_image')) {
                    $path = $request->file('admin_profile_image')
                        ->store('profile_images', 'public');

                    $profileData['profile_image'] = $path;
                    $uploadedFiles[]              = $path;
                }

                if ($adminUser->profile) {
                    $adminUser->profile->update($profileData);
                } else {
                    $profileData['user_id'] = $adminUser->id;
                    UserProfile::create($profileData);
                }
            }

            DB::commit();

            return redirect()
                ->route('companies')
                ->with('success', 'Company updated successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();

            // Cleanup uploaded files
            foreach ($uploadedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            throw $e; // or handle gracefully
        }
    }
    public function destroy(Company $company)
    {
        // Delete social links
        $company->socialLinks()->delete();

        // Delete logo
        if ($company->logo && Storage::disk('public')->exists($company->logo)) {
            Storage::disk('public')->delete($company->logo);
        }

        // Delete admin and profile if exists
        $adminUser = User::where('company_id', $company->id)->where('role_id', 2)->first();
        if ($adminUser) {
            if ($adminUser->profile && $adminUser->profile->profile_image && Storage::disk('public')->exists($adminUser->profile->profile_image)) {
                Storage::disk('public')->delete($adminUser->profile->profile_image);
            }
            $adminUser->profile()->delete();
            $adminUser->delete();
        }

        // Delete company
        $company->delete();

        return redirect()->route('companies')->with('success', 'Company and related data deleted successfully.');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getArr(Request $request): array
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'website'        => 'nullable|url',
            'kvk_number'     => 'nullable|string|max:50',
            'contact_name'   => 'nullable|string|max:255',
            'contact_phone'  => 'nullable|string|max:20',
            'contact_email'  => 'nullable|email',
            'status'         => 'nullable|string|in:active,inactive',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'social_links'   => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'address'        => 'nullable|string|max:255',

        ]);

        if ($request->has('is_admin') && $request->is_admin == 1) {
            $validated = array_merge($validated, $request->validate([
                'is_admin'            => 'nullable|boolean',
                'password'            => 'required_if:is_admin,1|confirmed|min:6',
                'admin_address'       => 'nullable|string|max:255',
                'admin_country_id'    => 'nullable|exists:countries,id',
                'admin_state_id'      => 'nullable|exists:states,id',
                'admin_city_id'       => 'nullable|exists:cities,id',
                'admin_profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]));
        }

        $validated['status'] = $request->has('status') ? $validated['status'] : 'inactive';
        return $validated;
    }
}
