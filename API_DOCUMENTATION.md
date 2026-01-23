# StageDesk Pro - API & Routes Reference

## Overview
StageDesk Pro uses Laravel's resource routing for RESTful API endpoints. All routes are protected with authentication and authorization middleware.

---

## Authentication Routes

### Public Routes (No Auth Required)
```
GET  /                                    # Home page
GET  /login                               # Login form
POST /login                               # Process login
GET  /register                            # Registration form
POST /register                            # Process registration
GET  /forgot-password                     # Forgot password form
POST /forgot-password                     # Process forgot password
GET  /reset-password                      # Reset password form
POST /reset-password                      # Process reset password
GET  /blogs                               # View all blogs
GET  /blog-details/{slug}                 # View blog post
GET  /blogs/{categorySlug?}               # Filter blogs by category
POST /blogs/{slug}/comment                # Post comment on blog (Auth Required)
```

---

## Dashboard Routes

### Protected Routes (Auth Required)
```
GET  /dashboard                           # Main dashboard

GET  /logout                              # User logout
```

---

## Artist Routes

### Base: `/artists`
**Middleware:** `auth, role:master_admin,company_admin`

| Method | Route | Controller | Action | Description |
|--------|-------|-----------|--------|-------------|
| GET | `/artists` | ArtistController | index | List all artists |
| GET | `/artists/create` | ArtistController | create | Show create form |
| POST | `/artists` | ArtistController | store | Create new artist |
| GET | `/artists/{artist}` | ArtistController | show | Show artist details |
| GET | `/artists/{artist}/edit` | ArtistController | edit | Show edit form |
| PUT | `/artists/{artist}` | ArtistController | update | Update artist |
| DELETE | `/artists/{artist}` | ArtistController | destroy | Delete artist |

---

## Artist Services Routes

### Base: `/artist-services`
**Middleware:** `auth, role:master_admin,company_admin,artist`

| Method | Route | Controller | Action | Description |
|--------|-------|-----------|--------|-------------|
| GET | `/artist-services` | ArtistServicesController | index | List services |
| GET | `/artist-services/create` | ArtistServicesController | create | Show create form |
| POST | `/artist-services` | ArtistServicesController | store | Create service |
| GET | `/artist-services/{service}` | ArtistServicesController | show | Show service details |
| GET | `/artist-services/{service}/edit` | ArtistServicesController | edit | Show edit form |
| PUT | `/artist-services/{service}` | ArtistServicesController | update | Update service |
| DELETE | `/artist-services/{service}` | ArtistServicesController | destroy | Delete service |

---

## Payment Routes

### Base: `/payments`
**Middleware:** `auth`

| Method | Route | Controller | Action | Description |
|--------|-------|-----------|--------|-------------|
| GET | `/payments` | PaymentController | index | List payments |
| GET | `/payments/create` | PaymentController | create | Show create form |
| POST | `/payments` | PaymentController | store | Create payment |
| GET | `/payments/{payment}` | PaymentController | show | Show payment details |
| GET | `/payments/{payment}/edit` | PaymentController | edit | Show edit form |
| PUT | `/payments/{payment}` | PaymentController | update | Update payment |
| DELETE | `/payments/{payment}` | PaymentController | destroy | Delete payment |
| POST | `/payments/{payment}/verify` | PaymentController | verifyPayment | Verify payment (Admin only) |

---

## Subscription Routes

### Base: `/subscriptions`
**Middleware:** `auth, role:master_admin`

| Method | Route | Controller | Action | Description |
|--------|-------|-----------|--------|-------------|
| GET | `/subscriptions` | CompanySubscriptionController | index | List subscriptions |
| GET | `/subscriptions/create` | CompanySubscriptionController | create | Show create form |
| POST | `/subscriptions` | CompanySubscriptionController | store | Create subscription |
| GET | `/subscriptions/{subscription}` | CompanySubscriptionController | show | Show subscription details |
| GET | `/subscriptions/{subscription}/edit` | CompanySubscriptionController | edit | Show edit form |
| PUT | `/subscriptions/{subscription}` | CompanySubscriptionController | update | Update subscription |
| DELETE | `/subscriptions/{subscription}` | CompanySubscriptionController | destroy | Cancel subscription |

---

## Notification Routes

### Base: `/notifications`
**Middleware:** `auth`

