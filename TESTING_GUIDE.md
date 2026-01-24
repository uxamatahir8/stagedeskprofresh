# 🧪 Quick Testing Guide - Company Creates Customer Account Feature

## Prerequisites
- ✅ Laravel application running
- ✅ Database migrated and seeded
- ✅ Mail configuration set up
- ✅ Admin account with company access

---

## Test Scenario 1: Happy Path - Create New Customer Account

### Steps:
1. **Log in as Company Admin**
   - Navigate to `/login`
   - Enter admin credentials
   - Verify successful login

2. **Navigate to Create Booking**
   - Go to Dashboard → Bookings → Create New Booking
   - URL should be: `/bookings/create`

3. **Verify Form State**
   - ✅ Customer dropdown visible and empty
   - ✅ "Create Customer Account" checkbox visible
   - ✅ Helper text: "Leave empty to create a new customer account"

4. **Fill Booking Form**
   ```
   Customer: [Leave Empty]
   Create Customer Account: ✓ (Check this)
   
   Event Type: Wedding
   Event Date: [Future date, e.g., 2 weeks from now]
   Start Time: 18:00
   End Time: 23:00
   
   Name: John
   Surname: Doe
   Email: newcustomer@test.com  [IMPORTANT: Use NEW email]
   Phone: 555-0123
   Date of Birth: 1990-01-01
   Address: 123 Main St, City, State
   
   [Fill other required fields as needed]
   ```

5. **Submit Form**
   - Click "Create Booking" button
   - Wait for redirect

6. **Verify Success**
   - ✅ Green success message appears:
     ```
     "Booking created successfully! A new customer account has been 
     created for newcustomer@test.com, and login credentials have 
     been sent to their email."
     ```
   - ✅ Redirected to `/bookings`

7. **Check Database** (Optional)
   ```sql
   -- Check user created
   SELECT * FROM users WHERE email = 'newcustomer@test.com';
   -- Should return 1 row
   
   -- Check password hashed
   -- Password field should start with: $2y$
   
   -- Check email verified
   -- email_verified_at should NOT be NULL
   
   -- Check activity log
   SELECT * FROM activity_logs WHERE action = 'customer_created' ORDER BY id DESC LIMIT 1;
   ```

8. **Check Email**
   - Open MailHog/Mailtrap/Email client
   - Find email: "Your Account Has Been Created - StageDesk Pro"
   - Verify email contains:
     - ✅ Welcome message
     - ✅ Credentials box (email + password)
     - ✅ Security warning
     - ✅ Booking details
     - ✅ Login button
   - **Copy the password**

9. **Test Login**
   - Log out from admin account
   - Navigate to `/login`
   - Enter:
     - Email: newcustomer@test.com
     - Password: [from email]
   - Click Login
   - ✅ Should successfully log in
   - ✅ Should see customer dashboard

### Expected Results:
- ✅ Booking created successfully
- ✅ New user account created in database
- ✅ Email sent with credentials
- ✅ Customer can log in immediately
- ✅ Activity logged

---

## Test Scenario 2: Email Already Exists

### Steps:
1. **Log in as Company Admin**

2. **Navigate to Create Booking**

3. **Fill Form with EXISTING Email**
   ```
   Customer: [Leave Empty]
   Create Customer Account: ✓ (Check this)
   
   Email: existingcustomer@test.com  [Use email that already exists]
   [Fill other fields...]
   ```

4. **Submit Form**

5. **Verify Behavior**
   - ✅ Booking created successfully
   - ✅ Success message: "Booking created successfully!" (no mention of account creation)
   - ✅ NO credential email sent (only standard booking confirmation)
   - ✅ Booking linked to existing user

### Expected Results:
- ✅ No duplicate account created
- ✅ Existing user used for booking
- ✅ Standard confirmation email (not credentials)

---

## Test Scenario 3: Validation Error - No Customer + Create Account Disabled

### Steps:
1. **Log in as Company Admin**

2. **Navigate to Create Booking**

3. **Fill Form**
   ```
   Customer: [Leave Empty]
   Create Customer Account: ☐ (Leave UNCHECKED)
   [Fill other fields...]
   ```

4. **Submit Form**

5. **Verify Error**
   - ✅ Validation error appears
   - ✅ Error message: "Please select a customer or enable 'Create Customer Account'"
   - ✅ Form retains all input values
   - ✅ User redirected back to form

### Expected Results:
- ✅ Form submission blocked
- ✅ Clear error message
- ✅ No booking created

---

## Test Scenario 4: JavaScript Behavior

### Steps:
1. **Log in as Company Admin**

2. **Navigate to Create Booking**

3. **Test Toggle Behavior**
   ```
   Initial State:
   - Customer dropdown: Empty
   - Create Account checkbox: Visible
   
   Action 1: Select a customer from dropdown
   Result: Create Account checkbox disappears
   
   Action 2: Clear customer selection (back to empty)
   Result: Create Account checkbox reappears
   ```

### Expected Results:
- ✅ Checkbox shows/hides based on customer selection
- ✅ Smooth UI experience
- ✅ No console errors

---

## Test Scenario 5: Password Security

### Steps:
1. **Create 5 New Customer Accounts**
   - Follow Test Scenario 1
   - Use different emails

