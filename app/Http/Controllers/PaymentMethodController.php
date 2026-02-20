<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;
        $this->ensureAdminRole($roleKey);

        $methods = $this->scopedQuery($roleKey, $user->company_id)
            ->latest()
            ->paginate(15);

        $title = 'Payment Methods';

        return view('dashboard.pages.payment-methods.index', compact('title', 'methods'));
    }

    public function create()
    {
        $this->ensureAdminRole(Auth::user()->role->role_key);

        $title = 'Add Payment Method';
        $mode = 'create';

        return view('dashboard.pages.payment-methods.manage', compact('title', 'mode'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;
        $this->ensureAdminRole($roleKey);

        $validated = $this->validateMethod($request);
        $validated['scope'] = $roleKey === 'master_admin' ? 'master' : 'company';
        $validated['company_id'] = $roleKey === 'company_admin' ? $user->company_id : null;
        $validated['is_active'] = $request->boolean('is_active', true);

        $method = PaymentMethod::create($validated);
        \App\Models\ActivityLog::log('created', $method, 'Payment method created', [
            'payment_method_id' => $method->id,
            'scope' => $method->scope,
            'company_id' => $method->company_id,
        ]);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method added successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;
        $this->ensureAdminRole($roleKey);
        $this->authorizeOwnership($paymentMethod, $roleKey, $user->company_id);

        $title = 'Edit Payment Method';
        $mode = 'edit';

        return view('dashboard.pages.payment-methods.manage', compact('title', 'mode', 'paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;
        $this->ensureAdminRole($roleKey);
        $this->authorizeOwnership($paymentMethod, $roleKey, $user->company_id);

        $validated = $this->validateMethod($request);
        $validated['is_active'] = $request->boolean('is_active', false);
        $paymentMethod->update($validated);

        \App\Models\ActivityLog::log('updated', $paymentMethod, 'Payment method updated', [
            'payment_method_id' => $paymentMethod->id,
            'scope' => $paymentMethod->scope,
            'company_id' => $paymentMethod->company_id,
        ]);

        return redirect()->route('payment-methods.index')->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();
        $roleKey = $user->role->role_key;
        $this->ensureAdminRole($roleKey);
        $this->authorizeOwnership($paymentMethod, $roleKey, $user->company_id);

        \App\Models\ActivityLog::log('deleted', $paymentMethod, 'Payment method deleted', [
            'payment_method_id' => $paymentMethod->id,
            'scope' => $paymentMethod->scope,
            'company_id' => $paymentMethod->company_id,
        ]);

        $paymentMethod->delete();

        return redirect()->route('payment-methods.index')->with('success', 'Payment method deleted successfully.');
    }

    private function validateMethod(Request $request): array
    {
        return $request->validate([
            'display_name' => 'required|string|max:255',
            'method_type' => 'required|in:bank_transfer,paypal,stripe,wise,other',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'wallet_email' => 'nullable|email|max:255',
            'instructions' => 'nullable|string|max:2000',
        ]);
    }

    private function ensureAdminRole(string $roleKey): void
    {
        if (!in_array($roleKey, ['master_admin', 'company_admin'])) {
            abort(403, 'Unauthorized');
        }
    }

    private function authorizeOwnership(PaymentMethod $paymentMethod, string $roleKey, ?int $companyId): void
    {
        if ($roleKey === 'master_admin' && $paymentMethod->scope !== 'master') {
            abort(403, 'Unauthorized');
        }

        if ($roleKey === 'company_admin' && !($paymentMethod->scope === 'company' && (int) $paymentMethod->company_id === (int) $companyId)) {
            abort(403, 'Unauthorized');
        }
    }

    private function scopedQuery(string $roleKey, ?int $companyId)
    {
        return match ($roleKey) {
            'master_admin' => PaymentMethod::query()->masterOwned(),
            'company_admin' => PaymentMethod::query()->companyOwned((int) $companyId),
            default => PaymentMethod::query()->whereRaw('1=0'),
        };
    }
}