| Method | Route | Controller | Action | Description |
|--------|-------|-----------|--------|-------------|
| GET | `/notifications` | NotificationController | index | List notifications |
| POST | `/notifications/{notification}/read` | NotificationController | read | Mark as read |
| POST | `/notifications/mark-all-read` | NotificationController | markAllRead | Mark all as read |
| DELETE | `/notifications/{notification}` | NotificationController | destroy | Delete notification |
| DELETE | `/notifications/destroy-all` | NotificationController | destroyAll | Delete all notifications |
| GET | `/notifications/unread` | NotificationController | getUnread | Get unread (JSON) |

---

## Booking Routes

### Defined in: `routes/bookings.php`
**Middleware:** `auth`

| Method | Route | Controller | Action | Description |
|--------|-------|-----------|--------|-------------|
| GET | `/bookings` | BookingController | index | List bookings |
| GET | `/bookings/create` | BookingController | create | Show create form |
| POST | `/bookings` | BookingController | store | Create booking |
| GET | `/bookings/{booking}` | BookingController | show | Show booking details |
| GET | `/bookings/{booking}/edit` | BookingController | edit | Show edit form |
| PUT | `/bookings/{booking}` | BookingController | update | Update booking |
| DELETE | `/bookings/{booking}` | BookingController | destroy | Delete booking |

---

## Helper Routes

### Utility Routes (Not full CRUD)
```
GET  /states/{country_id}                 # Get states by country (AJAX)
GET  /cities/{state_id}                   # Get cities by state (AJAX)
GET  /check-email-unique                  # Check if email exists (AJAX)
```

---

## Controller Request/Response Format

### Create/Update Form Requests

#### Artist Form
```php
{
    "company_id": 1,           // Required
    "user_id": 5,              // Required
    "stage_name": "DJ Max",    // Required, max 255
    "experience_years": 5,     // Required, numeric
    "genres": ["Jazz", "Pop"], // JSON array
    "specialization": "Weddings",
    "bio": "Professional DJ",  // Nullable
    "image": <file>            // Nullable, max 2MB
}
```

#### Service Form
```php
{
    "artist_id": 1,                    // Required
    "service_name": "DJ Service",      // Required
    "description": "4-hour DJ service",
    "price": 250.00,                   // Required, decimal
    "duration": 4,                     // Required, numeric
    "duration_unit": "hours"           // Required: minutes, hours, days
}
```

#### Payment Form
```php
{
    "type": "booking",                           // Required: booking, subscription
    "booking_requests_id": 1,                    // Optional
    "company_subscription_id": null,             // Optional
    "amount": 500.00,                            // Required, decimal
    "currency": "USD",                           // Required: USD, EUR, GBP
    "payment_method": "credit_card",             // Required
    "transaction_id": "TXN123456",               // Optional
    "attachment": <file>                         // Optional, PDF/Image max 2MB
}
```

#### Subscription Form
```php
{
    "company_id": 1,           // Required
    "package_id": 2,           // Required
    "auto_renew": true         // Boolean
}
```

---

## Response Formats

### Success Response (JSON)
```json
{
    "status": "success",
    "message": "Operation completed successfully",
    "data": {
        // Resource data
    }
}
```

### Error Response (JSON)
```json
{
    "status": "error",
    "message": "Error message",
    "errors": {
        "field_name": ["Error message for field"]
    }
}
```

### List Response
```json
{
    "current_page": 1,
    "data": [
        { /* item */ },
        { /* item */ }
    ],
    "from": 1,
    "last_page": 5,
    "per_page": 15,
    "to": 15,
    "total": 75
}
```

---

## Status Codes

| Code | Meaning | Use Case |
|------|---------|----------|
| 200 | OK | Successful GET, PUT |
| 201 | Created | Successful POST (resource created) |
| 204 | No Content | Successful DELETE |
| 302 | Found | Redirect (POST success redirects) |
| 400 | Bad Request | Invalid input data |
| 401 | Unauthorized | Not authenticated |
| 403 | Forbidden | Authenticated but no permission |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation errors |
| 500 | Internal Error | Server error |

---

## Authorization Rules

### Role-Based Access

#### Master Admin
- Access: ALL routes and resources
- Can: Manage system, view all data, verify payments, create subscriptions

#### Company Admin
- Access: Company-specific routes
- Can: View own company data, manage artists, manage bookings (company level)
- Cannot: View other companies' data

