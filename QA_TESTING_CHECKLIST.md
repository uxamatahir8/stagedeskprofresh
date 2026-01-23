# Quality Assurance Testing Checklist

## 1. Email System Testing

### User Registration & Verification
- [ ] **Registration Email Sent**: New user receives verification email immediately after registration
- [ ] **Verification Link Works**: Clicking verification link activates account
- [ ] **Expired Token Handling**: Old verification links show appropriate error message
- [ ] **Resend Verification**: Users can request new verification email
- [ ] **Already Verified**: Attempting to verify already-verified account shows info message
- [ ] **Login Without Verification**: Unverified users cannot login and see appropriate message

### Login with Code
- [ ] **Request Code**: Email code sent within seconds of request
- [ ] **Code Format**: 6-digit numeric code received
- [ ] **Code Expiry**: Code expires after 10 minutes
- [ ] **One-Time Use**: Code cannot be reused after successful login
- [ ] **Invalid Code**: Wrong code shows error message
- [ ] **Rate Limiting**: Multiple code requests trigger rate limit (3 per 15 minutes)
- [ ] **Tab Switching**: UI correctly switches between password and code login tabs

### Booking Emails
- [ ] **Booking Created**: Customer receives confirmation email with all booking details
- [ ] **Artist Assigned**: Customer receives email when artist is assigned
- [ ] **Artist Reassigned**: Customer receives email when artist is changed
- [ ] **New Booking for Artist**: Artist receives email when assigned to booking
- [ ] **Booking Confirmed**: Customer receives email when artist accepts
- [ ] **Booking Status Changed**: Customer receives email on any status change
- [ ] **Booking Rejected**: Customer and company admin notified when artist rejects

### Artist Sharing Emails
- [ ] **Share Request Sent**: Recipient company receives share request email
- [ ] **Request Details**: Email contains artist and owner company information
- [ ] **Call-to-Action**: Email links work and direct to artist sharing page

### Security Alert Emails
- [ ] **Account Locked**: User receives email after 5 failed login attempts
- [ ] **Suspicious Activity**: Email sent when multiple IPs detected
- [ ] **Password Changed**: Confirmation email sent after password change
- [ ] **New Device Login**: Email sent when logging in from new device (optional feature)

## 2. Authentication & Security Testing

### Email Verification
- [ ] **Verification Required**: Unverified users cannot access system
- [ ] **Dashboard Access Blocked**: Unverified users redirected to login with message
- [ ] **Email Sent**: Verification email received in inbox (check spam folder)
- [ ] **Token Validation**: Only valid tokens can verify accounts
- [ ] **Auto-Login After Verification**: Users can login immediately after verification

### Password-Based Login
- [ ] **Valid Credentials**: Correct email/password logs in successfully
- [ ] **Invalid Password**: Wrong password shows error message
- [ ] **Invalid Email**: Non-existent email shows error message
- [ ] **Session Created**: User stays logged in after successful login
- [ ] **Remember Me**: Checkbox functionality works as expected
- [ ] **Forgot Password Link**: Link navigates to password reset page

### Code-Based Login
- [ ] **Code Generation**: Code generated and sent immediately
- [ ] **Code Validation**: Only valid, non-expired codes work
- [ ] **Auto-Focus**: Code input field auto-focused
- [ ] **Numeric Only**: Only numbers accepted in code field
- [ ] **Success Redirect**: Successful code login redirects to dashboard
- [ ] **Error Handling**: Invalid codes show clear error messages

### Account Security
- [ ] **Rate Limiting**: 5 login attempts per minute per IP enforced
- [ ] **Account Lockout**: Account locked after 5 failed attempts
- [ ] **Lockout Duration**: Account unlocked after 30 minutes
- [ ] **Auto-Unlock**: Expired lockouts automatically cleared
- [ ] **Failed Attempt Counter**: Counter increments on each failure
- [ ] **Counter Reset**: Counter resets on successful login

