<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\Company;

class AuthController extends Controller
{
    //
    public function login()
    {
        $title = 'Login';

        return view('auth.pages.login', compact('title'));
    }

    public function register()
    {
        $title = 'Register';
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
                'email' => $request->email
            ],
            [
                'token' => $token,
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

        if (!$request->has('token') || !$request->has('email')) {
            return redirect()->route('login')->with('error', 'Invalid password reset link.');
        }


        return view('auth.pages.reset-password', compact('title'));
    }


    public function userResetPassword(Request $request)
    {
        // ✅ Validate input
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'token' => ['required'],
            'password' => [
                'required',
                'string',
                'min:10', // secure minimum
                'regex:/[a-z]/',      // lowercase
                'regex:/[A-Z]/',      // uppercase
                'regex:/[0-9]/',      // number
                'regex:/[@$!%*?&]/',  // special character
                'confirmed'           // must match password_confirmation
            ],
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.'
        ]);

        // ✅ Find record in password_reset_tokens
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return redirect()->back()->with('error', 'Invalid or expired reset token.');
        }

        // ✅ Token expiration check (optional: 1 hour)
        if (now()->diffInMinutes($tokenData->created_at) > 60) {
            return redirect()->back()->with('error', 'This reset link has expired. Please request a new one.');
        }

        // ✅ Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60)); // Optional: invalidate existing sessions
        $user->save();

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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $previous = url()->previous();

            // Check if user came from a blog page
            if (str_contains($previous, '/blogs')) {
                return redirect($previous)->with('success', 'Login successful!');
            }

            // Default redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    public function userRegister(Request $request)
    {
        $validated = $request->validate([
            'register_as' => 'required',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $role = Role::where('id', $request->register_as)->first();

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'role_id' => $role->id
            ]);

            // Save profile
            $profile = new UserProfile([
                'phone' => $request->phone,
                'address' => $request->address,
                'zipcode' => $request->zipcode,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id
            ]);

            if ($request->hasFile('profile_image')) {
                $profile->profile_image = $request->file('profile_image')->store('profiles', 'public');
            }

            $user->profile()->save($profile);

            // If registering as company
            if ($role->role_key === 'company_admin') {
                $company = Company::create([
                    'name' => $request->company_name,
                    'website' => $request->company_website,
                    'kvk_number' => $request->kvk_number,
                    'email' => $request->company_email,
                    'phone' => $request->company_phone,
                    'address' => $request->company_address,
                    'contact_name' => $request->name,
                    'contact_email' => $request->email,
                    'contact_phone' => $request->phone,
                    'status' => 'active',
                ]);

                if ($request->hasFile('company_logo')) {
                    $company->logo = $request->file('company_logo')->store('companies', 'public');
                    $company->save();
                }

                $user->update(['company_id' => $company->id]);
            }

            // Send welcome email
            Mail::to($user->email)->send(new WelcomeMail($user));

            DB::commit();
            return redirect()->route('login')->with('success', 'Registration successful! You can now log in.');
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
}