#### Customer
- Access: Own data only
- Can: Create bookings, make payments, view own notifications
- Cannot: Manage artists, view other users' data

#### Artist
- Access: Own profile and services
- Can: Manage own profile, services, view booking requests
- Cannot: Access admin functions, manage other artists

---

## Common Use Cases

### User Registration & Login
1. `POST /register` - User submits registration
2. Application creates user with 'customer' role
3. User is logged in automatically
4. Redirected to `/dashboard`

### Create Booking
1. `POST /bookings` - Customer submits booking form
2. System validates data
3. Booking created and assigned to user
4. Customer receives confirmation

### Artist Setup
1. Admin creates user with 'artist' role
2. `POST /artists` - Create artist profile
3. `POST /artist-services` - Add services
4. Artist can now receive booking requests

### Payment Processing
1. Customer initiates booking
2. `POST /payments` - Customer records payment
3. Payment status set to 'pending'
4. Admin reviews and `POST /payments/{id}/verify`
5. Payment status updated to 'completed'

### Notification Flow
1. System event occurs (booking created, payment received)
2. Notification record created in database
3. Topbar shows unread count
4. User can `POST /notifications/{id}/read` to mark as read
5. User views all in `/notifications` page

---

## Error Handling

### Validation Errors
```php
// Returns 422 with errors array
{
    "message": "The given data was invalid",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

### Authorization Errors
```php
// Returns 403
{
    "message": "This action is unauthorized"
}
```

### Not Found Errors
```php
// Returns 404
{
    "message": "Not found"
}
```

---

## Rate Limiting

### Current Limits
- No specific rate limiting configured
- Recommended for production:
  - Authentication attempts: 5 per minute
  - API calls: 100 per minute
  - File uploads: 10 per minute

### Implementation
```php
// In routes/web.php
Route::middleware('throttle:60,1')->group(function () {
    // Routes
});
```

---

## CORS Configuration

### Current Setup
- Local development: All origins allowed
- Production: Configure in `.env`

```php
// config/cors.php
'allowed_origins' => ['https://yourdomain.com'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

---

## Caching Headers

### Implemented Headers
```
Cache-Control: private, max-age=3600
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
```

---

## WebHooks (Future Implementation)

### Planned Events
- booking.created
- booking.updated
- payment.completed
- payment.failed
- subscription.renewed
- artist.created

---

## API Versioning

### Current Version: 1.0

### Future Versions
- API v2: Support for mobile applications
- Consider: `/api/v1/` prefix for versioned endpoints

---

## Testing API Endpoints

### Using cURL
```bash
# GET request
curl http://localhost:8000/artists

# POST request with authentication
curl -X POST http://localhost:8000/artists \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{"name":"value"}'
```

### Using Postman
1. Import collection from `postman_collection.json`
2. Set variables:
   - base_url: http://localhost:8000
   - auth_token: (get from login response)
3. Execute requests

---

## API Documentation Tools

### Swagger/OpenAPI (Recommended)
```bash
# Install L5-Swagger
composer require darkaonline/l5-swagger

# Generate documentation
php artisan l5-swagger:generate
```

### Access Documentation
```
http://localhost:8000/api/documentation
```

---

## Troubleshooting API Issues

### 401 Unauthorized
- Check authentication token
- Verify user is logged in
- Check session expiration

### 403 Forbidden
- Verify user role/permissions
- Check policy authorization
- Review role middleware

### 422 Validation Failed
- Check request payload format
- Validate all required fields
- Review validation rules in controller

### 404 Not Found
- Verify route exists
- Check URL parameters
- Confirm resource exists

---

## Best Practices

### Security
- Always validate input on server side
- Use HTTPS in production
- Implement CSRF protection
- Sanitize output to prevent XSS
- Implement rate limiting

### Performance
- Use eager loading for relationships
- Implement pagination for large datasets
- Cache frequently accessed data
- Use database indexes
- Minimize N+1 queries

### Documentation
- Keep API docs updated
- Document all endpoints
- Provide request/response examples
- List all required parameters
- Document error responses

---

**Last Updated:** November 2024
**API Version:** 1.0
**Status:** Production Ready

For routing questions, check `routes/` directory
For controller methods, check `app/Http/Controllers/` directory
For model definitions, check `app/Models/` directory
