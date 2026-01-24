# 📅 StageDesk Pro - Booking Flow Documentation

## Overview
This document provides a comprehensive explanation of the booking system workflow in StageDesk Pro, covering all stages from initial customer request to completion, payment processing, and review submission.

---

## 🔄 Complete Booking Flow

### Phase 1: Booking Creation
```
Customer → Create Booking Request → System Processing → Notification Sent
```

#### 1.1 Customer Initiates Booking
**Entry Points:**
- Customer Portal: `/customer/bookings/create`
- Admin Dashboard: `/bookings/create` (Master Admin/Company Admin can create on behalf of customers)
- **Admin Creates Booking with New Customer Account** (Company Admin creates booking for new customer)
- Public Landing Page: Contact form submission

**Required Information:**
- **Event Details:**
  - Event Type (Wedding, Birthday, Corporate Event, etc.)
  - Event Date (must be in the future)
  - Start Time & End Time
  - Event Location/Venue
  
- **Customer Information:**
  - Full Name (Name + Surname)
  - Date of Birth (validation: must be at least 5 days old)
  - Email Address
  - Phone Number
  - Physical Address

- **Wedding-Specific Fields** (if event type is "Wedding"):
  - Partner Name (required)
  - Wedding Date (required)
  - Wedding Time (required)
  - Wedding Location

- **Music Preferences:**
  - Opening Songs
  - Special Moments (First Dance, Cake Cutting, etc.)
  - Do's (Songs/Genres to include)
  - Don'ts (Songs/Genres to avoid)
  - Spotify Playlist Link (optional)

- **Additional Notes:**
  - General requirements
  - Special requests
  - Company Notes (admin-only field for internal use)

#### 1.2 System Validation
**Controller:** `BookingController@store`
**Route:** `POST /bookings`

**Validation Rules:**
```php
- event_date: required|date|after:today
- user_id: nullable|exists:users,id (nullable when admin creates booking for new customer)
- event_type_id: required|exists:event_types,id
- company_id: nullable|exists:companies,id
- assigned_artist_id: nullable|exists:artists,id
- name: required|string|max:255
- surname: required|string|max:255
- date_of_birth: required|date|before:today-5days
- phone: required|string|max:20
- email: required|email|max:255
- address: required|string|max:255
- create_customer_account: nullable|boolean (admin-only, creates new customer)
```

**Auto-Assignment Logic:**
- **Company Admin:** Auto-assigns their company_id
- **Customer:** Auto-assigns their user_id
- **Master Admin:** Can assign any company/customer

#### 1.2a Admin Creates Booking with New Customer Account

**Feature Overview:**
Company admins can create bookings for customers who don't have accounts yet. The system automatically creates a customer account, generates secure login credentials, and emails them to the new customer.

**Workflow:**
1. **Admin Access:** Company admin navigates to "Create Booking" page
2. **Customer Selection:** Admin leaves customer dropdown empty
3. **Toggle Option:** Admin enables "Create Customer Account" checkbox
4. **Email Entry:** Admin enters customer's email in the booking form
5. **Submission:** Form is submitted

**System Processing:**

**A. Email Validation Check:**
```php
// Check if email already exists in database
$existingUser = User::where('email', $request->email)->first();
```

