# 📘 StageDesk Pro - Complete Project Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [System Architecture](#system-architecture)
3. [Features & Modules](#features--modules)
4. [User Roles & Permissions](#user-roles--permissions)
5. [Database Design](#database-design)
6. [API Endpoints](#api-endpoints)
7. [Frontend Structure](#frontend-structure)
8. [Business Logic](#business-logic)
9. [Security & Authentication](#security--authentication)
10. [Deployment & Configuration](#deployment--configuration)

---

## 1. Project Overview

### About StageDesk Pro
StageDesk Pro is a comprehensive entertainment booking and management platform designed to connect customers with professional DJs, artists, and entertainment companies. The system streamlines the entire booking process from initial customer request to event completion and review submission.

### Technology Stack
- **Backend Framework:** Laravel 11.x (PHP 8.2+)
- **Database:** MySQL 8.0+
- **Frontend:** Blade Templates, Bootstrap 5, Alpine.js
- **JavaScript:** Vite, Chart.js for analytics
- **Icons:** Tabler Icons
- **Email:** Laravel Mail with queue support
- **Authentication:** Laravel Breeze/Sanctum

### Project Structure
```
stagedeskprofresh/
├── app/
│   ├── Console/              # Artisan commands
│   ├── Events/               # Event classes
│   ├── Helpers/              # Helper functions
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   │   ├── Admin/       # Admin-specific controllers
│   │   │   ├── Artist/      # Artist portal controllers
│   │   │   └── Customer/    # Customer portal controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form request validation
│   ├── Listeners/            # Event listeners
│   ├── Mail/                 # Email templates (Mailable classes)
│   ├── Models/               # Eloquent models
│   ├── Policies/             # Authorization policies
│   └── Services/             # Business logic services
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/             # Database seeders
│   └── factories/           # Model factories for testing
├── public/                   # Public assets (CSS, JS, images)
├── resources/
│   ├── views/               # Blade templates
│   │   ├── dashboard/      # Admin dashboard views
│   │   ├── landing/        # Public landing pages
│   │   └── emails/         # Email templates
│   ├── js/                  # JavaScript files
│   └── css/                 # Stylesheets
├── routes/                  # Route definitions
│   ├── web.php             # Public routes
│   ├── admin.php           # Admin routes
│   ├── artist.php          # Artist routes
│   ├── customer.php        # Customer routes
│   ├── bookings.php        # Booking routes
│   └── [other modules].php
├── storage/                 # Storage (logs, uploads, cache)
└── tests/                   # PHPUnit tests
```

---

## 2. System Architecture

### Application Layers

#### Presentation Layer (Frontend)
- **Blade Templates:** Server-side rendering for dynamic content
- **Bootstrap 5:** Responsive UI framework
- **Alpine.js:** Lightweight JavaScript framework for interactivity
- **Chart.js:** Data visualization for analytics

#### Application Layer (Business Logic)
- **Controllers:** Handle HTTP requests and responses
- **Services:** Encapsulate complex business logic
- **Events & Listeners:** Decouple operations (e.g., send emails on booking creation)
- **Policies:** Authorization logic for resource access

#### Data Layer (Persistence)
- **Eloquent ORM:** Database interactions via models
- **Query Builder:** Complex queries and aggregations
- **Relationships:** Defined in models (hasMany, belongsTo, etc.)

#### Infrastructure Layer
- **Queue System:** Async job processing (emails, notifications)
- **Cache:** Redis/Memcached for performance
- **Storage:** File uploads (logos, payment proofs, artist images)
- **Logging:** Daily log files for debugging and monitoring

### Design Patterns Used

1. **MVC (Model-View-Controller):** Core Laravel architecture
2. **Repository Pattern:** Data access abstraction (can be extended)
3. **Service Layer Pattern:** Business logic separation
4. **Observer Pattern:** Event/Listener system
5. **Factory Pattern:** Model factories for testing
6. **Singleton Pattern:** Service providers

### Request Lifecycle
```
HTTP Request
    ↓
Route Definition (routes/*.php)
    ↓
Middleware (auth, role check)
    ↓
Controller Method
    ↓
Service Layer (optional)
    ↓
Model/Database
    ↓
Response (View/JSON)
```

---

## 3. Features & Modules

### 3.1 User Management Module

#### Features
- User registration with email verification
- Role-based access control (4 roles)
- Profile management
- Password reset functionality
- Company assignment for users

#### Components
- **Model:** `User.php`
- **Controller:** `UserController.php`, `AuthController.php`
- **Routes:** `routes/web.php` (auth routes)
- **Views:** `resources/views/auth/`, `resources/views/dashboard/pages/users/`

#### Key Functionality
```php
// User Model Relationships
- company(): BelongsTo
- role(): BelongsTo
- artist(): HasOne
- bookings(): HasMany
- payments(): HasMany
- reviews(): HasMany
- notifications(): HasMany
```

---

### 3.2 Company Management Module

#### Features
- Company registration and verification
- Company profile with logo and social links
- Subscription management
- Artist pool management
- Multi-company support for master admin

#### Components
- **Model:** `Company.php`, `CompanySubscription.php`
- **Controller:** `CompanyController.php`, `CompanySubscriptionController.php`
- **Routes:** `routes/companies.php`, `routes/subscription.php`
- **Views:** 
  - `resources/views/dashboard/pages/companies/index_enhanced.blade.php`
  - `resources/views/dashboard/pages/companies/show_enhanced.blade.php`

#### Enhanced Company Features
- **Company Dashboard:** Advanced stats with charts
- **Artist Management:** View and manage company artists
- **Booking Analytics:** Track company booking trends
- **Subscription Display:** Show package features and status
- **Activity Timeline:** Recent company activities

#### Subscription System
**Packages Available:**
- Free Tier: Limited features
- Basic: Standard features
- Professional: Advanced features
- Enterprise: Full features + priority support

**Package Features:**
- Artist slots (number of artists allowed)
- Booking limits (monthly booking capacity)
- Advanced analytics access
- Priority customer support
- Custom branding options

---

### 3.3 Booking Management Module

**See [BOOKING_FLOW_DOCUMENTATION.md](BOOKING_FLOW_DOCUMENTATION.md) for complete details**

#### Quick Overview
- Customer creates booking request OR admin creates booking on behalf of customer
- Admin creates booking with automatic customer account creation (NEW)
- Admin assigns artist from company pool
- Payment processing
- Event execution
- Completion and review submission

#### Company-Created Customer Accounts (NEW FEATURE)

**Overview:**
Company admins can create bookings for customers who don't have accounts yet. The system automatically creates a customer account, generates secure login credentials, and emails them to the new customer.

**Workflow:**
1. Company admin navigates to "Create Booking"
2. Admin leaves customer dropdown empty
3. Admin enables "Create Customer Account" checkbox
4. Admin fills in customer details (email, name, phone, etc.)
5. System creates:
   - New user account with customer role
   - Secure 12-character password (uppercase, lowercase, numbers, special chars)
   - Booking linked to new customer
   - Activity log entry for audit trail
6. Customer receives email with:
   - Login credentials (email + temporary password)
   - Booking details (event type, date, location)
   - Security reminder to change password
   - Direct login link
   - Account features overview

**Key Features:**
- **Email Duplication Check:** System checks if email exists before creating account
- **Auto-Verification:** Admin-created accounts are email-verified automatically
- **Secure Passwords:** 12-character passwords with mixed character types
- **One-Time Transmission:** Password sent once via email, never stored in plain text
- **Activity Logging:** All customer creations logged for audit purposes
- **Form Intelligence:** JavaScript auto-hides "Create Account" option when customer selected

**Email Template:** 
- **Mailable:** `App\Mail\CustomerAccountCreated`
- **View:** `resources/views/emails/customer-account-created.blade.php`
- **Design:** Professional HTML email with inline CSS, color-coded sections

**Security Measures:**
- Password hashing with bcrypt
- Strong password requirements (12+ characters, mixed types)
- Email verification bypass for trusted admin-created accounts
- Activity logging for accountability
- Clear security warnings in credential email

#### Status Flow
```
pending → confirmed → completed
    ↓         ↓
cancelled   cancelled
```

---

### 3.4 Artist Management Module

#### Features
- Artist registration linked to user account
- Artist profiles with bio, image, genres
- Experience and specialization tracking
- Rating system based on reviews
- Artist availability management
- Artist sharing between companies

#### Components
- **Model:** `Artist.php`, `SharedArtist.php`
- **Controller:** `ArtistController.php`, `Admin/ArtistSharingController.php`
- **Routes:** `routes/artists.php`, `routes/artist.php` (portal)
- **Views:** `resources/views/dashboard/pages/artists/`

#### Artist Profile Fields
```php
- stage_name: Professional name
- experience_years: Years in industry
- genres: JSON array of music genres
- specialization: Type of events (weddings, corporate, etc.)
- rating: Average from customer reviews (1-5)
- image: Profile photo
- bio: Detailed description
```

#### Artist Portal Features
- View assigned bookings
- Accept/reject booking assignments
- Mark bookings as completed
- View earnings and statistics
- Update profile and preferences

#### Artist Sharing System
**Purpose:** Allow companies to share artists across organizations

**Process:**
1. Company A shares artist with Company B
2. Sharing request sent with status: `pending`
3. Company B accepts/rejects sharing
4. If accepted, Company B can assign shared artist to their bookings
5. Original company (Company A) retains artist ownership

**Model:** `SharedArtist`
```php
Fields:
- artist_id: Artist being shared
- shared_by_company_id: Original company
- shared_with_company_id: Receiving company
- status: pending, accepted, rejected, revoked
- notes: Sharing terms/notes
- shared_at: When sharing initiated
- accepted_at: When accepted
- revoked_at: When revoked
```

---

### 3.5 Payment Management Module

#### Features
- Multi-purpose payment processing (bookings + subscriptions)
- Multiple payment methods support
- Payment proof upload
- Status tracking (pending → completed → refunded)
- Revenue analytics and reporting

#### Components
- **Model:** `Payment.php`
- **Controller:** `PaymentController.php`
- **Routes:** `routes/payments.php`
- **Views:** `resources/views/dashboard/pages/payments/`

#### Payment Types
1. **Booking Payments:**
   - Linked to: `booking_requests_id`
   - For: Event booking fees
   - Paid by: Customers

2. **Subscription Payments:**
   - Linked to: `company_subscription_id`
   - For: Monthly/yearly subscription fees
   - Paid by: Companies

#### Payment Methods
- Credit Card
- Bank Transfer
- PayPal
- Cash
- Other (custom methods)

#### Payment Status Flow
```
pending → processing → completed
                    ↓
                  failed → refunded
```

---

### 3.6 Review & Rating Module

#### Features
- Customer reviews after completed bookings
- Star rating system (1-5 stars)
- Review moderation by admins
- Featured testimonials for homepage
- Artist rating calculation

#### Components
- **Model:** `Review.php`, `Testimonial.php`
- **Controller:** `ReviewController.php`, `TestimonialController.php`
- **Routes:** `routes/reviews.php`, `routes/testimonials.php`
- **Views:** `resources/views/dashboard/pages/reviews/`

#### Review Process
1. Booking must be `completed`
2. Customer submits review with rating and comment
3. System validates one review per booking
4. Review status: `pending` (moderation)
5. Admin approves/rejects review
6. Approved reviews update artist rating
7. Featured reviews become testimonials

#### Review Eligibility Check
```php
public function canBeReviewed(): bool
{
    return $this->status === 'completed' &&
           !$this->reviews()->where('user_id', Auth::user()->id)->exists();
}
```

---

### 3.7 Event Types Module

#### Features
- Predefined event categories
- Admin can create custom event types
- Used for booking categorization
- Wedding-specific field triggering

#### Components
- **Model:** `EventType.php`
- **Controller:** `EventTypeController.php`
- **Routes:** `routes/event_types.php`
- **Views:** `resources/views/dashboard/pages/event-types/`

#### Default Event Types
- Wedding
- Birthday Party
- Corporate Event
- Club/Bar Night
- Festival
- Private Party
- Anniversary
- Graduation
- Holiday Event
- Other

#### Special Handling
**Wedding Events:** When event type contains "wedding":
- Additional fields required: `wedding_date`, `wedding_time`, `partner_name`, `wedding_location`
- Validation rules adjusted dynamically

---

### 3.8 Blog System Module

#### Features
- Multi-category blog system
- Rich text editor for content
- Featured image support
- Publication status (draft/published)
- SEO-friendly URLs
- Comment system

#### Components
- **Models:** `Blog.php`, `Category.php`, `Comment.php`
- **Controllers:** `BlogController.php`, `CategoryController.php`, `CommentController.php`
- **Routes:** `routes/blogs.php`, `routes/categories.php`
- **Views:** `resources/views/dashboard/pages/blogs/`

#### Blog Fields
```php
- title: Blog post title
- slug: URL-friendly identifier
- content: HTML content
- excerpt: Short summary
- featured_image: Image path
- category_id: Associated category
- author_id: User who created blog
- status: draft, published
- published_at: Publication timestamp
- views: View count
```

---

### 3.9 Support Ticket System

#### Features
- Customer support ticketing
- Priority levels (low, medium, high, urgent)
- Status tracking (open, in progress, closed)
- Agent assignment
- Ticket conversation/replies
- File attachments

#### Components
- **Model:** `SupportTicket.php`
- **Controller:** `SupportTicketController.php`
- **Routes:** `routes/support_tickets.php`
- **Views:** `resources/views/dashboard/pages/support/`

#### Ticket Status Flow
```
open → in_progress → resolved → closed
  ↓
cancelled
```

#### Priority Levels
- **Low:** General inquiries, non-urgent
- **Medium:** Standard support requests
- **High:** Urgent issues affecting bookings
- **Critical:** System failures, payment issues

---

### 3.10 Notification System

#### Features
- Real-time in-app notifications
- Email notifications
- Notification preferences
- Mark as read functionality
- Notification history

#### Components
- **Model:** `Notification.php`
- **Controller:** `NotificationController.php`
- **Routes:** `routes/notifications.php`
- **Views:** Navbar dropdown (partial)

#### Notification Types
1. **Booking Notifications:**
   - New booking created
   - Customer account created (admin creates booking for new customer)
   - Artist assigned
   - Booking confirmed
   - Booking completed
   - Booking cancelled
   - Payment received

2. **Review Notifications:**
   - New review received
   - Review approved/rejected

3. **System Notifications:**
   - Subscription expiring
   - Payment overdue
   - Profile update required

#### Notification Channels
- **Database:** Stored in `notifications` table
- **Email:** Sent via Laravel Mail
- **Future:** Push notifications, SMS (extensible)

---

### 3.11 Activity Log System

#### Features
- Comprehensive audit trail
- Tracks all major actions
- User activity monitoring
- IP address logging
- Filterable by date, user, action type

#### Components
- **Model:** `ActivityLog.php`
- **Controller:** `ActivityLogController.php`
- **Routes:** `routes/activity_logs.php`
- **Views:** `resources/views/dashboard/pages/activity-logs/`

#### Logged Actions
- User login/logout
- Booking creation/updates
- Artist assignment
- Payment processing
- Review submission
- Company/user creation
- Settings changes
- Any CRUD operations

#### Activity Log Format
```php
ActivityLog::log(
    action: 'created',
    model: $booking,
    description: 'New booking created for customer John Doe',
    properties: ['booking_id' => $booking->id, 'amount' => 500]
);
```

---

### 3.12 Dashboard & Analytics Module

#### Master Admin Dashboard
**View:** `resources/views/dashboard/pages/index_enhanced.blade.php`

**Statistics Displayed:**
- Total bookings (all companies)
- Total companies
- Total artists
- Total revenue
- Pending bookings
- Completed bookings
- Active subscriptions
- Monthly revenue

**Charts:**
- Booking trend (line chart)
- Revenue by event type (doughnut chart)
- Monthly booking comparison
- Artist performance metrics

#### Company Admin Dashboard
**Statistics Displayed:**
- Company-specific bookings
- Company artists
- Company revenue
- Pending bookings
- Completed bookings
- Average rating
- Subscription status

**Charts:**
- Company booking trends
- Revenue breakdown
- Artist performance

#### Customer Dashboard
**Statistics Displayed:**
- Total bookings
- Upcoming events
- Past events
- Favorite artists

**Quick Actions:**
- Create new booking
- View booking history
- Submit pending reviews

#### Artist Dashboard
**Statistics Displayed:**
- Assigned bookings
- Completed events
- Average rating
- Total earnings
- Upcoming events

**Quick Actions:**
- View schedule
- Update availability
- View reviews

---

## 4. User Roles & Permissions

### Role Hierarchy

#### 1. Master Admin (Superuser)
**Role Key:** `master_admin`
**Access Level:** Full system access

**Permissions:**
- ✅ Manage all companies
- ✅ Manage all users across companies
- ✅ View/edit all bookings
- ✅ Manage subscription packages
- ✅ Configure system settings
- ✅ Access all analytics and reports
- ✅ Manage event types
- ✅ Moderate reviews and testimonials
- ✅ View activity logs (all users)
- ✅ Manage blog content
- ✅ Handle support tickets

**Dashboard Route:** `/dashboard`

#### 2. Company Admin
**Role Key:** `company_admin`
**Access Level:** Company-scoped access

**Permissions:**
- ✅ Manage company profile
- ✅ Manage company artists
- ✅ View/edit company bookings
- ✅ Assign artists to bookings
- ✅ Process payments (company bookings)
- ✅ Manage company users (artists, customers)
- ✅ View company analytics
- ✅ Manage company subscription
- ✅ View company activity logs
- ✅ Handle company support tickets
- ❌ Cannot access other companies' data
- ❌ Cannot modify system settings
- ❌ Cannot create/edit event types

**Dashboard Route:** `/admin/dashboard`

#### 3. Artist
**Role Key:** `artist`
**Access Level:** Assigned bookings only

**Permissions:**
- ✅ View assigned bookings
- ✅ Update artist profile
- ✅ Accept/reject booking assignments
- ✅ Mark bookings as completed
- ✅ View own earnings and statistics
- ✅ View own reviews
- ✅ Submit proposals for open bookings
- ❌ Cannot create bookings
- ❌ Cannot assign artists
- ❌ Cannot access other artists' bookings
- ❌ Cannot process payments
- ❌ Cannot view company-wide analytics

**Dashboard Route:** `/artist/dashboard`

#### 4. Customer
**Role Key:** `customer`
**Access Level:** Own bookings only

**Permissions:**
- ✅ Create booking requests
- ✅ View own bookings
- ✅ Edit pending bookings (before confirmation)
- ✅ Cancel bookings (with policy constraints)
- ✅ Make payments
- ✅ Submit reviews (after completion)
- ✅ View artist profiles
- ✅ Create support tickets
- ❌ Cannot assign artists
- ❌ Cannot mark bookings as completed
- ❌ Cannot view other customers' bookings
- ❌ Cannot access admin features

**Dashboard Route:** `/customer/dashboard`

### Role Assignment
**Database Table:** `roles`
```sql
id | role_name      | role_key
---+----------------+-------------
1  | Master Admin   | master_admin
2  | Company Admin  | company_admin
3  | Artist         | artist
4  | Customer       | customer
```

**User Role Assignment:**
- Field: `users.role_id`
- Relationship: `$user->role`
- Role Check: `Auth::user()->role->role_key`

### Middleware for Role Protection
```php
// routes/admin.php
Route::middleware(['auth', 'role:master_admin,company_admin'])->group(function() {
    // Admin routes
});

// routes/artist.php
Route::middleware(['auth', 'role:artist'])->group(function() {
    // Artist routes
});

// routes/customer.php
Route::middleware(['auth', 'role:customer'])->group(function() {
    // Customer routes
});
```

---

## 5. Database Design

### Core Tables

#### users
Primary user authentication and profile table
```sql
Columns:
- id: Primary key
- name: Full name
- email: Unique, indexed
- email_verified_at: Timestamp
- password: Hashed
- company_id: Foreign key → companies
- role_id: Foreign key → roles
- phone: Contact number
- country_id, state_id, city_id: Location
- profile_complete: Boolean flag
- remember_token: For "remember me"
- created_at, updated_at: Timestamps
- deleted_at: Soft delete

Indexes:
- email (unique)
- company_id
- role_id
```

#### companies
Entertainment company/agency information
```sql
Columns:
- id: Primary key
- name: Company name
- email: Contact email
- phone: Contact phone
- website: Company website URL
- kvk_number: Business registration number
- contact_name: Primary contact person
- contact_phone: Primary contact phone
- contact_email: Primary contact email
- address: Physical address
- logo: Logo file path
- status: active, inactive, suspended
- is_verified: Boolean
- verified_at: Timestamp
- last_login_at: Last login timestamp
- created_at, updated_at, deleted_at

Indexes:
- email
- status
- is_verified
```

#### artists
Artist/DJ profiles
```sql
Columns:
- id: Primary key
- company_id: Foreign key → companies
- user_id: Foreign key → users (one-to-one)
- stage_name: Professional name
- experience_years: Integer
- genres: JSON array
- specialization: Text
- rating: Decimal (average from reviews)
- image: Profile image path
- bio: Long text description
- created_at, updated_at, deleted_at

Indexes:
- company_id
- user_id (unique)
- rating
```

#### booking_requests
Main booking table
```sql
Columns:
- id: Primary key
- user_id: Foreign key → users (customer)
- company_id: Foreign key → companies
- assigned_artist_id: Foreign key → artists
- event_type_id: Foreign key → event_types
- status: ENUM(pending, confirmed, completed, cancelled)
- name, surname: Customer details
- date_of_birth: Date
- address, phone, email: Contact info
- event_date: Date of event
- start_time, end_time: Event duration
- opening_songs, special_moments: Text
- dos, donts: JSON (music preferences)
- playlist_spotify: Spotify link
- additional_notes, company_notes: Text
- wedding_date, wedding_time, wedding_location, partner_name: Wedding fields
- confirmed_at, completed_at, cancelled_at: Status timestamps
- cancellation_reason: Text
- created_at, updated_at, deleted_at

Indexes:
- user_id
- company_id
- assigned_artist_id
- event_type_id
- status
- event_date
```

#### payments
Payment transaction records
```sql
Columns:
- id: Primary key
- booking_requests_id: Foreign key → booking_requests (nullable)
- company_subscription_id: Foreign key → company_subscriptions (nullable)
- user_id: Foreign key → users
- amount: Decimal
- currency: VARCHAR(3) default 'EUR'
- transaction_id: Unique transaction identifier
- payment_method: ENUM(credit_card, bank_transfer, paypal, cash, other)
- attachment: File path (payment proof)
- type: ENUM(booking, subscription)
- status: ENUM(pending, processing, completed, failed, refunded)
- created_at, updated_at

Indexes:
- booking_requests_id
- company_subscription_id
- user_id
- status
- transaction_id
```

#### reviews
Customer reviews and ratings
```sql
Columns:
- id: Primary key
- booking_id: Foreign key → booking_requests
- user_id: Foreign key → users (reviewer)
- artist_id: Foreign key → artists
- company_id: Foreign key → companies
- rating: Integer (1-5)
- title: VARCHAR(255)
- comment: Text
- status: ENUM(pending, approved, rejected)
- featured: Boolean (for testimonials)
- created_at, updated_at

Indexes:
- booking_id
- user_id
- artist_id
- company_id
- rating
- status

Unique Constraint:
- (user_id, booking_id) - One review per booking per user
```

### Relationship Tables

#### artist_requests
Artist proposals for bookings
```sql
Columns:
- id: Primary key
- booking_requests_id: Foreign key
- artist_id: Foreign key
- proposed_price: Decimal
- message: Text
- status: ENUM(pending, accepted, rejected)
- created_at, updated_at

Indexes:
- booking_requests_id
- artist_id
- status
```

#### shared_artists
Artist sharing between companies
```sql
Columns:
- id: Primary key
- artist_id: Foreign key → artists
- shared_by_company_id: Foreign key → companies
- shared_with_company_id: Foreign key → companies
- status: ENUM(pending, accepted, rejected, revoked)
- notes: Text
- shared_at, accepted_at, revoked_at: Timestamps
- created_at, updated_at

Indexes:
- artist_id
- shared_by_company_id
- shared_with_company_id
- status
```

#### social_links
Company social media links
```sql
Columns:
- id: Primary key
- company_id: Foreign key
- platform: VARCHAR(50) (facebook, instagram, twitter, etc.)
- url: VARCHAR(500)
- created_at, updated_at

Indexes:
- company_id
```

### Subscription Tables

#### packages
Subscription plan definitions
```sql
Columns:
- id: Primary key
- name: Package name
- description: Text
- price: Decimal
- duration: Integer (days)
- features: JSON array
- max_artists: Integer
- max_bookings: Integer
- is_active: Boolean
- created_at, updated_at
```

#### company_subscriptions
Company subscription records
```sql
Columns:
- id: Primary key
- company_id: Foreign key
- package_id: Foreign key
- start_date, end_date: Date range
- status: ENUM(active, expired, cancelled)
- auto_renew: Boolean
- created_at, updated_at

Indexes:
- company_id
- package_id
- status
- end_date
```

### Additional Tables

#### event_types
```sql
- id, event_name, description, created_at, updated_at
```

#### categories (Blog)
```sql
- id, name, slug, description, created_at, updated_at
```

#### blogs
```sql
- id, title, slug, content, excerpt, featured_image, category_id, author_id, status, published_at, views, created_at, updated_at
```

#### support_tickets
```sql
- id, user_id, subject, description, priority, status, assigned_to, created_at, updated_at
```

#### notifications
```sql
- id, user_id, title, message, type, is_read, read_at, created_at, updated_at
```

#### activity_logs
```sql
- id, user_id, action, model_type, model_id, description, properties (JSON), ip_address, user_agent, created_at, updated_at
```

### Database Relationships Summary

```
users
  - belongsTo: role, company
  - hasOne: artist
  - hasMany: bookings, payments, reviews, notifications, support_tickets, activity_logs

companies
  - hasMany: users, artists, bookings, subscriptions, social_links
  - belongsToMany: shared_artists

artists
  - belongsTo: user, company
  - hasMany: assigned_bookings, reviews, artist_requests
  - belongsToMany: shared_with_companies

booking_requests
  - belongsTo: user, company, assigned_artist, event_type
  - hasMany: artist_requests, payments, reviews

payments
  - belongsTo: booking_request, subscription, user

reviews
  - belongsTo: booking, user, artist, company
```

---

## 6. API Endpoints

### Authentication Endpoints

```
POST   /register                    - User registration
POST   /login                       - User login
POST   /logout                      - User logout
POST   /password/email              - Send password reset link
POST   /password/reset              - Reset password
GET    /email/verify/{id}/{hash}    - Verify email address
POST   /email/verification-notification - Resend verification email
```

### Booking Endpoints

```
GET    /bookings                    - List bookings (role-filtered)
GET    /bookings/create             - Show booking creation form
POST   /bookings                    - Create new booking
GET    /bookings/{booking}          - Show booking details
GET    /bookings/{booking}/edit     - Show edit form
PUT    /bookings/{booking}          - Update booking
DELETE /bookings/{booking}          - Delete booking
POST   /bookings/{booking}/assign-artist    - Assign artist
POST   /bookings/{booking}/mark-completed   - Mark as completed
POST   /bookings/{booking}/cancel            - Cancel booking
```

### Artist Endpoints

```
GET    /artists                     - List all artists
GET    /artists/create              - Show artist creation form
POST   /artists                     - Create new artist
GET    /artists/{artist}            - Show artist details
GET    /artists/{artist}/edit       - Show edit form
PUT    /artists/{artist}            - Update artist
DELETE /artists/{artist}            - Delete artist
```

### Company Endpoints

```
GET    /companies                   - List companies (master admin)
GET    /companies/create            - Show company creation form
POST   /companies                   - Create company
GET    /companies/{company}         - Show company details (enhanced)
GET    /companies/{company}/edit    - Show edit form
PUT    /companies/{company}         - Update company
DELETE /companies/{company}         - Delete company
```

### Payment Endpoints

```
GET    /payments                    - List payments
GET    /payments/create             - Show payment form
POST   /payments                    - Record payment
GET    /payments/{payment}          - Show payment details
POST   /payments/{payment}/verify   - Verify payment
PUT    /payments/{payment}          - Update payment status
DELETE /payments/{payment}          - Delete payment
```

### Review Endpoints

```
GET    /reviews                     - List reviews
POST   /reviews                     - Submit review
GET    /reviews/{review}            - Show review
PUT    /reviews/{review}            - Update review (admin)
DELETE /reviews/{review}            - Delete review
PUT    /reviews/{review}/status     - Approve/reject review
```

### User Management Endpoints

```
GET    /users                       - List users
GET    /users/create                - Show user creation form
POST   /users                       - Create user
GET    /users/{user}                - Show user details
GET    /users/{user}/edit           - Show edit form
PUT    /users/{user}                - Update user
DELETE /users/{user}                - Delete user
```

### Subscription Endpoints

```
GET    /subscriptions               - List subscriptions
GET    /subscriptions/create        - Show subscription form
POST   /subscriptions               - Create subscription
GET    /subscriptions/{subscription} - Show subscription details
GET    /subscriptions/{subscription}/edit - Edit subscription
PUT    /subscriptions/{subscription}     - Update subscription
DELETE /subscriptions/{subscription}     - Cancel subscription
```

### Dashboard Endpoints

```
GET    /dashboard                   - Main dashboard (role-based)
GET    /admin/dashboard             - Company admin dashboard
GET    /artist/dashboard            - Artist portal dashboard
GET    /customer/dashboard          - Customer portal dashboard
```

### Support Ticket Endpoints

```
GET    /support/tickets             - List tickets
GET    /support/tickets/create      - Create ticket form
POST   /support/tickets             - Submit ticket
GET    /support/tickets/{ticket}    - Show ticket
POST   /support/tickets/{ticket}/reply - Add reply
PUT    /support/tickets/{ticket}    - Update status
```

### Blog Endpoints

```
GET    /blogs                       - List public blogs
GET    /blogs/{slug}                - Show blog post
GET    /admin/blogs                 - Admin blog list
GET    /admin/blogs/create          - Create blog form
POST   /admin/blogs                 - Save blog
GET    /admin/blogs/{blog}/edit     - Edit blog
PUT    /admin/blogs/{blog}          - Update blog
DELETE /admin/blogs/{blog}          - Delete blog
```

### Notification Endpoints

```
GET    /notifications               - List user notifications
PUT    /notifications/{notification}/read - Mark as read
PUT    /notifications/read-all      - Mark all as read
DELETE /notifications/{notification} - Delete notification
```

### Activity Log Endpoints

```
GET    /activity-logs               - View activity logs (admin)
GET    /activity-logs?user_id={id}  - Filter by user
GET    /activity-logs?action={type} - Filter by action
```

---

## 7. Frontend Structure

### Layout System

#### Main Layouts

**1. Guest Layout**
- **File:** `resources/views/layouts/guest.blade.php`
- **Used for:** Login, register, landing pages
- **Components:** Header, footer, auth forms

**2. Dashboard Layout**
- **File:** `resources/views/layouts/app.blade.php` or `dashboard.blade.php`
- **Used for:** All authenticated pages
- **Components:** Sidebar, navbar, content area, footer

### Sidebar Navigation

**Configuration:** `config/sidebar.php`

**Structure:**
```php
[
    'title' => 'Menu Item',
    'icon'  => 'tabler-icon-name',
    'route' => 'route.name',
    'roles' => ['master_admin', 'company_admin'], // Optional
    'submenu' => [ /* Nested items */ ]
]
```

**Rendering Logic:**
- Checks user role against `roles` array
- Displays only permitted menu items
- Active state based on current route

### View Components

#### Reusable Blade Components

**1. Stat Cards**
```blade
@component('components.stat-card', [
    'title' => 'Total Bookings',
    'value' => $totalBookings,
    'icon' => 'calendar',
    'color' => 'primary'
])
@endcomponent
```

**2. Data Tables**
- Consistent styling across all listing pages
- Pagination support
- Action buttons (View, Edit, Delete)

**3. Forms**
- Consistent form styling
- Validation error display
- CSRF token inclusion

**4. Modals**
- Confirmation dialogs
- Quick action modals
- Form modals

### Asset Management

**Vite Configuration:** `vite.config.js`

**CSS Files:**
- `resources/css/app.css` - Main stylesheet
- `public/css/` - Additional stylesheets
- Bootstrap 5 via CDN or npm

**JavaScript Files:**
- `resources/js/app.js` - Main JS entry point
- `public/js/` - Additional scripts
- Chart.js for analytics
- Alpine.js for interactivity

**Icons:**
- Tabler Icons (`ti ti-icon-name`)
- Consistent icon usage across the app

### Responsive Design

**Breakpoints (Bootstrap 5):**
- xs: <576px (mobile)
- sm: ≥576px (large mobile)
- md: ≥768px (tablet)
- lg: ≥992px (desktop)
- xl: ≥1200px (large desktop)
- xxl: ≥1400px (extra large desktop)

**Mobile Optimization:**
- Collapsible sidebar
- Touch-friendly buttons
- Responsive tables with horizontal scroll
- Mobile-friendly forms

---

## 8. Business Logic

### Booking Business Rules

1. **Event Date Validation:**
   - Must be in the future
   - Cannot overlap with artist's existing bookings

2. **Artist Assignment Rules:**
   - Artist must belong to the booking's company
   - Or artist must be shared with the company
   - Artist must be available on the event date

3. **Cancellation Policy:**
   - More than 14 days: 100% refund
   - 7-14 days: 50% refund
   - Less than 7 days: No refund

4. **Review Eligibility:**
   - Booking must be completed
   - User hasn't already reviewed the booking
   - Review within 30 days of completion (optional)

5. **Payment Verification:**
   - Booking must exist and be confirmed
   - Amount must match booking price
   - Transaction ID must be unique

### Company Business Rules

1. **Subscription Limits:**
   - Max artists based on package tier
   - Max bookings per month based on package
   - Feature access based on subscription level

2. **Company Verification:**
   - Email verification required
   - Document upload for business registration
   - Admin approval process

3. **Artist Sharing:**
   - Both companies must have active subscriptions
   - Artist must consent to sharing (future feature)
   - Sharing can be revoked at any time

### Payment Business Rules

1. **Booking Payments:**
   - Cannot process payment for cancelled bookings
   - Refunds only for completed payments
   - Payment amount must match booking total

2. **Subscription Payments:**
   - Auto-renewal based on company preference
   - Grace period: 7 days after expiration
   - Subscription suspended after grace period

### Email Notification Rules

1. **Booking Created:**
   - Send to: Customer, Company Admin
   - If artist pre-assigned: Also send to artist

2. **Artist Assigned:**
   - Send to: Customer, Artist, Company Admin

3. **Booking Completed:**
   - Send to: Customer (with review request), Artist, Company Admin

4. **Payment Received:**
   - Send to: Customer (receipt), Company Admin

5. **Subscription Expiring:**
   - Send at: 7 days before, 3 days before, on expiration day
   - Send to: Company Admin, Company Owner

---

## 9. Security & Authentication

### Authentication System

**Provider:** Laravel Breeze/Sanctum
**Session Driver:** Database sessions
**Password Hashing:** Bcrypt (default Laravel)

**Authentication Routes:**
- Login: `POST /login`
- Logout: `POST /logout`
- Register: `POST /register`
- Password Reset: `POST /password/email` and `POST /password/reset`

### Email Verification

**Feature:** Required for new user registration
**Process:**
1. User registers
2. Verification email sent
3. User clicks verification link
4. Email verified, full access granted

**Middleware:** `verified`

### Role-Based Access Control (RBAC)

**Implementation:** Custom middleware + role checks

**Role Middleware:**
```php
// app/Http/Middleware/CheckRole.php
if (!in_array($user->role->role_key, $roles)) {
    abort(403, 'Unauthorized');
}
```

**Usage in Routes:**
```php
Route::middleware(['auth', 'role:master_admin'])->group(function() {
    // Master admin routes
});
```

**Blade Directives:**
```blade
@role('master_admin')
    <button>Admin Action</button>
@endrole

@can('edit-booking', $booking)
    <a href="{{ route('bookings.edit', $booking) }}">Edit</a>
@endcan
```

### Authorization Policies

**Location:** `app/Policies/`

**Example: BookingPolicy**
```php
public function view(User $user, BookingRequest $booking)
{
    return match ($user->role->role_key) {
        'master_admin' => true,
        'company_admin' => $booking->company_id === $user->company_id,
        'artist' => $booking->assigned_artist_id === optional($user->artist)->id,
        'customer' => $booking->user_id === $user->id,
        default => false,
    };
}
```

**Registration:** `app/Providers/AuthServiceProvider.php`

### CSRF Protection

**Enabled:** All POST, PUT, DELETE requests
**Token:** `@csrf` directive in forms
**Middleware:** `VerifyCsrfToken` (default)

### SQL Injection Prevention

**Method:** Eloquent ORM with parameter binding
**Safe:**
```php
User::where('email', $request->email)->first();
BookingRequest::where('status', 'pending')->get();
```

**Unsafe (Avoid):**
```php
DB::select("SELECT * FROM users WHERE email = '$email'"); // Vulnerable!
```

### XSS Protection

**Blade Escaping:** `{{ $variable }}` automatically escapes HTML
**Unescaped (use cautiously):** `{!! $html !!}`

**Best Practices:**
- Always use `{{ }}` for user input
- Sanitize HTML content before storing
- Use `strip_tags()` for text-only fields

### File Upload Security

**Validation Rules:**
```php
'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
'attachment' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120',
```

**Storage:**
- Files stored in `storage/app/public/`
- Symlink: `php artisan storage:link`
- Never store in `public/` root directly

**Access Control:**
- Check user permissions before serving files
- Use controller methods for file downloads

### Rate Limiting

**Login Attempts:** 5 attempts per minute
**API Requests:** 60 requests per minute (configurable)

**Configuration:** `app/Http/Kernel.php`
```php
'throttle:60,1' // 60 requests per 1 minute
```

### Environment Variables

**File:** `.env`
**Never commit:** `.env` file to version control
**Production:** Use secure environment variable management

**Critical Variables:**
- `APP_KEY` - Application encryption key
- `DB_PASSWORD` - Database password
- `MAIL_PASSWORD` - Email service password
- `AWS_SECRET_ACCESS_KEY` - Cloud storage keys

---

## 10. Deployment & Configuration

### Server Requirements

**Minimum:**
- PHP 8.2 or higher
- MySQL 8.0 or higher
- Nginx or Apache with URL rewrite
- Composer 2.0+
- Node.js 18+ and npm (for asset building)

**Recommended:**
- PHP 8.3
- MySQL 8.0
- Redis for caching and queues
- Supervisor for queue workers
- SSL certificate (Let's Encrypt)

### Installation Steps

**1. Clone Repository**
```bash
git clone https://github.com/yourusername/stagedeskprofresh.git
cd stagedeskprofresh
```

**2. Install Dependencies**
```bash
composer install
npm install
```

**3. Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Database Setup**
```bash
# Edit .env with database credentials
php artisan migrate --seed
```

**5. Storage & Permissions**
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**6. Build Assets**
```bash
npm run build
```

**7. Start Services**
```bash
# Development
php artisan serve
php artisan queue:work

# Production
# Configure Nginx/Apache virtual host
sudo systemctl start nginx
sudo supervisorctl start stagedeskpro-worker
```

### Environment Configuration

**Key `.env` Variables:**
```env
APP_NAME="StageDesk Pro"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stagedeskpro
DB_USERNAME=dbuser
DB_PASSWORD=securepassword

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Queue Configuration

**Supervisor Config:** `/etc/supervisor/conf.d/stagedeskpro-worker.conf`
```ini
[program:stagedeskpro-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/stagedeskprofresh/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/stagedeskprofresh/storage/logs/worker.log
stopwaitsecs=3600
```

**Start Supervisor:**
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start stagedeskpro-worker:*
```

### Cron Jobs

**Laravel Scheduler:** `crontab -e`
```cron
* * * * * cd /path/to/stagedeskprofresh && php artisan schedule:run >> /dev/null 2>&1
```

**Scheduled Tasks (in `app/Console/Kernel.php`):**
```php
protected function schedule(Schedule $schedule)
{
    // Send subscription expiration reminders
    $schedule->command('subscriptions:check-expiration')->daily();
    
    // Clean old notifications
    $schedule->command('notifications:cleanup')->weekly();
    
    // Generate analytics reports
    $schedule->command('analytics:generate')->dailyAt('00:30');
    
    // Database backup
    $schedule->command('backup:run')->daily();
}
```

### Nginx Configuration

**Virtual Host:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /path/to/stagedeskprofresh/public;

    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Performance Optimization

**1. Caching**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**2. Opcache (php.ini)**
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

**3. Redis Cache**
```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**4. Asset Optimization**
```bash
npm run build
# Minifies and optimizes CSS/JS
```

### Backup Strategy

**Database Backups:**
```bash
# Daily automated backup
mysqldump -u root -p stagedeskpro > backup_$(date +%Y%m%d).sql

# Rotate backups (keep last 30 days)
find /path/to/backups -name "backup_*.sql" -mtime +30 -delete
```

**File Backups:**
```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/

# Backup uploaded files
rsync -av storage/app/public/ /backup/uploads/
```

**Laravel Backup Package:**
```bash
composer require spatie/laravel-backup
php artisan backup:run
```

### Monitoring & Logging

**Log Files:**
- Application Logs: `storage/logs/laravel.log`
- Web Server: `/var/log/nginx/error.log`
- Queue Workers: `storage/logs/worker.log`

**Log Rotation:**
```bash
# /etc/logrotate.d/stagedeskpro
/path/to/stagedeskprofresh/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

**Monitoring Tools:**
- Laravel Telescope (development)
- New Relic (production)
- Sentry for error tracking
- Uptime monitoring (Pingdom, UptimeRobot)

### Security Checklist

- [x] Force HTTPS in production
- [x] Set `APP_DEBUG=false` in production
- [x] Configure firewall (UFW/iptables)
- [x] Disable directory listing
- [x] Secure `.env` file permissions (600)
- [x] Regular security updates (composer update)
- [x] Configure fail2ban for brute force protection
- [x] Implement rate limiting
- [x] Regular database backups
- [x] SSL certificate renewal automation
- [x] Monitor logs for suspicious activity

---

## Additional Documentation Files

For more detailed information on specific topics, refer to:

1. **[BOOKING_FLOW_DOCUMENTATION.md](BOOKING_FLOW_DOCUMENTATION.md)** - Complete booking system workflow
2. **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Detailed API endpoint documentation
3. **[DATABASE_SCHEMA.md](DATABASE_SCHEMA.md)** - Complete database structure
4. **[INSTALLATION_GUIDE.md](INSTALLATION_GUIDE.md)** - Step-by-step installation
5. **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** - Production deployment guide
6. **[EMAIL_SYSTEM_DOCUMENTATION.md](EMAIL_SYSTEM_DOCUMENTATION.md)** - Email templates and notification system

---

## Maintenance & Support

**Bug Reports:** Open issues on GitHub repository
**Feature Requests:** Submit via GitHub issues
**Security Issues:** Email security@stagedeskpro.local
**Documentation Updates:** Submit pull requests

**Support Channels:**
- Documentation: This wiki
- Email: support@stagedeskpro.local
- Community Forum: TBD

---

**Document Version:** 3.0  
**Last Updated:** January 25, 2026  
**Maintained By:** StageDesk Pro Development Team  
**License:** MIT

---

*This documentation is a living document and will be updated as the system evolves. Contributions and improvements are welcome.*
