# 🚀 Quick Reference - Company Creates Customer Account

## ⚡ Feature Overview
Company admins can create bookings for new customers. System automatically creates customer account, generates secure password, and emails credentials.

---

## 📋 Quick Start (Admin)

1. Go to: **Create Booking** page
2. Leave **Customer** dropdown empty
3. Toggle **"Create Customer Account"** ON
4. Fill form with customer email & details
5. Submit
6. Customer receives email with login credentials

---

## 📧 Email Details

**Subject:** "Your Account Has Been Created - StageDesk Pro"

**Contains:**
- Login email & temporary password
- Booking details
- Security warning (change password)
- Direct login link

**Template:** `resources/views/emails/customer-account-created.blade.php`  
**Mailable:** `App\Mail\CustomerAccountCreated`

---

## 🔐 Password Specifications

- **Length:** 12 characters
- **Types:** Uppercase + Lowercase + Numbers + Special (!@#$%^&*)
- **Storage:** Bcrypt hashed (never plain text)
- **Transmission:** Sent once via email

---

## 🎯 Key Files

### Backend
- `app/Http/Controllers/BookingController.php` (lines 150-340)
  - `store()` method - handles account creation
  - `generateSecurePassword()` - creates strong passwords

- `app/Mail/CustomerAccountCreated.php`
  - Mailable class for credential emails

### Frontend
- `resources/views/dashboard/pages/bookings/manage.blade.php`
  - Booking form with create account toggle
  - JavaScript for smart form behavior

- `resources/views/emails/customer-account-created.blade.php`
  - Beautiful HTML email template

---

## ✅ Edge Cases Handled

1. **Email Exists:** Uses existing user, sends standard confirmation
2. **No Customer + No Checkbox:** Validation error
3. **Customer Selected:** Create account option hidden (JavaScript)
4. **Edit Mode:** Cannot create account during edit

---

## 🧪 Quick Test

```bash
# 1. Log in as admin
# 2. Go to /bookings/create
# 3. Leave customer empty, check "Create Customer Account"
# 4. Enter: newtest@test.com
# 5. Fill other fields
# 6. Submit

# Expected:
# ✅ Success message mentions account creation
# ✅ Email sent to newtest@test.com
# ✅ Customer can log in with emailed password
```

---

## 🔍 Troubleshooting

### Email Not Received?
```bash
# Check mail config
php artisan config:clear

# Check logs
tail -f storage/logs/laravel.log

# Test mail
php artisan tinker
Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
```

### Checkbox Not Showing?
- Hard refresh browser (Ctrl+F5)
- Check JavaScript console for errors
- Verify customer dropdown is empty

### Login Fails?
- Copy password carefully (no extra spaces)
- Check database: password starts with `$2y$`
- Try resetting password manually if needed

---

## 📊 Database Tables

### users
```sql
-- New customer record created with:
- email (from form)
- password (bcrypt hashed)
- email_verified_at (auto-set to NOW)
- role_id (customer role)
- company_id (admin's company)
```

### booking_requests
```sql
-- Booking linked to:
- user_id (new or existing customer)
- company_id (admin's company)
```

### activity_logs
```sql
-- Activity logged:
- action: 'customer_created'
- description: "Created new customer account for [email]"
- user_id: Admin who created
```

---

## 📖 Full Documentation

- **Complete Feature Guide:** [COMPANY_CUSTOMER_CREATION_FEATURE.md](COMPANY_CUSTOMER_CREATION_FEATURE.md) (800+ lines)
- **Testing Guide:** [TESTING_GUIDE.md](TESTING_GUIDE.md) (300+ lines)
- **Implementation Summary:** [FEATURE_IMPLEMENTATION_SUMMARY.md](FEATURE_IMPLEMENTATION_SUMMARY.md)
- **Booking Flow:** [BOOKING_FLOW_DOCUMENTATION.md](BOOKING_FLOW_DOCUMENTATION.md) (section 1.2a)
- **Project Docs:** [COMPLETE_PROJECT_DOCUMENTATION.md](COMPLETE_PROJECT_DOCUMENTATION.md) (section 3.3)

---

## 🎨 UI Elements

### Booking Form
```
Customer: [Dropdown - Optional]
ℹ️ Leave empty to create a new customer account

☑️ Create Customer Account
   Generate login credentials and send email to customer
```

### Success Message
```
✅ Booking created successfully! A new customer account has been 
   created for customer@email.com, and login credentials have 
   been sent to their email.
```

---

## 🔄 Workflow Summary

```
Admin Action           System Response            Customer Receives
─────────────         ──────────────────         ─────────────────
Creates booking   →   Email exists? Check    →   
(no customer)         ↓                          
                      No: Create user             
Enable checkbox       ↓                          
                      Generate password      →   Email with:
Fill details          ↓                          - Credentials
                      Hash with bcrypt           - Booking info
Submit form       →   ↓                          - Login link
                      Send credential email  →   
                      ↓                          
                      Log activity               Can log in
                      ↓                          immediately!
                      Create booking             
                      ↓                          
                      Show success msg       
```

---

## 💡 Pro Tips

1. **Email Display:** Customer dropdown shows "Name (email)" for clarity
2. **Auto-Verify:** Admin-created accounts skip email verification
3. **Smart Form:** JavaScript prevents conflicting selections
4. **Audit Trail:** All actions logged in activity_logs
5. **Strong Passwords:** 12-char minimum with mixed types

---

## 🎯 Status

**Feature:** ✅ Complete & Production Ready  
**Documentation:** ✅ Comprehensive  
**Testing:** ✅ Guide Provided  
**Security:** ✅ Implemented  

---

## 📞 Quick Links

- [Full Feature Documentation](COMPANY_CUSTOMER_CREATION_FEATURE.md)
- [Testing Guide](TESTING_GUIDE.md)
- [Booking Flow Docs](BOOKING_FLOW_DOCUMENTATION.md)
- [Complete Project Docs](COMPLETE_PROJECT_DOCUMENTATION.md)

---

**Last Updated:** January 2024  
**Version:** 1.0.0