**B. User Creation (if email doesn't exist):**
```php
if (!$existingUser && $request->create_customer_account) {
    // Generate secure 12-character password
    $password = generateSecurePassword(); // Uppercase, lowercase, numbers, special chars
    
    // Get customer role
    $customerRole = Role::where('role_key', 'customer')->first();
    
    // Create new user
    $newUser = User::create([
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'password' => bcrypt($password),
        'phone' => $request->phone,
        'date_of_birth' => $request->date_of_birth,
        'address' => $request->address,
        'role_id' => $customerRole->id,
        'company_id' => $companyId,
        'email_verified_at' => now(), // Auto-verify admin-created accounts
    ]);
    
    // Log customer creation
    ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => 'customer_created',
        'description' => "Created new customer account for {$newUser->email}",
    ]);
}
```

**C. Credential Email Dispatch:**
```php
// Send account credentials email
Mail::to($newUser->email)->send(new CustomerAccountCreated($newUser, $password, $booking));
```

**Email Template:** `emails.customer-account-created`
**Mailable:** `App\Mail\CustomerAccountCreated`

**Email Content Includes:**
- **Welcome Message:** Professional greeting
- **Login Credentials Box:**
  - Email address
  - Temporary password (displayed in monospace font)
- **Security Warning:** Reminder to change password after first login
- **Booking Details:**
  - Event type
  - Event date
  - Event location
  - Booking status
- **Login Button:** Direct link to `/login`
- **Feature Overview:** What customers can do in their portal
- **Support Information:** Contact details

**D. Success Notification:**
Admin receives success message:
```
"Booking created successfully! A new customer account has been created for [email], 
and login credentials have been sent to their email."
```

**Edge Cases Handled:**
1. **Email Already Exists:**
   - System uses existing user account
   - Creates booking for that user
   - Sends standard booking confirmation (not credential email)
   
2. **Create Account Unchecked + No Customer Selected:**
   - Returns validation error: "Please select a customer or enable 'Create Customer Account'"
   
3. **Customer Selected + Create Account Checked:**
   - JavaScript automatically unchecks "Create Customer Account" when customer is selected
   - Uses selected customer for booking

**Password Security:**
- **Length:** 12 characters minimum
- **Character Types:** Uppercase, lowercase, numbers, special characters
- **Hashing:** bcrypt with Laravel's default cost factor
- **Transmission:** Sent once via email, never stored in plain text
- **First Login:** Customer encouraged to change password immediately

**Auto-Verification:**
- Admin-created accounts are automatically email-verified (`email_verified_at` set to current timestamp)
- Customer can log in immediately without email verification step

**Activity Logging:**
```php
ActivityLog Entry:
- user_id: Admin who created the customer
- action: 'customer_created'
- description: "Created new customer account for [email]"
- created_at: timestamp
```

#### 1.3 Initial Status Assignment
**Status:** `pending`
- Booking is created with "pending" status
- If artist is assigned during creation, status changes to `confirmed` and `confirmed_at` timestamp is set

#### 1.4 Event Triggers & Notifications
**Event:** `BookingCreated`
**Listeners:** 
- Send email notification to customer (confirmation)
- Send email notification to company admin
- Send email notification to assigned artist (if applicable)
- Create activity log entry

**Email Template:** `emails.booking-created`

---

### Phase 2: Artist Assignment

#### 2.1 Artist Selection Methods

**Method A: Direct Assignment (Admin)**
**Route:** `POST /bookings/{booking}/assign-artist`
**Controller:** `BookingController@assignArtist`
**Permissions:** Master Admin, Company Admin

**Process:**
1. Admin selects available artist from company's artist pool
2. System validates artist belongs to the correct company
3. Booking status changes from `pending` → `confirmed`
4. `assigned_artist_id` is set
5. `confirmed_at` timestamp is recorded
6. Notifications sent to:
   - Assigned artist (new booking notification)
   - Customer (artist assigned notification)
   - Company admin (confirmation notification)

**Activity Log:** "Assigned artist {artist_name} to booking #{booking_id}"

**Method B: Artist Request System**
**Route:** `POST /artist-requests`
**Controller:** `ArtistRequestController@store`

**Process:**
1. Multiple artists can submit proposals for a booking
2. Each proposal includes:
   - Proposed price
   - Message to customer
   - Artist availability confirmation
3. Status: `pending`
4. Company admin reviews all proposals
5. Admin accepts one proposal:
   - Status changes to `accepted`
   - Other proposals change to `rejected`
   - Artist gets assigned to booking
   - Booking status → `confirmed`

#### 2.2 Artist Portal Workflow
**Artist Dashboard:** `/artist/bookings`

**Artist Actions:**
- **View Assigned Bookings:** See all bookings assigned to them
- **Accept/Reject:** Respond to booking assignments
- **Submit Proposal:** Propose price for open bookings
- **Mark Complete:** Update status when event is finished

**Artist Notifications:**
- New booking assignment
- Booking modifications
- Customer cancellation
- Payment received

---

### Phase 3: Payment Processing

#### 3.1 Payment Creation
**Route:** `POST /payments`
**Controller:** `PaymentController@store`

**Payment Types:**
1. **Booking Payment** (`type: 'booking'`)
   - Linked to: `booking_requests_id`
   - For: Event booking services
   
2. **Subscription Payment** (`type: 'subscription'`)
   - Linked to: `company_subscription_id`
   - For: Company subscription fees

#### 3.2 Payment Methods
```php
- Credit Card
- Bank Transfer
- PayPal
- Cash
- Other
```

#### 3.3 Payment Status Flow
```
pending → processing → completed
                    ↓
                  failed → refunded
```

**Status Definitions:**
- **pending:** Payment initiated, awaiting processing
- **processing:** Payment gateway is processing transaction
- **completed:** Payment successfully received and verified
- **failed:** Payment attempt failed
- **refunded:** Payment was reversed/refunded

#### 3.4 Payment Verification
**Controller:** `PaymentController@verify`
**Route:** `POST /payments/{payment}/verify`

**Process:**
1. Admin uploads payment proof/attachment (if manual)
2. System validates transaction_id (if gateway)
3. Payment status → `completed`
4. Booking payment status updated
5. Revenue added to company statistics
6. Customer receives payment confirmation email
7. Activity log created

**Permissions:** Master Admin, Company Admin

---

### Phase 4: Booking Execution & Completion

#### 4.1 Pre-Event Stage
**Status:** `confirmed`
**Timeline:** Between confirmation and event date

**Actions Available:**
- **Customer:**
  - Update booking details (if allowed by policy)
  - Cancel booking (cancellation policy applies)
  - Contact assigned artist
  - Make payments

- **Artist:**
  - View full booking details
  - Update preparation notes
  - Communicate with customer
  - Confirm equipment/setup requirements

- **Admin:**
  - Monitor booking status
  - Track payments
  - Handle customer service requests
  - Assign/reassign artists if needed

#### 4.2 Event Day
**Artist Actions:**
- Access booking details via mobile-friendly interface
- View customer preferences (do's/don'ts, special songs)
- Check playlist links
- Note any issues or special requests during event

#### 4.3 Marking Booking as Completed

**Method A: Artist Marks Complete**
**Route:** `POST /artist/bookings/{booking}/complete`
**Controller:** `ArtistPortalController@markBookingCompleted`

**Method B: Admin Marks Complete**
**Route:** `POST /bookings/{booking}/mark-completed`
**Controller:** `BookingController@markCompleted`

**Process:**
1. Validate event date has passed
2. Check all payments are completed
3. Update booking:
   - `status` → `completed`
   - `completed_at` → current timestamp
4. Trigger `BookingCompleted` event
5. Send completion notifications:
   - Customer (with review request)
   - Company admin (for record keeping)
   - Artist (confirmation of completion)
6. Enable review submission for customer
7. Update statistics:
   - Company completed bookings count
   - Artist completed bookings count
   - Total revenue calculations

**Activity Log:** "Booking #{booking_id} marked as completed by {user}"

---

### Phase 5: Review & Rating System

#### 5.1 Review Eligibility
**Conditions:**
- Booking status must be `completed`
- Customer has not already submitted a review
- Review window: typically 30 days after completion

**Check Method:**
```php
$booking->canBeReviewed() // Returns boolean
```

#### 5.2 Review Submission
**Route:** `POST /reviews`
**Controller:** `ReviewController@store`

**Review Data:**
- **Rating:** 1-5 stars (required)
- **Title:** Short review headline (optional)
- **Comment:** Detailed feedback (required, min: 10 characters)
- **Booking ID:** Reference to completed booking
- **Artist ID:** Artist being reviewed
- **Company ID:** Company being reviewed

**Validation:**
```php
- rating: required|integer|min:1|max:5
- comment: required|string|min:10
- booking_id: required|exists:booking_requests,id
```

#### 5.3 Review Processing
1. Validate review eligibility
2. Create review record
3. Update artist average rating:
   ```php
   $artist->rating = $artist->reviews()->avg('rating');
   $artist->save();
   ```
4. Update company statistics
5. Send notification to artist (new review received)
6. Activity log created
7. Review appears on:
   - Artist profile page
   - Company dashboard
   - Public artist listing (if approved)

#### 5.4 Review Moderation
**Admin Actions:**
- **Approve:** Review visible publicly
- **Reject:** Review hidden with reason
- **Feature as Testimonial:** Promote to homepage

**Controller:** `ReviewController@updateStatus`
**Route:** `PUT /reviews/{review}/status`

---

### Phase 6: Cancellation Flow

#### 6.1 Cancellation Triggers

**Who Can Cancel:**
- **Customer:** Can cancel their own bookings
- **Admin:** Can cancel any booking
- **Artist:** Can reject/cancel before confirmation

#### 6.2 Cancellation Process
**Route:** `POST /bookings/{booking}/cancel`
**Controller:** `BookingController@cancel`

**Steps:**
1. Validate cancellation permission
2. Check cancellation policy (time before event)
3. Calculate refund amount (if applicable)
4. Update booking:
   - `status` → `cancelled`
   - `cancelled_at` → current timestamp
   - `cancellation_reason` → reason text
5. Process refund (if payment completed)
6. Send cancellation notifications:
   - Customer (cancellation confirmation + refund info)
   - Assigned artist (booking cancelled alert)
   - Company admin (for records)
7. Free up artist's schedule
8. Update statistics (remove from pending/confirmed counts)

**Activity Log:** "Booking #{booking_id} cancelled by {user}. Reason: {reason}"

#### 6.3 Cancellation Policies
**Time-Based Refund Policy:**
```
- More than 14 days before event: 100% refund
- 7-14 days before event: 50% refund
- Less than 7 days before event: No refund (or partial based on policy)
- After event date: No refund
```

**Policy Configuration:** Can be customized in settings per company

---

## 📊 Booking Status Lifecycle

### Status Flow Diagram
```
[Customer Creates] → pending
                       ↓
[Artist Assigned] → confirmed
                       ↓
[Event Happens] → completed → [Can Be Reviewed]
                       ↓
                  [Review Submitted]

Alternative Paths:
pending → cancelled (before assignment)
confirmed → cancelled (before event)
```

### Complete Status List
| Status | Description | Can Edit? | Next Status |
|--------|-------------|-----------|-------------|
| `pending` | Awaiting artist assignment | Yes | confirmed, cancelled |
| `confirmed` | Artist assigned, awaiting event | Limited | completed, cancelled |
| `completed` | Event finished successfully | No | N/A |
| `cancelled` | Booking was cancelled | No | N/A |

---

## 👥 Role-Based Booking Permissions

### Master Admin
**Full Access:**
- ✅ View all bookings across all companies
- ✅ Create bookings for any customer/company
- ✅ Edit any booking
- ✅ Delete any booking
- ✅ Assign/reassign artists
- ✅ Mark completed/cancelled
- ✅ Process payments
- ✅ Manage reviews

**Dashboard Route:** `/bookings`
**Controller:** `BookingController`

### Company Admin
**Company-Scoped Access:**
- ✅ View bookings for their company only
- ✅ Create bookings for company customers
- ✅ Edit company bookings
- ✅ Assign company artists
- ✅ Mark completed/cancelled
- ✅ Process company payments
- ✅ View company reviews

**Scope Filter:**
```php
BookingRequest::where('company_id', Auth::user()->company_id)
```

**Dashboard Route:** `/admin/bookings`
**Controller:** `CompanyAdminController`

### Artist
**Assigned Bookings Only:**
- ✅ View bookings assigned to them
- ✅ Update booking notes (limited fields)
- ✅ Mark as completed
- ✅ Submit proposals for open bookings
- ❌ Cannot create new bookings
- ❌ Cannot edit customer details
- ❌ Cannot cancel bookings

**Scope Filter:**
```php
BookingRequest::where('assigned_artist_id', Auth::user()->artist->id)
```

**Dashboard Route:** `/artist/bookings`
**Controller:** `ArtistPortalController`

### Customer
**Own Bookings Only:**
- ✅ View their own bookings
- ✅ Create new booking requests
- ✅ Update booking details (before confirmation)
- ✅ Cancel bookings (with policy constraints)
- ✅ Submit reviews (after completion)
- ✅ Make payments
- ❌ Cannot assign artists
- ❌ Cannot mark as completed
- ❌ Cannot view other customers' bookings

**Scope Filter:**
```php
BookingRequest::where('user_id', Auth::user()->id)
```

**Dashboard Route:** `/customer/bookings`
**Controller:** `CustomerPortalController`

---

## 🔔 Notification System

### Booking-Related Notifications

#### 1. Booking Created
**Trigger:** New booking submitted
**Recipients:**
- Customer: "Your booking request #X has been received"
- Company Admin: "New booking request from {customer}"
- Assigned Artist (if pre-assigned): "You have been assigned to booking #X"

**Template:** `emails.booking-created`

#### 2. Customer Account Created (NEW)
**Trigger:** Company admin creates booking for new customer with "Create Customer Account" enabled
**Recipients:**
- Customer: "Your Account Has Been Created - StageDesk Pro"

**Template:** `emails.customer-account-created`
**Mailable:** `App\Mail\CustomerAccountCreated`

**Email Includes:**
- **Welcome Message:** Professional greeting with celebration
- **Login Credentials:**
  - Email address
  - Temporary password (12-character secure password)
- **Security Warning:** Reminder to change password on first login
- **Booking Details:**
  - Event type
  - Event date
  - Event location  
  - Current booking status
- **Direct Login Link:** Button linking to `/login`
- **Feature Overview:** What customers can do in their account
- **Support Contact:** How to reach support team

**Password Specifications:**
- Length: 12 characters
- Character types: Uppercase, lowercase, numbers, special characters
- Hashing: bcrypt
- One-time transmission: Sent once via email, never stored in plain text

**Auto-Verification:**
- Email is automatically verified for admin-created accounts
- Customer can log in immediately without email confirmation step

#### 3. Artist Assigned
**Trigger:** Artist assigned to booking
**Recipients:**
- Customer: "{Artist} has been assigned to your booking"
- Artist: "You have been assigned to a new booking on {date}"
- Company Admin: "Artist {name} assigned to booking #{id}"

**Template:** `emails.artist-assigned`

#### 3. Booking Confirmed
**Trigger:** Booking moves to confirmed status
**Recipients:**
- Customer: "Your booking is confirmed! Event on {date}"
- Artist: "Booking confirmed. Event on {date}"

**Template:** `emails.booking-confirmed`

#### 4. Payment Received
**Trigger:** Payment status → completed
**Recipients:**
- Customer: "Payment received. Receipt attached."
- Company Admin: "Payment of ${amount} received for booking #{id}"

**Template:** `emails.payment-received`

#### 5. Booking Completed
**Trigger:** Booking marked as completed
**Recipients:**
- Customer: "Thanks for your event! Please review your experience"
- Artist: "Booking #{id} has been marked as completed"
- Company Admin: "Booking #{id} completed successfully"

**Template:** `emails.booking-completed`

#### 6. Review Submitted
**Trigger:** Customer submits review
**Recipients:**
- Artist: "You received a {rating}-star review from {customer}"
- Company Admin: "New review for {artist}"

**Template:** `emails.review-submitted`

#### 7. Booking Cancelled
**Trigger:** Booking status → cancelled
**Recipients:**
- Customer: "Your booking has been cancelled. Refund: ${amount}"
- Artist: "Booking #{id} on {date} has been cancelled"
- Company Admin: "Booking #{id} cancelled by {user}"

**Template:** `emails.booking-cancelled`

### Notification Channels
- **Email:** Via Laravel Mail system
- **Database:** Stored in `notifications` table for in-app display
- **Real-time:** (Can be extended with WebSockets/Pusher)

### Notification Settings
**User Preferences:** Users can configure which notifications they want to receive
**Location:** User Settings > Notifications

---

## 💾 Database Schema for Booking System

### booking_requests Table
```sql
CREATE TABLE booking_requests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,                    -- Customer
    company_id BIGINT NULL,                     -- Assigned company
    assigned_artist_id BIGINT NULL,             -- Assigned artist
    event_type_id BIGINT NOT NULL,              -- Type of event
    
    -- Customer Details
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    
    -- Event Details
    event_date DATE NOT NULL,
    start_time TIME NULL,
    end_time TIME NULL,
    
    -- Music Preferences
    opening_songs TEXT NULL,
    special_moments TEXT NULL,
    dos TEXT NULL,                              -- JSON: preferred songs/genres
    donts TEXT NULL,                            -- JSON: songs to avoid
    playlist_spotify VARCHAR(500) NULL,
    
    -- Wedding-Specific
    wedding_date DATE NULL,
    wedding_time TIME NULL,
    wedding_location VARCHAR(500) NULL,
    partner_name VARCHAR(255) NULL,
    
    -- Notes
    additional_notes TEXT NULL,
    company_notes TEXT NULL,                    -- Admin-only notes
    
    -- Status & Timestamps
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    confirmed_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    cancellation_reason TEXT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,                  -- Soft deletes
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (assigned_artist_id) REFERENCES artists(id),
    FOREIGN KEY (event_type_id) REFERENCES event_types(id),
    
    INDEX idx_status (status),
    INDEX idx_event_date (event_date),
    INDEX idx_company_id (company_id),
    INDEX idx_assigned_artist_id (assigned_artist_id)
);
```

### Related Tables

#### artist_requests (Proposal System)
```sql
CREATE TABLE artist_requests (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_requests_id BIGINT NOT NULL,
    artist_id BIGINT NOT NULL,
    proposed_price DECIMAL(10,2) NOT NULL,
    message TEXT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (booking_requests_id) REFERENCES booking_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE CASCADE
);
```

#### payments
```sql
CREATE TABLE payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_requests_id BIGINT NULL,           -- NULL if subscription payment
    company_subscription_id BIGINT NULL,       -- NULL if booking payment
    user_id BIGINT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    transaction_id VARCHAR(255) NULL,
    payment_method ENUM('credit_card', 'bank_transfer', 'paypal', 'cash', 'other'),
    attachment VARCHAR(500) NULL,              -- Proof of payment upload
    type ENUM('booking', 'subscription') NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (booking_requests_id) REFERENCES booking_requests(id),
    FOREIGN KEY (company_subscription_id) REFERENCES company_subscriptions(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### reviews
```sql
CREATE TABLE reviews (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,                   -- Customer who reviewed
    artist_id BIGINT NOT NULL,
    company_id BIGINT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(255) NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    featured BOOLEAN DEFAULT 0,                -- Featured as testimonial
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (booking_id) REFERENCES booking_requests(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (artist_id) REFERENCES artists(id),
    FOREIGN KEY (company_id) REFERENCES companies(id),
    
    UNIQUE KEY unique_user_booking (user_id, booking_id)
);
```

---

## 🔍 Booking Search & Filtering

### Available Filters

#### Admin Dashboard Filters
```php
// routes/bookings.php
GET /bookings?status={status}&company_id={id}&date_from={date}&date_to={date}&artist_id={id}
```

**Filter Parameters:**
- `status`: pending, confirmed, completed, cancelled
- `company_id`: Filter by company (master admin only)
- `date_from`: Event date range start
- `date_to`: Event date range end
- `artist_id`: Filter by assigned artist
- `search`: Text search in customer name, email, or booking notes

**Controller Implementation:** `BookingController@index`

#### Customer Portal Filters
```php
// routes/customer.php
GET /customer/bookings?status={status}&event_type={id}
```

**Filter Parameters:**
- `status`: View specific status bookings
- `event_type`: Filter by event type

#### Artist Portal Filters
```php
// routes/artist.php
GET /artist/bookings?status={status}&date={date}
```

**Filter Parameters:**
- `status`: confirmed, completed
- `date`: Filter by event date
- `upcoming`: Only future bookings
- `past`: Only past bookings

---

## 📈 Booking Analytics & Reports

### Key Metrics Tracked

#### Company Dashboard
1. **Total Bookings:** Count of all bookings
2. **Pending Bookings:** Awaiting artist assignment
3. **Confirmed Bookings:** With assigned artist
4. **Completed Bookings:** Successfully finished events
5. **Cancellation Rate:** (Cancelled / Total) × 100
6. **Average Rating:** From completed booking reviews
7. **Total Revenue:** Sum of completed payments
8. **Booking Trends:** Monthly/weekly booking counts

#### Artist Dashboard
1. **Assigned Bookings:** Current confirmed events
2. **Completed Events:** Historical completion count
3. **Average Rating:** From customer reviews
4. **Upcoming Events:** Next 7/30 days
5. **Earnings:** Total from completed bookings

#### Customer Dashboard
1. **Total Bookings:** All-time booking count
2. **Upcoming Events:** Confirmed future bookings
3. **Past Events:** Completed bookings
4. **Pending Requests:** Awaiting confirmation

### Report Generation
**Location:** Admin > Reports > Booking Reports

**Available Reports:**
- Monthly Booking Summary (PDF/Excel export)
- Artist Performance Report
- Revenue by Event Type
- Customer Booking History
- Cancellation Analysis

---

## 🛠️ Technical Implementation Details

### Controllers

#### BookingController
**Path:** `app/Http/Controllers/BookingController.php`
**Key Methods:**
- `index()`: List bookings with role-based filtering
- `create()`: Show booking creation form
- `store()`: Save new booking request
- `show()`: Display booking details
- `edit()`: Show booking edit form
- `update()`: Update booking information
- `destroy()`: Soft delete booking
- `assignArtist()`: Assign artist to booking
- `markCompleted()`: Mark booking as completed
- `cancel()`: Cancel booking with refund logic

#### Customer Portal Controller
**Path:** `app/Http/Controllers/Customer/CustomerPortalController.php`
**Key Methods:**
- `myBookings()`: Customer's booking list
- `createBooking()`: Create booking form
- `bookingDetails()`: View booking details
- `cancelBooking()`: Customer-initiated cancellation

#### Artist Portal Controller
**Path:** `app/Http/Controllers/Artist/ArtistPortalController.php`
**Key Methods:**
- `myBookings()`: Assigned bookings list
- `bookingDetails()`: View booking details
- `acceptBooking()`: Accept booking assignment
- `rejectBooking()`: Reject booking assignment
- `markBookingCompleted()`: Mark event as completed

### Models

#### BookingRequest Model
**Path:** `app/Models/BookingRequest.php`

**Relationships:**
```php
- user(): BelongsTo (Customer)
- company(): BelongsTo
- assignedArtist(): BelongsTo (Artist)
- eventType(): BelongsTo
- bookedServices(): HasMany
- artistRequests(): HasMany
- payments(): HasMany
- reviews(): HasMany
```

**Scopes:**
```php
- scopeUserBookings($query): Filter by current user
- scopeCompanyBookings($query): Filter by company
```

**Methods:**
```php
- canBeReviewed(): bool - Check if customer can submit review
```

### Routes

#### Admin Routes
**File:** `routes/bookings.php`
```php
Route::middleware(['auth'])->group(function () {
    // CRUD
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    
    // Actions
    Route::post('/bookings/{booking}/assign-artist', [BookingController::class, 'assignArtist'])
        ->name('bookings.assign-artist');
    Route::post('/bookings/{booking}/mark-completed', [BookingController::class, 'markCompleted'])
        ->name('bookings.mark-completed');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');
});
```

#### Customer Routes
**File:** `routes/customer.php`
```php
Route::prefix('customer')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/bookings', [CustomerPortalController::class, 'myBookings'])
        ->name('customer.bookings');
    Route::get('/bookings/{booking}', [CustomerPortalController::class, 'bookingDetails'])
        ->name('customer.bookings.details');
    Route::get('/bookings/create', [CustomerPortalController::class, 'createBooking'])
        ->name('customer.bookings.create');
    Route::post('/bookings/{booking}/cancel', [CustomerPortalController::class, 'cancelBooking'])
        ->name('customer.bookings.cancel');
});
```

#### Artist Routes
**File:** `routes/artist.php`
```php
Route::prefix('artist')->middleware(['auth', 'role:artist'])->group(function () {
    Route::get('/bookings', [ArtistPortalController::class, 'myBookings'])
        ->name('artist.bookings');
    Route::get('/bookings/{booking}', [ArtistPortalController::class, 'bookingDetails'])
        ->name('artist.bookings.details');
    Route::post('/bookings/{booking}/accept', [ArtistPortalController::class, 'acceptBooking'])
        ->name('artist.bookings.accept');
    Route::post('/bookings/{booking}/reject', [ArtistPortalController::class, 'rejectBooking'])
        ->name('artist.bookings.reject');
    Route::post('/bookings/{booking}/complete', [ArtistPortalController::class, 'markBookingCompleted'])
        ->name('artist.bookings.complete');
});
```

### Events

#### BookingCreated Event
**Path:** `app/Events/BookingCreated.php`
**Trigger:** After new booking is stored
**Listeners:** Email notifications to customer, admin, and artist

#### BookingStatusChanged Event
**Path:** `app/Events/BookingStatusChanged.php`
**Trigger:** When booking status changes
**Listeners:** Status change notifications

---

## 🔐 Security & Permissions

### Access Control Matrix

| Action | Master Admin | Company Admin | Artist | Customer |
|--------|-------------|---------------|--------|----------|
| View All Bookings | ✅ | ✅ (Company only) | ❌ | ❌ |
| View Own Bookings | ✅ | ✅ | ✅ | ✅ |
| Create Booking | ✅ | ✅ | ❌ | ✅ |
| Edit Booking | ✅ | ✅ (Before event) | ❌ | ✅ (Before confirm) |
| Delete Booking | ✅ | ✅ | ❌ | ❌ |
| Assign Artist | ✅ | ✅ | ❌ | ❌ |
| Mark Completed | ✅ | ✅ | ✅ | ❌ |
| Cancel Booking | ✅ | ✅ | ✅ (Before event) | ✅ |
| Submit Review | ❌ | ❌ | ❌ | ✅ |
| Process Payment | ✅ | ✅ | ❌ | ✅ |

### Permission Checks

**Booking Access Validation:**
```php
private function canAccessBooking($booking, $user, $roleKey): bool
{
    return match ($roleKey) {
        'master_admin' => true,
        'company_admin' => $booking->company_id === $user->company_id,
        'artist' => $booking->assigned_artist_id === optional($user->artist)->id,
        'customer' => $booking->user_id === $user->id,
        default => false,
    };
}
```

**Edit Permission Validation:**
```php
private function canEditBooking($booking, $user, $roleKey): bool
{
    // Cannot edit completed or cancelled bookings
    if (in_array($booking->status, ['completed', 'cancelled'])) {
        return false;
    }
    
    // Customers can only edit their own pending bookings
    if ($roleKey === 'customer') {
        return $booking->user_id === $user->id && $booking->status === 'pending';
    }
    
    // Admins can edit their company's bookings
    if ($roleKey === 'company_admin') {
        return $booking->company_id === $user->company_id;
    }
    
    // Master admin can edit any booking
    return $roleKey === 'master_admin';
}
```

---

## 🎯 Best Practices

### For Developers

1. **Always Use Transactions:** Wrap multi-step operations in DB transactions
   ```php
   DB::beginTransaction();
   try {
       // Operations
       DB::commit();
   } catch (\Exception $e) {
       DB::rollBack();
       throw $e;
   }
   ```

2. **Validate Permissions:** Check role-based permissions before any action
3. **Log Activities:** Use ActivityLog for audit trail
4. **Send Notifications:** Always notify relevant parties of status changes
5. **Handle Errors Gracefully:** Use try-catch and return meaningful error messages

### For Administrators

1. **Verify Customer Details:** Always double-check contact information
2. **Assign Artists Promptly:** Minimize pending booking time
3. **Monitor Payment Status:** Follow up on pending payments
4. **Review Cancellations:** Understand reasons for pattern analysis
5. **Encourage Reviews:** Remind customers to submit feedback

### For Customers

1. **Book Early:** Popular artists get booked quickly
2. **Provide Detailed Preferences:** Help artists deliver better service
3. **Confirm Details:** Review all booking information before confirming
4. **Communicate Issues:** Contact support for any concerns
5. **Submit Reviews:** Help other customers and improve service

---

## 🐛 Common Issues & Troubleshooting

### Issue 1: Booking Not Appearing
**Symptoms:** Booking created but not visible
**Causes:**
- Role-based filtering hiding booking
- Company_id not set correctly
- Soft-deleted record

**Solution:**
```php
// Check with trashed records
BookingRequest::withTrashed()->find($id);

