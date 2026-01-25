<?php
namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Mail\WelcomeMail;
use App\Mail\VerifyEmail;
use App\Mail\LoginCode as LoginCodeMail;
use App\Models\Company;
use App\Models\Countries;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\LoginCode;
use App\Services\AuthSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $authSecurity;

    public function __construct(AuthSecurityService $authSecurity)
    {
        $this->authSecurity = $authSecurity;
    }

    //
    public function login()
    {
        $title = 'Login';

        return view('auth.pages.login', compact('title'));
    }

    public function register()
    {
        $title     = 'Register';
        $countries = Countries::all();

        return view('auth.pages.register', compact('title', 'countries'));
    }

    public function forgotPassword()
    {
        $title = 'Forgot Password';

        return view('auth.pages.forgot-password', compact('title'));
    }

    public function userForgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // ✅ Get user
        $user = User::where('email', $request->email)->first();

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $request->email,
            ],
            [
                'token'      => $token,
                'created_at' => now(),
            ]
        );

        $resetLink = URL::to('/reset-password?token=' . $token . '&email=' . $user->email);

        Mail::send('emails.user-forgot-password', ['user' => $user, 'resetLink' => $resetLink], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Reset Your Password');
        });

        return redirect()->route('user_login')->with('success', 'Password reset link sent to your email.');
    }

    public function resetPassword(Request $request)
    {
        $title = 'Reset Password';

        if (! $request->has('token') || ! $request->has('email')) {
            return redirect()->route('login')->with('error', 'Invalid password reset link.');
        }

        return view('auth.pages.reset-password', compact('title'));
    }

    public function userResetPassword(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'email'    => ['required', 'email', 'exists:users,email'],
            'token'    => ['required'],
            'password' => [
                'required',
                'string',
                'min:10',            // secure minimum
                'regex:/[a-z]/',     // lowercase
                'regex:/[A-Z]/',     // uppercase
                'regex:/[0-9]/',     // number
                'regex:/[@$!%*?&]/', // special character
                'confirmed',         // must match password_confirmation
            ],
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
        ]);

        // ✅ Find record in password_reset_tokens
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (! $tokenData) {
            return redirect()->back()->with('error', 'Invalid or expired reset token.');
        }

        // ✅ Token expiration check (optional: 1 hour)
        if (now()->diffInMinutes($tokenData->created_at) > 60) {
            return redirect()->back()->with('error', 'This reset link has expired. Please request a new one.');
        }

        // ✅ Update user password
        $user = User::where('email', $request->email)->first();

        // Check if password was recently used
        if ($this->authSecurity->isPasswordReused($user, $request->password)) {
            return redirect()->back()->with('error', 'Please choose a password you have not used recently.');
        }

        $newPasswordHash = Hash::make($request->password);
        $user->password = $newPasswordHash;
        $user->password_changed_at = now();
        $user->setRememberToken(Str::random(60)); // Optional: invalidate existing sessions
        $user->save();

        // Save to password history
        $this->authSecurity->savePasswordHistory($user, $newPasswordHash);

        // ✅ Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // ✅ Send confirmation email
        Mail::send('emails.password-reset-success', ['user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Your Password Has Been Reset');
        });

        // ✅ Redirect with success
        return redirect()->route('login')->with('success', 'Your password has been successfully reset. You can now log in.');
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check rate limiting
        $rateLimitKey = 'login-attempt:' . $request->ip();
        $rateLimit = $this->authSecurity->checkRateLimit($rateLimitKey, 5, 1);

        if ($rateLimit['limited']) {
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in ' . $rateLimit['retry_after'] . ' seconds.',
            ])->onlyInput('email');
        }

        // Find user
        $user = User::where('email', $credentials['email'])->first();

        // Check if account is locked
        if ($user && $this->authSecurity->isAccountLocked($user)) {
            $minutes = $user->locked_until->diffInMinutes(now());
            $this->authSecurity->recordLoginAttempt($credentials['email'], false, $user->id, 'Account locked');

            return back()->withErrors([
                'email' => "Your account is locked due to multiple failed login attempts. Please try again in {$minutes} minutes.",
            ])->onlyInput('email');
        }

        // Check if email is verified
        if ($user && !$user->email_verified_at) {
            $this->authSecurity->recordLoginAttempt($credentials['email'], false, $user->id, 'Email not verified');

            // Generate new verification token if doesn't exist or regenerate
            if (!$user->verification_token) {
                $user->verification_token = \Illuminate\Support\Str::random(64);
                $user->save();
            }

            // Send new verification email
            $verificationUrl = url('/verify-email/' . $user->verification_token);
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $verificationUrl));

            return back()->withErrors([
                'email' => 'Your email is not verified. A new verification link has been sent to your email address.',
            ])->with('verification_sent', true)->onlyInput('email');
        }

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            // Clear rate limiter on successful login
            $this->authSecurity->clearRateLimit($rateLimitKey);

            // Reset failed attempts
            $this->authSecurity->resetFailedAttempts($user);

            // Detect suspicious activity
            $this->authSecurity->detectSuspiciousActivity($user);

            $request->session()->regenerate();

            // Check if user must change password
            if ($user->must_change_password == 1) {
                return redirect()->route('change-password')->with('info', 'For security reasons, you must change your temporary password before continuing.');
            }

            $previous = url()->previous();

            // Check if user came from a blog page
            if (str_contains($previous, '/blogs')) {
                return redirect($previous)->with('success', 'Login successful!');
            }

            // Default redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        // Record failed attempt
        $userId = $user ? $user->id : null;
        $this->authSecurity->recordLoginAttempt($credentials['email'], false, $userId, 'Invalid credentials');

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Send login code to email
     */
    public function sendLoginCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if email is verified
        if (!$user->email_verified_at) {
            // Auto-send verification email
            if (!$user->verification_token) {
                $user->verification_token = Str::random(64);
                $user->save();
            }

            $verificationUrl = route('verify-email', ['token' => $user->verification_token]);
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

            return back()
                ->withErrors(['email' => 'Your email is not verified. We\'ve sent a new verification link to your email.'])
                ->with('active_tab', 'code-login')
                ->with('verification_sent', true)
                ->onlyInput('email');
        }

        // Check if account is locked
        if ($this->authSecurity->isAccountLocked($user)) {
            $minutes = $user->locked_until->diffInMinutes(now());
            return back()
                ->withErrors(['email' => "Your account is locked. Please try again in {$minutes} minutes."])
                ->with('active_tab', 'code-login')
                ->onlyInput('email');
        }

        // Check rate limiting for code generation
        $rateLimitKey = 'login-code:' . $request->email;
        $rateLimit = $this->authSecurity->checkRateLimit($rateLimitKey, 3, 15); // 3 codes per 15 minutes

        if ($rateLimit['limited']) {
            return back()
                ->withErrors(['email' => 'Too many code requests. Please try again in ' . ceil($rateLimit['retry_after'] / 60) . ' minutes.'])
                ->with('active_tab', 'code-login')
                ->onlyInput('email');
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store code in database
        LoginCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'ip_address' => $request->ip(),
        ]);

        // Send email
        Mail::to($user->email)->send(new LoginCodeMail($code, $user->email, 10));

        return back()
            ->with('code_sent', true)
            ->with('active_tab', 'code-login')
            ->with('success', 'Login code sent to your email!')
            ->onlyInput('email');
    }

    /**
     * Login with code
     */
    public function loginWithCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors(['code' => 'Invalid email or code.'])
                ->with('active_tab', 'code-login')
                ->with('code_sent', true)
                ->onlyInput('email');
        }

        // Check if account is locked
        if ($this->authSecurity->isAccountLocked($user)) {
            $minutes = $user->locked_until->diffInMinutes(now());
            return back()
                ->withErrors(['code' => "Your account is locked. Please try again in {$minutes} minutes."])
                ->with('active_tab', 'code-login')
                ->with('code_sent', true)
                ->onlyInput('email');
        }

        // Find valid code
        $loginCode = LoginCode::where('email', $request->email)
            ->where('code', $request->code)
            ->valid()
            ->first();

        if (!$loginCode) {
            // Increment failed attempts
            $this->authSecurity->incrementFailedAttempts($user);
            $this->authSecurity->recordLoginAttempt($request->email, false, $user->id, 'Invalid login code');

            return back()
                ->withErrors(['code' => 'Invalid or expired code.'])
                ->with('active_tab', 'code-login')
                ->with('code_sent', true)
                ->onlyInput('email');
        }

        // Mark code as used
        $loginCode->markAsUsed();

        // Log the user in
        Auth::login($user);

        // Reset failed attempts
        $this->authSecurity->resetFailedAttempts($user);

        // Detect suspicious activity
        $this->authSecurity->detectSuspiciousActivity($user);

        // Record successful login
        $this->authSecurity->recordLoginAttempt($request->email, true, $user->id);

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function userRegister(Request $request)
    {
        // Base validation rules
        $rules = [
            'register_as' => 'required',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string',
            'password'    => [
                'required',
                'confirmed',
                'min:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/'
            ],
        ];

        $messages = [
            'password.min' => 'Password must be at least 10 characters.',
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
        ];

        // Get role to check if it's company
        $role = Role::where('id', $request->register_as)->first();

        // Add company-specific validation rules
        if ($role && $role->role_key === 'company_admin') {
            $rules['company_name'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // Generate verification token
            $verificationToken = Str::random(64);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'status'   => 'active',
                'role_id'  => $role->id,
                'verification_token' => $verificationToken,
            ]);

            // Save profile
            $profile = new UserProfile([
                'phone'      => $request->phone,
                'address'    => $request->address,
                'zipcode'    => $request->zipcode,
                'country_id' => $request->country_id,
                'state_id'   => $request->state_id,
                'city_id'    => $request->city_id,
            ]);

            if ($request->hasFile('profile_image')) {
                $profile->profile_image = $request->file('profile_image')->store('profiles', 'public');
            }

            $user->profile()->save($profile);

            // If registering as company
            if ($role->role_key === 'company_admin') {
                $company = Company::create([
                    'name'          => $request->company_name,
                    'website'       => $request->company_website,
                    'kvk_number'    => $request->kvk_number,
                    'email'         => $request->company_email,
                    'phone'         => $request->company_phone,
                    'address'       => $request->company_address,
                    'contact_name'  => $request->name,
                    'contact_email' => $request->email,
                    'contact_phone' => $request->phone,
                    'status'        => 'active',
                ]);

                if ($request->hasFile('company_logo')) {
                    $company->logo = $request->file('company_logo')->store('companies', 'public');
                    $company->save();
                }

                $user->update(['company_id' => $company->id]);
            }

            // Send verification email
            $verificationUrl = url('/verify-email/' . $verificationToken);
            Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

            event(new UserRegistered($user));
            DB::commit();
            return redirect()->route('login')->with('success', 'Registration successful! Please check your email to verify your account before logging in.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    /**
     * Show the change password form for temporary passwords
     */
    public function showChangePassword()
    {
        // Only authenticated users with must_change_password flag can access
        $user = Auth::user();

        if (!$user || $user->must_change_password != 1) {
            return redirect()->route('dashboard');
        }

        return view('auth.pages.change-password', [
            'title' => 'Change Password - Required',
        ]);
    }

    /**
     * Update the password for users with temporary passwords
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Verify user must change password
        if (!$user || $user->must_change_password != 1) {
            return redirect()->route('dashboard')->withErrors(['error' => 'Unauthorized action.']);
        }

        // Validate the new password
        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'confirmed',
                'min:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ], [
            'new_password.regex' => 'Password must include uppercase, lowercase, number, and special character.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Check if new password is different from current
        if (Hash::check($validated['new_password'], $user->password)) {
            return back()->withErrors(['new_password' => 'New password must be different from your temporary password.']);
        }

        // Update password and remove must_change_password flag
        $user->update([
            'password' => Hash::make($validated['new_password']),
            'must_change_password' => 0,
        ]);

        // Log the password change
        $this->authSecurity->recordLoginAttempt($user->email, true, $user->id, 'Password changed successfully');

        return redirect()->route('dashboard')->with('success', 'Password changed successfully! You can now access all features.');
    }
}
