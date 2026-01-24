# Email System Documentation

## Overview

The StageDeskPro platform includes a comprehensive email notification system that keeps users informed about all important events, security alerts, and system activities. The email system is built using Laravel's Mail facade with Mailable classes and Blade templates.

## Email Configuration

### SMTP Setup (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # or your SMTP server
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@stagedeskpro.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Supported Mail Drivers
- **smtp**: Standard SMTP server
- **sendmail**: Local sendmail
- **mailgun**: Mailgun API
- **ses**: Amazon SES
- **postmark**: Postmark API
- **log**: Development (logs to storage/logs/laravel.log)

### Testing Configuration

For development/testing, use `MAIL_MAILER=log` to log emails instead of sending them.

## Email Types

### 1. User Verification Email

**Mailable**: `App\Mail\VerifyEmail`  
**View**: `resources/views/emails/verify-email.blade.php`  
**Triggered**: After user registration

**Purpose**: Confirm user's email address before allowing system access

**Contains**:
- Welcome message
- Verification link (expires in 24 hours)
- Benefits of email verification
- Security warning

**Usage**:
```php
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;

$verificationUrl = url('/verify-email/' . $verificationToken);
Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));
```

---

### 2. Login Code Email

**Mailable**: `App\Mail\LoginCode`  
**View**: `resources/views/emails/login-code.blade.php`  
**Triggered**: When user requests code-based login

**Purpose**: Send 6-digit verification code for passwordless login

**Contains**:
- 6-digit numeric code
- Code validity period (10 minutes)
- Email address verification
- Security warning

**Usage**:
```php
use App\Mail\LoginCode as LoginCodeMail;

$code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
Mail::to($user->email)->send(new LoginCodeMail($code, $user->email, 10));
```

---

### 3. Booking Created Email

**Mailable**: `App\Mail\BookingCreated`  
**View**: `resources/views/emails/booking-created.blade.php`  
**Triggered**: When new booking is created

**Purpose**: Confirm booking details to customer

**Contains**:
- Booking confirmation
- Complete booking details (date, time, venue, duration)
- Event type information
- Special requests (if any)
- Next steps information

**Usage**:
```php
use App\Mail\BookingCreated;

if ($booking->customer && $booking->customer->email) {
    Mail::to($booking->customer->email)->send(new BookingCreated($booking));
}
```

---

### 4. Artist Assigned Email

**Mailable**: `App\Mail\ArtistAssigned`  
**View**: `resources/views/emails/artist-assigned.blade.php`  
**Triggered**: When artist is assigned or reassigned to booking

**Purpose**: Notify customer about artist assignment

**Contains**:
- Artist information (name, specialization, experience)
- Event details
- Booking status
- Next steps

**Usage**:
```php
use App\Mail\ArtistAssigned;

$isReassignment = $booking->assigned_artist_id !== null;
Mail::to($booking->customer->email)->send(
    new ArtistAssigned($booking->fresh(), $isReassignment)
);
```

---

### 5. New Booking for Artist Email

**Mailable**: `App\Mail\NewBookingForArtist`  
**View**: `resources/views/emails/new-booking-for-artist.blade.php`  
**Triggered**: When artist is assigned to booking

**Purpose**: Notify artist about new booking assignment

**Contains**:
- Event details (date, time, venue, duration)
- Customer information
- Special requests
- Payment information
- Action required notice

**Usage**:
```php
use App\Mail\NewBookingForArtist;

if ($artist->user && $artist->user->email) {
    Mail::to($artist->user->email)->send(new NewBookingForArtist($booking));
}
```

---

### 6. Booking Status Changed Email

**Mailable**: `App\Mail\BookingStatusChanged`  
**View**: `resources/views/emails/booking-status-changed.blade.php`  
**Triggered**: When booking status changes

**Purpose**: Notify customer about booking status updates

**Contains**:
- Status transition (old → new)
- Booking details
- Status-specific messages
- Next steps

**Supported Status Changes**:
- pending → confirmed
- pending → rejected
- confirmed → completed
- confirmed → cancelled

**Usage**:
```php
use App\Mail\BookingStatusChanged;

$oldStatus = $booking->status;
$booking->update(['status' => 'confirmed']);

Mail::to($booking->customer->email)->send(
    new BookingStatusChanged($booking->fresh(), $oldStatus, 'confirmed')
);
```

---

### 7. Artist Share Request Email