2. **Examine Generated Passwords**
   - Check emails for each customer
   - Copy passwords
   - Verify each password:
     - ✅ Length: 12 characters
     - ✅ Contains uppercase (A-Z)
     - ✅ Contains lowercase (a-z)
     - ✅ Contains numbers (0-9)
     - ✅ Contains special chars (!@#$%^&*)
     - ✅ All unique (no duplicates)

3. **Check Database Hashing**
   ```sql
   SELECT email, password FROM users WHERE email LIKE '%test.com' ORDER BY id DESC LIMIT 5;
   ```
   - ✅ All passwords start with `$2y$`
   - ✅ No plain text passwords

### Expected Results:
- ✅ Strong passwords generated
- ✅ All passwords hashed
- ✅ No password reuse

---

## Test Scenario 6: Email Template Rendering

### Steps:
1. **Create New Customer Account**
   - Follow Test Scenario 1

2. **Open Email in Different Clients**
   - Gmail web
   - Outlook web
   - Mobile email app

3. **Verify Rendering**
   - ✅ Email displays correctly
   - ✅ Colors show properly
   - ✅ Button is clickable
   - ✅ Text is readable
   - ✅ Responsive on mobile

### Expected Results:
- ✅ Professional appearance
- ✅ Cross-client compatibility
- ✅ Mobile-friendly

---

## Test Scenario 7: Activity Log

### Steps:
1. **Create New Customer Account**
   - Follow Test Scenario 1

2. **Navigate to Activity Logs**
   - Go to Dashboard → Activity Logs
   - Or directly: `/activity-logs`

3. **Find Log Entry**
   - Filter by action: "customer_created"
   - Or sort by date (newest first)

4. **Verify Log Entry**
   ```
   User: [Admin who created customer]
   Action: customer_created
   Description: "Created new customer account for newcustomer@test.com"
   IP Address: [Admin's IP]
   User Agent: [Admin's browser]
   Date: [Current timestamp]
   ```

### Expected Results:
- ✅ Log entry created
- ✅ All details accurate
- ✅ Audit trail maintained

---

## Test Scenario 8: Edit Existing Booking

### Steps:
1. **Create a Booking** (any method)

2. **Edit the Booking**
   - Go to Bookings list
   - Click "Edit" on a booking

3. **Verify Form State**
   - ✅ Customer dropdown shows selected customer
   - ✅ Create Account checkbox HIDDEN
   - ✅ Cannot create new account during edit

4. **Update Booking Details**
   - Change event date or other fields
   - Submit form

5. **Verify Behavior**
   - ✅ Booking updated
   - ✅ No new account created
   - ✅ Customer unchanged

### Expected Results:
- ✅ Edit mode prevents account creation
- ✅ Update only affects booking

---

## Quick Test Commands

### Check Mail Configuration
```bash
php artisan tinker
```
```php
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

### Check User Created
```bash
php artisan tinker
```
```php
User::where('email', 'newcustomer@test.com')->first();
```

### Check Activity Log
```bash
php artisan tinker
```
```php
ActivityLog::where('action', 'customer_created')->latest()->first();
```

### Generate Test Password
```bash
php artisan tinker
```
```php
app('App\Http\Controllers\BookingController')->generateSecurePassword();
// If method is private, test the logic separately
```

---

## Common Issues & Solutions

### Issue: Email not sending
**Solution:**
```bash
# Check mail configuration
php artisan config:clear

# Check .env file
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025

# If using queues, run worker
php artisan queue:work
```

### Issue: JavaScript not working
**Solution:**
```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Hard refresh browser
Ctrl + Shift + R (or Cmd + Shift + R on Mac)
```

### Issue: Validation error on valid data
**Solution:**
- Check date format (should be Y-m-d)
- Check event date is in future
- Check date of birth is at least 5 days old
- Check all required fields filled

---

## Test Data Template

```
# Customer 1
Name: John
Surname: Doe
Email: john.doe@test.com
Phone: 555-0101
DOB: 1990-01-15
Address: 123 Main St, City, State

# Customer 2
Name: Jane
Surname: Smith
Email: jane.smith@test.com
Phone: 555-0102
DOB: 1985-06-20
Address: 456 Oak Ave, City, State

# Customer 3
Name: Bob
Surname: Johnson
Email: bob.johnson@test.com
Phone: 555-0103
DOB: 1992-11-30
Address: 789 Pine Rd, City, State
```

---

## Testing Checklist

### Functional Tests
- [ ] Create booking with new customer account
- [ ] Create booking with existing email
- [ ] Create booking with customer selected
- [ ] Attempt create without customer or checkbox
- [ ] Edit existing booking (no account creation)

### UI Tests
- [ ] JavaScript toggle behavior
- [ ] Form validation messages
- [ ] Success messages
- [ ] Error messages

### Email Tests
- [ ] Credential email received
- [ ] Email content correct
- [ ] Email renders properly
- [ ] Login link works

### Security Tests
- [ ] Passwords strong (12+ chars, mixed types)
- [ ] Passwords hashed in database
- [ ] Auto-verification works
- [ ] Activity logging works

### Database Tests
- [ ] User record created
- [ ] Booking linked to user
- [ ] Activity log entry exists
- [ ] No duplicate accounts

---

## Test Results Template

```
Test Date: ___________
Tester: ___________
Environment: [ ] Local [ ] Staging [ ] Production

Test Scenario 1: Create New Customer
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Test Scenario 2: Existing Email
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Test Scenario 3: Validation Error
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Test Scenario 4: JavaScript
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Test Scenario 5: Password Security
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Test Scenario 6: Email Template
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Test Scenario 7: Activity Log
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Test Scenario 8: Edit Booking
Status: [ ] PASS [ ] FAIL
Notes: _______________________________

Overall Status: [ ] ALL PASS [ ] SOME FAIL
Ready for Production: [ ] YES [ ] NO
```

---

**Happy Testing! 🎉**