### Password Security
- [ ] **Password Complexity**: Minimum 10 characters with uppercase, lowercase, number, special character required
- [ ] **Password Confirmation**: Passwords must match during reset
- [ ] **Password History**: Last 5 passwords cannot be reused
- [ ] **History Tracking**: Password changes saved to history
- [ ] **Reset Token**: Token-based password reset works
- [ ] **Token Expiry**: Reset tokens expire after 60 minutes

## 3. Booking Workflow Testing

### Booking Creation
- [ ] **Form Validation**: All required fields validated
- [ ] **Date Validation**: Event date must be in future
- [ ] **Customer Assignment**: Customer ID auto-assigned for customer role
- [ ] **Company Assignment**: Company ID auto-assigned for company admin
- [ ] **Initial Status**: New bookings start with 'pending' status
- [ ] **Database Record**: Booking saved with unique booking_id
- [ ] **Activity Log**: Creation logged in activity_logs table
- [ ] **Email Sent**: Confirmation email sent to customer

### Artist Assignment
- [ ] **Artist Pool**: Both owned and shared artists available
- [ ] **Assignment Success**: Artist assigned and status changes to pending
- [ ] **Reassignment**: Existing artist can be replaced
- [ ] **Status Reset**: Reassignment resets booking to pending
- [ ] **Activity Log**: Assignment logged with previous artist ID
- [ ] **Customer Email**: Customer notified of assignment
- [ ] **Artist Email**: Artist notified of new booking
- [ ] **Company Verification**: Only company's artists assignable

### Artist Acceptance/Rejection
- [ ] **Accept Booking**: Artist can accept pending booking
- [ ] **Status Change**: Acceptance changes status to confirmed
- [ ] **Confirmation Timestamp**: confirmed_at set on acceptance
- [ ] **Customer Notification**: Customer receives confirmation email
- [ ] **Reject Booking**: Artist can reject with reason
- [ ] **Return to Pending**: Rejection returns booking to pending state
- [ ] **Artist Removed**: assigned_artist_id set to null on rejection
- [ ] **Reason Logged**: Rejection reason saved in company_notes
- [ ] **Reassignment Enabled**: Company can reassign after rejection

## 4. Artist Sharing Testing

### Share Request
- [ ] **Create Request**: Company can share owned artist
- [ ] **Self-Share Prevention**: Cannot share with own company
- [ ] **Duplicate Prevention**: Cannot share same artist twice with same company
- [ ] **Status Pending**: New shares start with pending status
- [ ] **Timestamp**: shared_at timestamp recorded
- [ ] **Activity Log**: Share request logged
- [ ] **Email Notification**: Recipient company receives email

### Share Management
- [ ] **Accept Share**: Recipient can accept pending request
- [ ] **Status Update**: Acceptance changes status to accepted
- [ ] **Acceptance Timestamp**: accepted_at recorded
- [ ] **Reject Share**: Recipient can reject request
- [ ] **Revoke Share**: Owner can revoke accepted share
- [ ] **Revocation Timestamp**: revoked_at recorded
- [ ] **UI Update**: Artist lists update immediately

### Artist Assignment from Shared Pool
- [ ] **Shared Artists Visible**: Accepted shared artists appear in assignment dropdown
- [ ] **Assignment Works**: Shared artists can be assigned to bookings
- [ ] **Permission Verification**: Only companies with accepted shares can assign
- [ ] **Status Enforcement**: Only accepted shares allow assignment

## 5. Security Features Testing

### Security Headers
- [ ] **CSP Header**: Content-Security-Policy present in response
- [ ] **XSS Protection**: X-XSS-Protection header set
- [ ] **Frame Options**: X-Frame-Options prevents clickjacking
- [ ] **HSTS**: Strict-Transport-Security header on HTTPS
- [ ] **Content Type**: X-Content-Type-Options prevents MIME sniffing
- [ ] **Referrer Policy**: Referrer-Policy header present

### Attack Detection
- [ ] **SQL Injection**: Attempts logged to security_logs table
- [ ] **XSS Attempts**: Script injection attempts detected
- [ ] **File Inclusion**: Path traversal attempts logged
- [ ] **Command Injection**: Shell command attempts detected
- [ ] **Suspicious User Agent**: Bot/crawler requests logged