// Verify company assignment
dd($booking->company_id, Auth::user()->company_id);
```

### Issue 2: Cannot Assign Artist
**Symptoms:** Artist assignment fails
**Causes:**
- Artist not in same company as booking
- Artist already assigned to another booking at same time
- Insufficient permissions

**Solution:**
- Verify artist company_id matches booking company_id
- Check artist schedule for conflicts
- Confirm user has admin role

### Issue 3: Payment Not Linking
**Symptoms:** Payment created but not showing on booking
**Causes:**
- Incorrect booking_requests_id
- Payment type set to 'subscription' instead of 'booking'

**Solution:**
```php
Payment::create([
    'booking_requests_id' => $booking->id,  // Correct field name
    'type' => 'booking',                     // Must be 'booking'
    'status' => 'pending',
    // ...
]);
```

### Issue 4: Email Notifications Not Sending
**Symptoms:** No emails received after booking events
**Causes:**
- Mail configuration incorrect
- Queue not running
- Email address invalid

**Solution:**
```bash
# Check mail config
php artisan config:cache

# Start queue worker
php artisan queue:work

# Test email
php artisan tinker
Mail::raw('Test', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

---

## 📚 Additional Resources

### Related Documentation
- [API Documentation](API_DOCUMENTATION.md)
- [Database Schema](DATABASE_SCHEMA.md)
- [Email System](EMAIL_SYSTEM_DOCUMENTATION.md)
- [Installation Guide](INSTALLATION_GUIDE.md)

### Code Examples
- [BookingController.php](app/Http/Controllers/BookingController.php)
- [BookingRequest Model](app/Models/BookingRequest.php)
- [Booking Routes](routes/bookings.php)

### Testing
```bash
# Run booking feature tests
php artisan test --filter=BookingTest

# Run specific test
php artisan test --filter=test_customer_can_create_booking
```

---

## 📝 Changelog

### Version 2.0 (Current)
- Added artist request/proposal system
- Implemented review eligibility check
- Enhanced cancellation with refund logic
- Added wedding-specific fields
- Improved notification system

### Version 1.5
- Added role-based filtering
- Implemented activity logging
- Enhanced payment tracking
- Added booking completion workflow

### Version 1.0
- Initial booking system
- Basic CRUD operations
- Email notifications
- Simple artist assignment

---

## 🤝 Contributing

To contribute to the booking system:

1. Review existing booking flow
2. Test changes with multiple roles
3. Update this documentation
4. Submit pull request with detailed description

---

**Document Version:** 2.0  
**Last Updated:** January 25, 2026  
**Author:** StageDesk Pro Development Team  
**Contact:** support@stagedeskpro.local
