<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanySubscription;
use App\Models\Company;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CompanySubscriptionController extends Controller
{
    //
    public function index()
    {
        $title = 'Manange Company Subscriptions';
        $companySubscription = CompanySubscription::with('company', 'package')->orderBy('id', 'desc')->get();

        return view('dashboard.pages.subscriptions.index', compact('title', 'companySubscription'));
    }

    public function create($id = '')
    {
        $title = 'Create Company Subscription';

        $companies = Company::all();
        $packages = Package::all();

        return view('dashboard.pages.subscriptions.manage', compact('title', 'companies', 'packages', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'package_id' => 'required|exists:packages,id',
        ]);

        DB::transaction(function () use ($request) {
            // Step 1: Cancel any existing active subscriptions for this company
            CompanySubscription::where('company_id', $request->company_id)
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
                'company_id' => $request->company_id,
                'package_id' => $request->package_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'auto_renew' => false,
                'status' => 'active',
            ]);
        });

        return redirect()->route('subscriptions')->with('success', 'Subscription created successfully.');
    }
}