**Mailable**: `App\Mail\ArtistShareRequest`  
**View**: `resources/views/emails/artist-share-request.blade.php`  
**Triggered**: When company shares artist with another company

**Purpose**: Notify recipient company about artist sharing request

**Contains**:
- Artist information
- Sharing company details
- Notes from sharing company
- Benefits of artist sharing
- Action required

**Usage**:
```php
use App\Mail\ArtistShareRequest;

$recipientCompany = Company::find($request->company_id);
if ($recipientCompany && $recipientCompany->user) {
    Mail::to($recipientCompany->user->email)->send(
        new ArtistShareRequest($sharedArtist->fresh())
    );
}
```

---

### 8. Security Alert Email

**Mailable**: `App\Mail\SecurityAlert`  
**View**: `resources/views/emails/security-alert.blade.php`  
**Triggered**: On security events

**Purpose**: Alert users about security-related activities

**Alert Types**:

#### Account Locked
Sent after 5 failed login attempts
- Lock duration (30 minutes)
- IP address
- Time of lock
- Recovery instructions

#### Suspicious Activity
Sent when unusual patterns detected
- Activity type
- IP address
- Location
- Recommended actions

#### Password Changed
Sent after successful password change
- IP address
- Time of change
- Security warning if not user

#### New Device Login
Sent when logging in from unrecognized device
- Device information
- Browser details
- IP address
- Location

**Usage**:
```php
use App\Mail\SecurityAlert;

// Account locked
Mail::to($user->email)->send(
    new SecurityAlert($user, 'account_locked', [
        'failed_attempts' => 5,
        'lock_duration' => 30,
        'ip_address' => request()->ip(),
        'time' => now()->format('F d, Y h:i A')
    ])
);

// Suspicious activity
Mail::to($user->email)->send(
    new SecurityAlert($user, 'suspicious_activity', [
        'activity_type' => 'Multiple IP addresses detected',
        'ip_address' => request()->ip(),
        'location' => 'Unknown',
        'time' => now()->format('F d, Y h:i A')
    ])
);

// Password changed
Mail::to($user->email)->send(
    new SecurityAlert($user, 'password_changed', [
        'ip_address' => request()->ip(),
        'time' => now()->format('F d, Y h:i A')
    ])
);
```

---

## Email Template Structure

### Base Layout
**File**: `resources/views/emails/layout.blade.php`

All emails extend this base layout which provides:
- Professional header with logo and app name
- Responsive design
- Consistent styling
- Footer with contact information

**Layout Components**:
- `.email-container`: Main wrapper
- `.header`: Logo and app name
- `.content`: Main email content (@yield)
- `.footer`: Copyright and contact info

**Helper CSS Classes**:
- `.button`: Primary action button (blue)
- `.code-box`: Display verification codes
- `.code`: Styled code text
- `.info-box`: Information blocks (blue)
- `.warning-box`: Warning blocks (yellow)
- `.success-box`: Success blocks (green)
- `.details-table`: Data tables

### Customizing Email Templates

#### 1. Update Base Layout
Edit `resources/views/emails/layout.blade.php` to:
- Change logo
- Update color scheme
- Modify footer text
- Add custom CSS

#### 2. Customize Individual Emails
Each email view can be customized independently:
```blade
@extends('emails.layout')

@section('content')
    <h2>Your Custom Title</h2>
    <p>Your custom content...</p>
    
    <div class="info-box">
        <strong>Info:</strong> Your information
    </div>
    
    <a href="{{ $url }}" class="button">Action Button</a>
@endsection
```

---

## Email Queue Configuration

### Enable Email Queue

For production, queue emails for better performance:

**1. Configure Queue Driver** (.env):
```env
QUEUE_CONNECTION=database
```

**2. Run Queue Worker**:
```bash
php artisan queue:work
```

**3. Send Emails to Queue**:
```php
Mail::to($user->email)->queue(new VerifyEmail($user, $url));
```

### Benefits of Queueing
- Faster response times
- Better user experience
- Handles bulk emails efficiently
- Automatic retry on failure

---

## Testing Emails

### 1. Using Mailtrap (Recommended for Development)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

### 2. Using Log Driver

```env
MAIL_MAILER=log
```

Emails will be written to `storage/logs/laravel.log`

### 3. Manual Testing

```bash
php artisan tinker
```

```php
// Test verification email
$user = User::first();
$url = url('/verify-email/test-token');
Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $url));

// Test login code
Mail::to($user->email)->send(new \App\Mail\LoginCode('123456', $user->email, 10));
```