### Activity Logging
- [ ] **Login Attempts**: All attempts logged in login_attempts table
- [ ] **Success/Failure**: Successful and failed logins recorded separately
- [ ] **IP Tracking**: IP addresses logged for all attempts
- [ ] **User Agent**: Browser/device information captured
- [ ] **Security Events**: All security events in security_logs table
- [ ] **Activity Logs**: User actions tracked in activity_logs table

## 6. User Interface Testing

### Login Page
- [ ] **Tab Navigation**: Smooth switching between password and code login
- [ ] **Form Validation**: Inline validation for all fields
- [ ] **Error Display**: Errors shown below respective fields
- [ ] **Success Messages**: Green alerts for successful actions
- [ ] **Loading States**: Buttons show loading during submission
- [ ] **Responsive Design**: Works on mobile, tablet, desktop

### Email Templates
- [ ] **Layout Consistent**: All emails use base layout
- [ ] **Branding**: App name and logo display correctly
- [ ] **Responsive**: Emails render well in desktop and mobile email clients
- [ ] **Links Work**: All buttons and links navigate correctly
- [ ] **Dynamic Content**: User-specific data displays correctly
- [ ] **Footer Present**: Contact info and unsubscribe notice included

### Dashboard Access
- [ ] **Redirect After Login**: Users directed to appropriate dashboard
- [ ] **Role-Based Access**: Each role sees correct dashboard
- [ ] **Verification Check**: Unverified users blocked from dashboard
- [ ] **Session Persistence**: Users stay logged in across page refreshes
- [ ] **Logout**: Logout button ends session and redirects to login

## 7. Database Testing

### Schema Validation
- [ ] **login_codes Table**: Exists with all columns
- [ ] **Indexes**: email, expires_at indexes present
- [ ] **Foreign Keys**: All relationships defined correctly
- [ ] **email_verified_at**: Column added to users table
- [ ] **verification_token**: Column added to users table

### Data Integrity
- [ ] **Unique Codes**: Login codes are unique per request
- [ ] **Code Expiry**: Expired codes not validated
- [ ] **Used Flag**: Used codes marked correctly
- [ ] **Cascading Deletes**: Dependent records deleted properly
- [ ] **Timestamps**: created_at and updated_at auto-populated

### Query Performance
- [ ] **Index Usage**: Queries use appropriate indexes
- [ ] **N+1 Prevention**: Eager loading used where needed
- [ ] **Transaction Safety**: All critical operations wrapped in transactions
- [ ] **Rollback**: Failed operations rollback completely

## 8. Error Handling Testing

### Validation Errors
- [ ] **Required Fields**: Missing fields show validation error
- [ ] **Email Format**: Invalid email format rejected
- [ ] **Password Strength**: Weak passwords rejected
- [ ] **Date Logic**: Past dates for future events rejected
- [ ] **Unique Constraints**: Duplicate entries prevented

### System Errors
- [ ] **Database Down**: Graceful error message shown
- [ ] **Mail Server Down**: Error handled without crashing
- [ ] **Invalid Routes**: 404 page shown for non-existent pages
- [ ] **Permission Errors**: 403 page shown for unauthorized access
- [ ] **Server Errors**: 500 page shown with generic message

### User Feedback
- [ ] **Success Messages**: Green alerts for successful actions
- [ ] **Error Messages**: Red alerts for failures
- [ ] **Info Messages**: Blue alerts for informational messages
- [ ] **Warning Messages**: Yellow alerts for warnings
- [ ] **Message Persistence**: Alerts visible until dismissed or page reload

## 9. Email Deliverability Testing

### SMTP Configuration
- [ ] **Connection**: SMTP server connects successfully
- [ ] **Authentication**: Credentials validated
- [ ] **Sending**: Test emails send without errors
- [ ] **Queue**: Queued emails process correctly (if using queue)

