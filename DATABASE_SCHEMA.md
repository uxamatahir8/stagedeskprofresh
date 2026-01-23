# StageDesk Pro - Database Schema Reference

## Core Tables

### users
- id (PK)
- name
- email (UNIQUE)
- password
- company_id (FK - companies)
- role_id (FK - roles)
- phone
- country_id (FK - countries)
- state_id (FK - states)
- city_id (FK - cities)
- profile_complete (boolean)
- email_verified_at
- created_at, updated_at

### roles
- id (PK)
- role_name
- role_key (UNIQUE)
- created_at, updated_at

**Role Keys:** master_admin, company_admin, customer, artist

### companies
- id (PK)
- company_name
- owner_id (FK - users)
- email
- phone
- address
- website
- industry
- tax_id
- company_logo
- created_at, updated_at

---

## Booking & Services

### event_types
- id (PK)
- event_name
- description
- created_at, updated_at

### booking_requests
- id (PK)
- user_id (FK - users)
- event_type_id (FK - event_types)
- name
- surname
- email
- phone
- event_date
- event_duration
- location
- des (description)
- dos (JSON - requirements)
- donts (JSON - restrictions)
- wedding_date (nullable)
- wedding_venue (nullable)
- created_at, updated_at, deleted_at (SoftDelete)

### booked_services
- id (PK)
- booking_requests_id (FK - booking_requests)
- service_description
- price
- created_at, updated_at

### artist_requests
- id (PK)
- booking_requests_id (FK - booking_requests)
- artist_id (FK - artists)
- proposed_price
- message
- status (pending, accepted, rejected)
- created_at, updated_at

---

## Artist Management

### artists
- id (PK)
- company_id (FK - companies)
- user_id (FK - users)
- stage_name
- experience_years
- genres (JSON)
- specialization
- rating (float)
- image
- bio
- created_at, updated_at, deleted_at (SoftDelete)

### artist_services
- id (PK)
- artist_id (FK - artists)
- service_name
- description (nullable)
- price
- duration
- duration_unit (minutes, hours, days)
- created_at, updated_at

---

## Payment & Subscription

### payments
- id (PK)
- user_id (FK - users)
- booking_requests_id (FK - booking_requests, nullable)
- company_subscription_id (FK - company_subscriptions, nullable)
- amount
- currency
- payment_method (credit_card, debit_card, bank_transfer, paypal, stripe)
- transaction_id (UNIQUE, nullable)
- type (booking, subscription)
- status (pending, completed, rejected)
- attachment (nullable)
- created_at, updated_at

### packages
- id (PK)
- package_name
- description
- price
- features (JSON)
- duration_type (weekly, monthly, yearly)
- created_at, updated_at

### company_subscriptions
- id (PK)
- company_id (FK - companies)
- package_id (FK - packages)
- start_date
- end_date
- auto_renew (boolean, default: false)
- status (active, pending, expired, canceled)
- created_at, updated_at

---

## Notifications & Communication

### notifications
- id (PK)
- user_id (FK - users)
- title
- message
- type (booking, payment, subscription, system)
- link (nullable)
- is_read (boolean)
- created_at, updated_at

### support_tickets
- id (PK)
- user_id (FK - users)
- subject
- message
- status (open, closed, resolved)
- created_at, updated_at, deleted_at

### testimonials
- id (PK)
- user_id (FK - users)
- artist_id (FK - artists, nullable)
- rating (1-5)
- review
- created_at, updated_at, deleted_at (SoftDelete)

---

## Affiliate & Commission

### affiliates
- id (PK)
- user_id (FK - users)
- referral_code
- commission_rate
- total_earnings
- created_at, updated_at

### affiliate_comissions
- id (PK)
- affiliate_id (FK - affiliates)
- company_id (FK - companies)
- company_subscription_id (FK - company_subscriptions)
- amount
- status (pending, paid)
- created_at, updated_at

---

## Blog & Comments

### blogs
- id (PK)
- title
- slug
- description
- image
- user_id (FK - users)
- blog_category_id (FK - blog_categories)
- created_at, updated_at, deleted_at

### blog_categories
- id (PK)
- category_name
- slug
- created_at, updated_at

### comments
- id (PK)
- user_id (FK - users)
- blog_id (FK - blogs)
- comment_text
- created_at, updated_at

---

## Geographic Data

### countries
- id (PK)
- name
- code
- created_at, updated_at

### states
- id (PK)
- name
- country_id (FK - countries)
- created_at, updated_at

### cities
- id (PK)
- name
- state_id (FK - states)
- created_at, updated_at

---

## Settings & Configuration

### settings
- id (PK)
- settings_key (UNIQUE)
- settings_value (text/JSON)
- created_at, updated_at

