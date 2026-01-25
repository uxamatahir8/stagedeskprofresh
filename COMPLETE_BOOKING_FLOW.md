# 🎯 Complete Booking Flow - StageDesk Pro

## 📋 **Overview**
This document outlines the complete booking workflow from creation to completion, including all status changes, notifications, and role-based actions.

---

## 🔄 **Booking Status Flow**

```
┌─────────────────────────────────────────────────────────────────┐
│                     BOOKING LIFECYCLE                           │
└─────────────────────────────────────────────────────────────────┘

1. PENDING (Initial)
   ↓
2. PENDING (Artist Assigned - Waiting for Acceptance)
   ↓
   ├─→ CONFIRMED (Artist Accepts)
   │   ↓
   │   ├─→ COMPLETED (Event Finished)
   │   │
   │   └─→ PENDING (Artist Cancels - Emergency)
   │
   └─→ PENDING (Artist Rejects - Unassigned)
```

---

## 📝 **Step-by-Step Flow**

### **Step 1: Customer Creates Booking**
- **Action:** Customer submits booking request
- **Initial Status:** `pending`
- **Fields Set:**
  - Event details (date, type, location)
  - Customer information
  - Special requests, music preferences
  
**Notifications Sent:**
- ✉️ Email to Customer: Booking confirmation
- 📧 Email to Company Admin: New booking alert
- 🔔 In-app notification to Company Admin

**Database:**
```php
BookingRequest::create([
    'status' => 'pending',
    'user_id' => $customerId,
    'company_id' => null, // or assigned company
    'assigned_artist_id' => null,
    'confirmed_at' => null,
    'completed_at' => null,
]);
```

---

### **Step 2: Company Admin Assigns DJ/Artist**
- **Action:** Company admin selects and assigns artist to booking
- **Status:** `pending` (UNCHANGED - waiting for artist acceptance)
- **Fields Updated:**
  - `assigned_artist_id` = artist ID
  - `company_notes` = optional notes for artist

**Notifications Sent:**
- ✉️ Email to Artist: New booking assignment (needs acceptance)
- ✉️ Email to Customer: Artist has been assigned
- 📧 Email to Previous Artist (if reassignment): You've been unassigned
- 🔔 In-app notification to Artist

**Important:** Status remains `pending` because artist must accept or reject!

**Code Example:**
```php
$booking->update([
    'assigned_artist_id' => $artistId,
    'status' => 'pending', // NOT 'confirmed' yet!
    'confirmed_at' => null,
]);
```

---

### **Step 3a: Artist Accepts Booking** ✅
- **Action:** Artist clicks "Accept Booking" button
- **Status Change:** `pending` → `confirmed`
- **Fields Updated:**
  - `status` = 'confirmed'
  - `confirmed_at` = current timestamp
  - `company_notes` += acceptance note with timestamp

**Notifications Sent:**
- ✉️ Email to Customer: Artist accepted, booking confirmed
- ✉️ Email to Company Admin: Artist accepted booking
- 🔔 In-app notification to Company Admin
- 🔔 In-app notification to Customer

**Code:**
```php
$booking->update([
    'status' => 'confirmed',
    'confirmed_at' => now(),
    'company_notes' => ($notes ?? '') . "\n[" . now() . "] Artist accepted",
]);
```

**Artist Portal:** Shows "Mark as Completed" button (after event)

---

### **Step 3b: Artist Rejects Booking** ❌
- **Action:** Artist clicks "Reject Booking" and provides reason
- **Status:** `pending` (UNCHANGED - but unassigned)
- **Fields Updated:**
  - `assigned_artist_id` = null (unassigned)
  - `confirmed_at` = null
  - `company_notes` += rejection note with reason and timestamp

**Notifications Sent:**
- ✉️ Email to Customer: Artist unavailable, finding replacement
- ✉️ Email to Company Admin: Artist rejected (includes reason)
- 🔔 In-app notification to Company Admin

**Code:**
```php
$booking->update([
    'status' => 'pending', // Ready for reassignment
    'assigned_artist_id' => null,
    'confirmed_at' => null,
    'company_notes' => ($notes ?? '') . "\n[" . now() . "] Rejected by {$artist}: {$reason}",
]);
```

**Next:** Company admin can assign a different artist (back to Step 2)

---

### **Step 4: Artist Marks as Completed** ✅
- **Action:** After performing at event, artist marks booking complete
- **Status Change:** `confirmed` → `completed`
- **Fields Updated:**
  - `status` = 'completed'
  - `completed_at` = current timestamp
  - `company_notes` += completion notes from artist

**Notifications Sent:**
- ✉️ Email to Customer: Event completed, please review
- ✉️ Email to Company Admin: Booking completed by artist
- 🔔 In-app notification to Company Admin
- 🔔 In-app notification to Customer

**Code:**
```php
$booking->update([
    'status' => 'completed',
    'completed_at' => now(),
    'company_notes' => ($notes ?? '') . "\nArtist completion notes: {$notes}",
]);
```

**Next:** Customer can leave review for artist

---

### **Step 5 (Optional): Artist Cancels Confirmed Booking** 🚫
- **Action:** Artist cancels commitment (emergency only)
- **Status Change:** `confirmed` → `pending`
- **Fields Updated:**
  - `status` = 'pending'
  - `assigned_artist_id` = null (unassigned)
  - `confirmed_at` = null
  - `company_notes` += cancellation note with reason

**Notifications Sent:**
- ✉️ Email to Customer: Artist cancelled, finding replacement
- ✉️ Email to Company Admin: Emergency cancellation (includes reason)
- 🔔 In-app notification to Company Admin (urgent)

