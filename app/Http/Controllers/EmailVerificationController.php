<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogger;
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
            ActivityLogger::warning(
                'auth.verify_email.invalid_token',
                'Email verification failed: invalid token',
                ['category' => 'auth', 'action' => 'verify_email']
            );
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            ActivityLogger::info(
                'auth.verify_email.already_verified',
                'Email verification requested for already verified account',
                ['category' => 'auth', 'action' => 'verify_email', 'user_id' => $user->id]
            );
            return redirect('/login')->with('info', 'Email already verified. Please login.');
        }

        // Verify the email
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();
        ActivityLogger::success(
            'auth.verify_email.success',
            'Email verified successfully',
            ['category' => 'auth', 'action' => 'verify_email', 'user_id' => $user->id]
        );

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
            ActivityLogger::warning(
                'auth.verify_email.resend_user_not_found',
                'Verification resend failed because user was not found',
                ['category' => 'auth', 'action' => 'verify_email', 'metadata' => ['email' => $request->email]]
            );
            return back()->with('error', 'User not found.');
        }

        if ($user->email_verified_at) {
            ActivityLogger::info(
                'auth.verify_email.resend_already_verified',
                'Verification resend skipped for verified account',
                ['category' => 'auth', 'action' => 'verify_email', 'user_id' => $user->id]
            );
            return back()->with('info', 'Email already verified.');
        }

        // Generate new verification token
        $token = Str::random(64);
        $user->verification_token = $token;
        $user->save();

        // Send verification email
        $verificationUrl = url('/verify-email/' . $token);
        try {
            \Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $verificationUrl));
            ActivityLogger::success(
                'auth.verify_email.resend_success',
                'Verification email re-sent successfully',
                ['category' => 'auth', 'action' => 'verify_email', 'user_id' => $user->id]
            );
        } catch (\Throwable $e) {
            ActivityLogger::error(
                'auth.verify_email.resend_failed',
                'Verification resend email failed',
                [
                    'category' => 'email',
                    'action' => 'send',
                    'user_id' => $user->id,
                    'exception' => ['message' => $e->getMessage()],
                ]
            );

            return back()->with('error', 'Unable to send verification email at the moment.');
        }

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }
}
