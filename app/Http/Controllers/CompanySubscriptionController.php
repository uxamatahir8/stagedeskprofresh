<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySubscription;
use App\Models\Company;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanySubscriptionController extends Controller
{
    /**
     * Scope query for company_admin: only their company's subscriptions.
     */
    private function scopeSubscriptions()
    {
        $q = CompanySubscription::with('company', 'package')->orderBy('id', 'desc');
        if (Auth::check() && Auth::user()->role->role_key === 'company_admin') {
            $q->where('company_id', Auth::user()->company_id);
        }
        return $q;
    }

    public function index()
    {
        $title = 'Manage Company Subscriptions';
        $companySubscription = $this->scopeSubscriptions()->get();

        return view('dashboard.pages.subscriptions.index', compact('title', 'companySubscription'));
    }

    public function create($id = '')
    {
        $title = 'Create Company Subscription';

        $user = Auth::user();
        $companies = $user->role->role_key === 'master_admin'
            ? Company::all()
            : Company::where('id', $user->company_id)->get();
        $packages = Package::all();

        return view('dashboard.pages.subscriptions.manage', compact('title', 'companies', 'packages', 'id'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->role->role_key === 'company_admin'
            ? $user->company_id
            : $request->company_id;
        $request->validate([
            'company_id' => $user->role->role_key === 'master_admin'
                ? 'required|exists:companies,id'
                : 'nullable',
            'package_id' => 'required|exists:packages,id',
        ]);
        if ($user->role->role_key === 'company_admin') {
            $request->merge(['company_id' => $companyId]);
        }

        $companyId = (int) $request->company_id;
        DB::transaction(function () use ($request, $companyId) {
            // Step 1: Cancel any existing active subscriptions for this company
            CompanySubscription::where('company_id', $companyId)
                ->where('status', 'active')
                ->update(['status' => 'canceled']);

            // Step 2: Get the selected package details
            $package = Package::findOrFail($request->package_id);

            $startDate = Carbon::now();

            // Step 3: Calculate end date based on duration type
            switch (strtolower($package->duration_type)) {
                case 'weekly':
                    $endDate = $startDate->copy()->addWeek();
                    break;
                case 'monthly':
                    $endDate = $startDate->copy()->addMonth();
                    break;
                case 'yearly':
                    $endDate = $startDate->copy()->addYear();
                    break;
                default:
                    throw new \Exception('Invalid package duration type');
            }

            // Step 4: Create a new active subscription
            CompanySubscription::create([
                'company_id' => $companyId,
                'package_id' => $request->package_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'auto_renew' => false,
                'status' => 'active',
            ]);
        });

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function show(CompanySubscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        $title = 'Subscription Details';
        $subscription->load('company', 'package');

        return view('dashboard.pages.subscriptions.show', compact('title', 'subscription'));
    }

    public function edit(CompanySubscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        $title = 'Edit Subscription';
        $user = Auth::user();
        $companies = $user->role->role_key === 'master_admin'
            ? Company::all()
            : Company::where('id', $user->company_id)->get();
        $packages = Package::all();

        return view('dashboard.pages.subscriptions.manage', compact('title', 'subscription', 'companies', 'packages'));
    }

    public function update(Request $request, CompanySubscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        $user = Auth::user();
        $request->validate([
            'company_id' => $user->role->role_key === 'master_admin' ? 'required|exists:companies,id' : 'nullable',
            'package_id' => 'required|exists:packages,id',
            'auto_renew'  => 'boolean',
        ]);
        if ($user->role->role_key === 'company_admin') {
            $request->merge(['company_id' => $user->company_id]);
        }

        DB::transaction(function () use ($request, $subscription) {
            // Get the selected package details
            $package = Package::findOrFail($request->package_id);

            $startDate = $subscription->start_date;

            // Calculate end date based on duration type
            switch (strtolower($package->duration_type)) {
                case 'weekly':
                    $endDate = $startDate->copy()->addWeek();
                    break;
                case 'monthly':
                    $endDate = $startDate->copy()->addMonth();
                    break;
                case 'yearly':
                    $endDate = $startDate->copy()->addYear();
                    break;
                default:
                    throw new \Exception('Invalid package duration type');
            }

            $subscription->update([
                'company_id' => $request->company_id,
                'package_id' => $request->package_id,
                'end_date' => $endDate,
                'auto_renew' => $request->boolean('auto_renew'),
            ]);
        });

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(CompanySubscription $subscription)
    {
        $this->authorizeSubscription($subscription);
        $subscription->update(['status' => 'canceled']);

        return redirect()->route('subscriptions.index')->with('success', 'Subscription canceled successfully.');
    }

    private function authorizeSubscription(CompanySubscription $subscription): void
    {
        if (Auth::check() && Auth::user()->role->role_key === 'company_admin'
            && (int) $subscription->company_id !== (int) Auth::user()->company_id) {
            abort(403, 'You can only manage your company\'s subscriptions.');
        }
    }
}