**Code:**
```php
$booking->update([
    'status' => 'pending', // Ready for reassignment
    'assigned_artist_id' => null,
    'confirmed_at' => null,
    'company_notes' => ($notes ?? '') . "\n[" . now() . "] CANCELLED by {$artist}: {$reason}",
]);
```

---

## 👥 **Role-Based Permissions**

### **Customer**
| Status | Can View | Can Edit | Can Cancel |
|--------|----------|----------|------------|
| pending | ✅ | ❌ | ✅ |
| confirmed | ✅ | ❌ | ✅ (with penalty) |
| completed | ✅ | ❌ | ❌ |

**Available Actions:**
- View booking details
- View assigned artist info
- Cancel booking (before event)
- Leave review (after completion)

---

### **Artist/DJ**
| Status | Can View | Can Accept | Can Reject | Can Complete |
|--------|----------|------------|------------|--------------|
| pending (assigned) | ✅ | ✅ | ✅ | ❌ |
| pending (unassigned) | ❌ | ❌ | ❌ | ❌ |
| confirmed | ✅ | ❌ | ✅ (emergency) | ✅ |
| completed | ✅ | ❌ | ❌ | ❌ |

**Available Actions:**
- View assigned bookings only
- Accept/Reject pending bookings
- Mark confirmed bookings as completed
- Emergency cancel (confirmed bookings only)
- View payment information

---

### **Company Admin**
| Status | Can View | Can Edit | Can Assign | Can Reassign |
|--------|----------|----------|------------|--------------|
| pending | ✅ | ✅ | ✅ | ✅ |
| confirmed | ✅ | ✅ | ❌ | ✅ |
| completed | ✅ | ❌ | ❌ | ❌ |

**Available Actions:**
- View all company bookings
- Edit booking details
- Assign/reassign artists
- View all notifications
- Access reports and analytics

---

### **Master Admin**
- **Full access to all bookings across all companies**
- Can override any status
- Can manage all users and artists
- Access to system-wide analytics

---

## 📧 **Email Notification Summary**

### **Sent to Customer:**
1. Booking created confirmation
2. Artist assigned notification
3. Booking confirmed by artist
4. Artist rejected/cancelled (finding replacement)
5. Booking completed (with review request)
6. Payment receipts

### **Sent to Artist:**
1. New booking assignment (action required)
2. Booking reassigned to someone else
3. Booking cancelled by customer
4. Payment information

### **Sent to Company Admin:**
1. New booking created
2. Artist accepted booking
3. Artist rejected booking (with reason)
4. Artist cancelled confirmed booking (emergency)
5. Booking completed by artist
6. Customer cancelled booking
7. Payment received notifications

---

## 🔔 **In-App Notifications**

**Company Admin Receives:**
- 🟡 Pending booking requiring artist assignment
- 🟢 Artist accepted booking
- 🔴 Artist rejected booking
- 🔴 Artist cancelled confirmed booking
- 🔵 Booking completed
- 💰 Payment received

**Artist Receives:**
- 🟡 New booking assignment (needs acceptance)
- 🔴 Booking reassigned to another artist
- 🔴 Booking cancelled by customer
- 💰 Payment processed

**Customer Receives:**
- 🟢 Artist assigned
- 🟢 Booking confirmed by artist
- 🟡 Artist unavailable (finding replacement)
- 🔵 Event completed (review request)

---

## ⚠️ **Important Notes**

### **Status: `pending`**
A booking can be in `pending` status in three scenarios:
1. **Just created** - No artist assigned yet
2. **Artist assigned** - Waiting for artist to accept/reject
3. **Artist rejected** - Available for reassignment

**Check:** Use `assigned_artist_id` field to differentiate:
- `null` = Not assigned or rejected
- `has value` = Waiting for artist response

### **Status: `confirmed`**
- Artist has accepted the booking
- Customer is notified
- Artist is committed to perform
- Can only be cancelled in emergency (goes back to `pending`)

### **Status: `completed`**
- Event is finished
- Artist marked it as completed
- Customer can leave review
- Status is final (cannot be changed)

---

## 🛠️ **Implementation Files**

### **Controllers:**
- `BookingController.php` - Main booking CRUD and artist assignment
- `ArtistPortalController.php` - Artist actions (accept/reject/complete)
- `CompanyAdminController.php` - Company admin specific actions

### **Views:**
- `bookings/index.blade.php` - Booking list with assign buttons
- `bookings/show.blade.php` - Detailed booking view
- `artist/booking-details.blade.php` - Artist view with accept/reject

### **Email Templates:**
- `NewBookingForArtist.php` - Artist assignment notification
- `ArtistAssigned.php` - Customer notification
- `BookingStatusChanged.php` - Generic status change notifications
- `BookingReassigned.php` - Previous artist notification

### **Database Fields:**
```sql
booking_requests:
  - status: enum('pending', 'confirmed', 'completed', 'cancelled')
  - assigned_artist_id: nullable foreign key
  - confirmed_at: nullable timestamp
  - completed_at: nullable timestamp
  - company_notes: text (includes all status change logs)
```

---

## ✅ **Testing Checklist**

- [ ] Customer creates booking → Status is `pending`
- [ ] Admin assigns artist → Status remains `pending`, artist notified
- [ ] Artist accepts → Status changes to `confirmed`, all parties notified
- [ ] Artist rejects → Status stays `pending`, unassigned, admin notified
- [ ] Admin reassigns → Previous artist notified, new artist receives assignment
- [ ] Artist completes → Status changes to `completed`, admin and customer notified
- [ ] Artist cancels confirmed → Status to `pending`, unassigned, admin notified urgently
- [ ] All emails are sent correctly
- [ ] In-app notifications appear
- [ ] Activity logs record all changes

---

**Last Updated:** January 26, 2026
**Version:** 2.0 (Corrected Flow)