### social_links
- id (PK)
- user_id (FK - users)
- platform
- url
- created_at, updated_at

---

## Key Relationships

```
User
├── Company (belongsTo)
├── Role (belongsTo)
├── BookingRequests (hasMany)
├── Payments (hasMany)
├── Notifications (hasMany)
├── Artist (hasOne) - if user is an artist
└── SocialLinks (hasMany)

Company
├── Users (hasMany)
├── Subscriptions (hasMany)
├── Artists (hasMany)
└── AffiliateCommissions (hasMany)

Artist
├── Company (belongsTo)
├── User (belongsTo)
├── Services (hasMany)
└── ArtistRequests (hasMany)

ArtistServices
└── Artist (belongsTo)

BookingRequest
├── User (belongsTo)
├── EventType (belongsTo)
├── BookedServices (hasMany)
├── ArtistRequests (hasMany)
└── Payments (hasMany)

ArtistRequest
├── BookingRequest (belongsTo)
└── Artist (belongsTo)

Payment
├── User (belongsTo)
├── BookingRequest (belongsTo)
└── CompanySubscription (belongsTo)

CompanySubscription
├── Company (belongsTo)
└── Package (belongsTo)

Notification
└── User (belongsTo)

Blog
├── User (belongsTo)
├── BlogCategory (belongsTo)
└── Comments (hasMany)

Affiliate
└── User (belongsTo)
```

---

## Indexing Strategy

### Recommended Indexes
```sql
-- Performance critical
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_company_id ON users(company_id);
CREATE INDEX idx_users_role_id ON users(role_id);

CREATE INDEX idx_booking_requests_user_id ON booking_requests(user_id);
CREATE INDEX idx_booking_requests_event_type_id ON booking_requests(event_type_id);
CREATE INDEX idx_booking_requests_created_at ON booking_requests(created_at);

CREATE INDEX idx_payments_user_id ON payments(user_id);
CREATE INDEX idx_payments_booking_request_id ON payments(booking_requests_id);
CREATE INDEX idx_payments_status ON payments(status);

CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);

CREATE INDEX idx_company_subscriptions_company_id ON company_subscriptions(company_id);
CREATE INDEX idx_company_subscriptions_status ON company_subscriptions(status);

CREATE INDEX idx_artists_company_id ON artists(company_id);
CREATE INDEX idx_artists_user_id ON artists(user_id);

CREATE INDEX idx_artist_services_artist_id ON artist_services(artist_id);

CREATE INDEX idx_artist_requests_booking_request_id ON artist_requests(booking_requests_id);
CREATE INDEX idx_artist_requests_artist_id ON artist_requests(artist_id);
```

---

## Data Types & Constraints

### Common Enums
```
payment_methods: credit_card, debit_card, bank_transfer, paypal, stripe
payment_status: pending, completed, rejected
subscription_status: active, pending, expired, canceled
booking_status: pending, confirmed, completed, cancelled
duration_units: minutes, hours, days
notification_types: booking, payment, subscription, system
ticket_status: open, closed, resolved
```

### Common JSON Fields
```
artists.genres: ["Jazz", "Classical", "Pop", ...]
booking_requests.dos: ["requirement1", "requirement2", ...]
booking_requests.donts: ["restriction1", "restriction2", ...]
packages.features: ["feature1", "feature2", ...]
```

---

## Soft Delete Implementation

Tables using SoftDeletes (include deleted_at column):
- booking_requests
- artists
- blogs
- testimonials

Benefits:
- Data preservation and recovery
- Historical tracking
- Compliance with data retention policies

---

## View/Query Optimization

### Common Queries

**Get user with all relationships:**
```php
$user = User::with(['role', 'company', 'bookingRequests', 'payments', 'notifications'])->find($id);
```

**Get artist with services:**
```php
$artist = Artist::with(['company', 'user', 'services', 'artistRequests'])->find($id);
```

**Get booking with related data:**
```php
$booking = BookingRequest::with(['user', 'eventType', 'bookedServices', 'artistRequests', 'payments'])->find($id);
```

**Get company with subscriptions:**
```php
$company = Company::with(['subscriptions.package', 'artists', 'users'])->find($id);
```

---

## Backup & Recovery

### Important Considerations
- Run regular backups (daily recommended)
- Test backup recovery process weekly
- Archive old data using soft deletes
- Monitor database size growth
- Optimize queries with indexes

---

## Scaling Considerations

### Future Optimizations
- Redis caching for frequently accessed data
- Database partitioning for large tables
- Read replicas for reporting queries
- Archive old payment records
- Implement database views for complex queries

---

**Last Updated:** November 2024
**Database Engine:** MySQL 5.7+
**Charset:** utf8mb4
**Collation:** utf8mb4_unicode_ci