### Email Reception
- [ ] **Inbox Delivery**: Emails arrive in inbox (not spam)
- [ ] **Timing**: Emails received within acceptable time
- [ ] **Content**: All dynamic content renders correctly
- [ ] **Links**: All links in emails work
- [ ] **Images**: Images load (if any)

### Email Content
- [ ] **Subject Lines**: Clear and descriptive
- [ ] **From Address**: Recognizable sender address
- [ ] **Reply-To**: Reply address configured
- [ ] **Text Version**: Plain text version available (optional)
- [ ] **Unsubscribe**: Unsubscribe link present (for marketing emails)

## 10. Performance Testing

### Page Load Times
- [ ] **Login Page**: Loads in < 2 seconds
- [ ] **Dashboard**: Loads in < 3 seconds
- [ ] **Booking List**: Loads in < 3 seconds with pagination
- [ ] **Artist Sharing**: Loads in < 3 seconds

### Email Sending
- [ ] **Immediate Emails**: Verification/login codes sent in < 5 seconds
- [ ] **Batch Processing**: Multiple emails don't slow down system
- [ ] **Queue Processing**: Background jobs complete successfully

### Database Queries
- [ ] **Query Count**: Pages execute < 20 queries
- [ ] **Query Time**: Individual queries complete in < 100ms
- [ ] **Connection Pool**: Connections managed efficiently

## 11. Cross-Browser Testing

### Browsers to Test
- [ ] **Chrome**: Latest version
- [ ] **Firefox**: Latest version
- [ ] **Safari**: Latest version
- [ ] **Edge**: Latest version
- [ ] **Mobile Chrome**: Android
- [ ] **Mobile Safari**: iOS

### Features to Verify
- [ ] **Tab Navigation**: Works in all browsers
- [ ] **Form Submission**: No JavaScript errors
- [ ] **CSS Rendering**: Styles display correctly
- [ ] **JavaScript**: All interactive features work

## 12. Final QA Checklist

### Documentation
- [ ] **README Updated**: Installation instructions current
- [ ] **API Documentation**: All endpoints documented
- [ ] **Deployment Guide**: Step-by-step deployment instructions
- [ ] **User Guide**: End-user documentation complete
- [ ] **Admin Guide**: Administrative procedures documented

### Security Review
- [ ] **No Hardcoded Secrets**: All credentials in .env file
- [ ] **HTTPS Enforced**: Production uses HTTPS
- [ ] **CSRF Protection**: All forms protected
- [ ] **SQL Injection**: Parameterized queries used
- [ ] **XSS Protection**: Output escaped/sanitized

### Code Quality
- [ ] **No Debug Code**: dd(), dump(), var_dump() removed
- [ ] **No Console Logs**: console.log() statements removed
- [ ] **Error Handling**: Try-catch blocks present
- [ ] **Comments**: Complex logic commented
- [ ] **Code Standards**: PSR-12 standards followed

### Deployment Readiness
- [ ] **Environment Variables**: All required env vars documented
- [ ] **Migrations**: All migrations tested
- [ ] **Seeders**: Sample data seeders work
- [ ] **Cache Clear**: Cache clearing commands work
- [ ] **Asset Compilation**: npm run build completes successfully

### Client Handover
- [ ] **Admin Account**: Default admin created
- [ ] **Demo Data**: Sample bookings/artists present
- [ ] **Training Materials**: Videos/guides prepared
- [ ] **Support Contact**: Support email/phone provided
- [ ] **Warranty Period**: Support duration agreed

---

## Test Results Template

### Tester Information
- **Tester Name**: _________________
- **Date**: _________________
- **Environment**: _________________

### Summary
- **Total Tests**: _________________
- **Passed**: _________________
- **Failed**: _________________
- **Blocked**: _________________

### Critical Issues Found
1. _________________
2. _________________
3. _________________

### Recommendations
_________________
_________________
_________________

### Sign-Off
- **QA Lead**: _________________ Date: _________________
- **Project Manager**: _________________ Date: _________________
- **Client Representative**: _________________ Date: _________________
