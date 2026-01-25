# 🧪 Quick Testing Guide - Booking Enhancements

## ✅ **Feature Testing Checklist**

### **1. Customer Autofill Feature**

#### **Test 1: Basic Autofill**
1. Login as Company Admin or Master Admin
2. Go to **Bookings → Create Booking**
3. Select a customer from "Customer" dropdown
4. **Expected:** Name, Surname, Email, Phone fields auto-populate
5. **Expected:** Fields become readonly (grayed out)
6. **Note:** Phone is loaded from user_profiles table

#### **Test 2: Clear Autofill**
1. After selecting a customer (fields filled)
2. Change dropdown back to "Select Existing Customer..."
3. **Expected:** All fields clear
4. **Expected:** Fields become editable again

#### **Test 3: Data Accuracy**
1. Select customer "John Doe" with email "john@test.com"
2. **Expected:** 
   - Name field = "John"
   - Surname field = "Doe"
   - Email field = "john@test.com"
   - Phone field = customer's phone from user_profiles table

---

### **2. Master Admin Company Selection**

#### **Test 1: Field Visibility**
1. Login as **Master Admin**
2. Go to **Bookings → Create Booking**
3. **Expected:** "Company" dropdown appears BEFORE "Customer" dropdown
4. **Expected:** Field is marked as required (*)

5. Login as **Company Admin**
6. Go to **Bookings → Create Booking**
7. **Expected:** NO "Company" dropdown (auto-assigned)

#### **Test 2: Company Selection**
1. As Master Admin, create booking
2. Select "Company A" from dropdown
3. Select customer and fill details
4. Submit booking
5. **Expected:** Booking saved with company_id = Company A's ID
6. **Expected:** View booking shows company name

---

### **3. Email Notifications - New Booking**

#### **Test 1: Master Admin Creates Booking**
1. Login as **Master Admin**
2. Create booking for "Company A"
3. Submit booking
4. **Expected Emails:**
   - ✉️ Customer: "Booking Confirmation"
   - ✉️ Company A Admin: "New Booking Created"
5. Check company admin email contains:
   - Booking ID
   - Customer name
   - Event date
   - "View Booking Details" button

#### **Test 2: Company Admin Creates Booking**
1. Login as **Company Admin**
2. Create booking (company auto-assigned)
3. Submit booking
4. **Expected Emails:**
   - ✉️ Customer: "Booking Confirmation" ✅
   - ✉️ Company Admin: NO EMAIL ❌
5. Company admin should NOT receive their own booking notification

---

### **4. Artist Filtering by Company**

#### **Test 1: Master Admin - Index Page**
1. Login as **Master Admin**
2. Go to **Bookings** list
3. Find booking for "Company A"
4. Click "Assign Artist" button
5. Open artist dropdown
6. **Expected:** Only artists from Company A shown
7. **Expected:** Helper text: "Only artists from Company A are shown"
8. **Expected:** Each artist shows company name in parentheses

#### **Test 2: Master Admin - Show Page**
1. As Master Admin, open booking for "Company B"
2. Click "Assign Artist" in sidebar
3. Open modal artist dropdown
4. **Expected:** Only Company B artists visible
5. Try reassign modal
6. **Expected:** Same filtering applies

#### **Test 3: Company Admin - No Filtering**
1. Login as **Company Admin** (Company A)
2. View any booking
3. Click "Assign Artist"
4. **Expected:** All Company A artists shown (no filtering needed)
5. **Expected:** No company name in parentheses

---

### **5. Email Notifications - Artist Assignment**

#### **Test 1: Master Admin Assigns Artist**
1. Login as **Master Admin**
2. Open booking for "Company A"
3. Assign artist "DJ Mike" from Company A
4. Submit assignment
5. **Expected Emails:**
   - ✉️ DJ Mike: "New Booking Assignment"
   - ✉️ Customer: "Artist Assigned"
   - ✉️ Company A Admin: "Artist Assigned to Booking" ⭐
6. Check company admin email contains:
   - Artist name and details
   - Booking information
   - Note: "Artist has been notified"

#### **Test 2: Company Admin Assigns Artist**
1. Login as **Company Admin**
2. Assign artist to booking
3. Submit assignment
4. **Expected Emails:**
   - ✉️ Artist: "New Booking Assignment" ✅
   - ✉️ Customer: "Artist Assigned" ✅
   - ✉️ Company Admin: NO EMAIL ❌
