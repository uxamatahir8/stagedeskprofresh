<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    /**
     * Verify user email with token
     */
    public function verify(Request $request, $token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect('/login')->with('info', 'Email already verified. Please login.');
        }

        // Verify the email
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect('/login')->with('success', 'Email verified successfully! You can now login.');
    }

    /**
     * Resend verification email
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if ($user->email_verified_at) {
            return back()->with('info', 'Email already verified.');
        }

        // Generate new verification token
        $token = Str::random(64);
        $user->verification_token = $token;
        $user->save();

        // Send verification email
        $verificationUrl = url('/verify-email/' . $token);
        \Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $verificationUrl));

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }
}
