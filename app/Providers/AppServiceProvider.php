<?php

namespace App\Providers;

use App\Events\BookingCreated;
use App\Listeners\CreateBookingCreatedNotification;
use App\Models\Artist;
use App\Models\BookingRequest;
use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Settings;
use App\Models\SupportTicket;
use App\Models\User;
use App\Observers\AuditObserver;
use App\Services\ActivityLogger;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Events\MessageSending;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 pagination views globally for all paginator links().
        Paginator::useBootstrapFive();

        // Explicitly register booking-created event listener
        Event::listen(BookingCreated::class, CreateBookingCreatedNotification::class);
        Event::listen(MessageSending::class, function (MessageSending $event): void {
            $to = collect($event->message->getTo() ?? [])->keys()->values()->toArray();
            ActivityLogger::info(
                'email.dispatch.started',
                'Email dispatch started',
                [
                    'category' => 'email',
                    'status' => 'started',
                    'metadata' => [
                        'to' => $to,
                        'subject' => $event->message->getSubject(),
                    ],
                ]
            );
        });
        Event::listen(MessageSent::class, function (MessageSent $event): void {
            $to = collect($event->message->getTo() ?? [])->keys()->values()->toArray();
            ActivityLogger::success(
                'email.dispatch.success',
                'Email sent successfully',
                [
                    'category' => 'email',
                    'status' => 'success',
                    'metadata' => [
                        'to' => $to,
                        'subject' => $event->message->getSubject(),
                    ],
                ]
            );
        });

        // Register broad CRUD observers for system-wide auditing.
        $observer = new AuditObserver();
        User::observe($observer);
        Company::observe($observer);
        Artist::observe($observer);
        BookingRequest::observe($observer);
        Payment::observe($observer);
        Review::observe($observer);
        CompanySubscription::observe($observer);
        Notification::observe($observer);
        SupportTicket::observe($observer);

        try {
            if (Schema::hasTable('settings')) {
                $settings = Settings::query()->pluck('value', 'key')->toArray();
                View::share('share', $settings);
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available or settings table doesn't exist
            // This prevents errors during migrations, testing, or initial setup
        }
    }
}