---

## Email Tracking

### Login Attempts Table
Tracks all email-based authentication attempts:
- Email address
- Success/failure status
- IP address
- User agent
- Timestamp

### Login Codes Table
Tracks verification codes:
- Email address
- Code value
- Expiry time
- Used status
- IP address

### Query Examples

```php
// Recent login attempts
$attempts = LoginAttempt::where('email', $email)
    ->where('attempted_at', '>=', now()->subHours(24))
    ->get();

// Valid login codes
$validCodes = LoginCode::where('email', $email)
    ->valid()
    ->get();

// Failed login count
$failedCount = LoginAttempt::where('email', $email)
    ->where('successful', false)
    ->where('attempted_at', '>=', now()->subHour())
    ->count();
```

---

## Troubleshooting

### Emails Not Sending

**Check SMTP Configuration**:
```bash
php artisan config:clear
php artisan cache:clear
```

**Test SMTP Connection**:
```php
// In tinker
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

### Emails Going to Spam

**Solutions**:
1. Configure SPF records for your domain
2. Set up DKIM signing
3. Use authenticated SMTP server
4. Avoid spam trigger words in subject/content
5. Include unsubscribe link (for marketing emails)

### Template Not Rendering

**Check**:
1. Blade syntax errors
2. Missing variables
3. View cache: `php artisan view:clear`
4. File permissions

### Queue Not Processing

**Check**:
1. Queue driver configured
2. Queue worker running: `php artisan queue:work`
3. Failed jobs: `php artisan queue:failed`
4. Restart worker after code changes

---

## Best Practices

### 1. Always Use Queues in Production
```php
// Good
Mail::to($user->email)->queue(new VerifyEmail($user, $url));

// Avoid in production
Mail::to($user->email)->send(new VerifyEmail($user, $url));
```

### 2. Handle Email Failures Gracefully
```php
try {
    Mail::to($user->email)->send(new BookingCreated($booking));
} catch (\Exception $e) {
    \Log::error('Email sending failed: ' . $e->getMessage());
    // Continue with application flow
}
```

### 3. Use Markdown for Simple Emails
```php
// For simple notification emails
Mail::to($user)->send(new \Illuminate\Mail\Markdown('emails.simple'));
```

### 4. Test Before Deploying
- Test all email types
- Check mobile rendering
- Verify all links work
- Test spam score

### 5. Monitor Email Deliverability
- Track bounce rates
- Monitor spam complaints
- Check delivery rates
- Review email logs

---

## Email Analytics

### Tracked Metrics

1. **Delivery Rate**: Emails successfully delivered
2. **Open Rate**: Emails opened by recipients
3. **Click Rate**: Links clicked in emails
4. **Bounce Rate**: Emails that bounced
5. **Spam Rate**: Emails marked as spam

### Integration with Email Services

For advanced analytics, integrate with:
- **Mailgun**: Built-in analytics dashboard
- **SendGrid**: Detailed tracking and reports
- **Postmark**: Delivery insights
- **Amazon SES**: CloudWatch metrics

---

## Security Considerations

### 1. Email Validation
Always validate email addresses before sending:
```php
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    Mail::to($email)->send($mailable);
}
```

### 2. Rate Limiting
Prevent email bombing:
```php
if (RateLimiter::tooManyAttempts('email:' . $email, 3)) {
    // Block sending
}
```

### 3. Token Expiry
All verification tokens should expire:
- Email verification: 24 hours
- Login codes: 10 minutes
- Password reset: 60 minutes

### 4. Sensitive Information
Never include passwords or sensitive data in emails:
- ✅ Send reset link
- ❌ Don't send password

---

## Maintenance

### Clear Email Logs
```bash
# Clear old login attempts (older than 30 days)
php artisan db:table login_attempts --where="attempted_at < DATE_SUB(NOW(), INTERVAL 30 DAY)" --delete

# Clear used login codes (older than 7 days)
php artisan db:table login_codes --where="created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)" --delete
```

### Monitor Queue
```bash
# Check queue size
php artisan queue:monitor

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

---

## Support

For email system issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Review queue logs: `php artisan queue:failed`
3. Test SMTP connection
4. Contact technical support with error details

---

**Last Updated**: January 24, 2026  
**Version**: 1.0  
**Maintained By**: StageDeskPro Development Team