5. Company admin should NOT email themselves

---

## 🔍 **Error Scenarios**

### **Test 1: Missing Company (Master Admin)**
1. As Master Admin, try to submit booking without selecting company
2. **Expected:** Validation error: "Company field is required"

### **Test 2: Readonly Field Edit Attempt**
1. Select customer (fields autofill and become readonly)
2. Try to edit Name field
3. **Expected:** Field is not editable (readonly)

### **Test 3: Wrong Company Artists**
1. Master Admin viewing Company A booking
2. Open assign artist modal
3. **Expected:** Company B and C artists should NOT appear
4. **Expected:** Only Company A artists visible

---

## 📊 **Database Verification**

### **After Creating Booking:**
```sql
SELECT id, booking_id, company_id, user_id, name, surname, email, phone, status
FROM booking_requests 
WHERE id = [YOUR_BOOKING_ID];
```
**Expected:**
- company_id = selected company (master admin) or auto-assigned (company admin)
- user_id = selected customer ID
- name, surname, email, phone = customer data

### **After Assigning Artist:**
```sql
SELECT id, assigned_artist_id, status, confirmed_at
FROM booking_requests 
WHERE id = [YOUR_BOOKING_ID];
```
**Expected:**
- assigned_artist_id = artist ID
- status = 'pending' (NOT 'confirmed')
- confirmed_at = NULL

### **Check Artist Company Match:**
```sql
SELECT b.id, b.company_id AS booking_company, a.company_id AS artist_company
FROM booking_requests b
JOIN artists a ON b.assigned_artist_id = a.id
WHERE b.id = [YOUR_BOOKING_ID];
```
**Expected:**
- booking_company = artist_company (must match!)

---

## 📧 **Email Log Verification**

Check Laravel logs for email sending:
```bash
tail -f storage/logs/laravel.log | grep "Failed to send"
```

**Should See NO Errors:**
- "Failed to send assignment email to artist"
- "Failed to send booking notification to company admin"
- "Failed to send artist assignment notification to company admin"

---

## 🎯 **Quick Test Script**

Run this test sequence in 10 minutes:

1. ✅ Login as Master Admin
2. ✅ Create booking → Select Company A → Select Customer → Submit
3. ✅ Check company admin email received
4. ✅ Assign artist → Verify only Company A artists shown
5. ✅ Submit assignment → Check company admin email
6. ✅ Login as Company Admin
7. ✅ Create booking → Verify no company dropdown → Submit
8. ✅ Check NO email to company admin
9. ✅ Assign artist → Verify all company artists shown
10. ✅ Check NO email to company admin

**Expected Result:** All 10 steps pass ✅

---

## 🐛 **Common Issues & Solutions**

| Issue | Solution |
|-------|----------|
| Customer data not autofilling | Clear browser cache, check console for JS errors |
| Company dropdown not showing | Verify logged in as master_admin role |
| Wrong artists appearing | Check booking has company_id set correctly |
| Emails not sending | Check `.env` mail configuration, run `php artisan config:clear` |
| Fields not readonly | Check if customer was actually selected (value not empty) |
| Email missing booking details | Clear view cache: `php artisan view:clear` |

---

## 🔧 **Debug Commands**

```bash
# Check user role
php artisan tinker
>>> Auth::user()->role->role_key

# Test email configuration
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Check booking data
php artisan tinker
>>> $booking = BookingRequest::find(123);
>>> $booking->company;
>>> $booking->assignedArtist;
>>> $booking->company_id;
```

---

## ✨ **Success Criteria**

All features working when:
- ✅ Customer selection autofills all 4 fields correctly (name, surname, email, phone)
- ✅ Phone is loaded from user_profiles table via profile relationship
- ✅ Master admin sees company dropdown, company admin does not
- ✅ Master admin creating booking sends email to company admin
- ✅ Company admin creating booking does NOT email themselves
- ✅ Artist dropdowns filter by booking's company (master admin)
- ✅ Master admin assigning artist sends email to company admin
- ✅ Company admin assigning artist does NOT email themselves
- ✅ All emails contain correct booking and user information
- ✅ Database relationships correct (booking.company_id = artist.company_id)
- ✅ No JavaScript errors in browser console
- ✅ No PHP errors in Laravel logs

---

**Testing Date:** January 26, 2026  
**Tester:** _________________  
**Result:** PASS ✅ / FAIL ❌  
**Notes:** _________________
