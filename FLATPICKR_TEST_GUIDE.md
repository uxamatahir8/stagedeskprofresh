# Flatpickr Datepicker - Quick Test Guide

## What Was Fixed
The flatpickr datepicker on the booking creation/edit page was not initializing properly. I've fixed this issue by adding custom initialization code that handles timing issues and hidden fields.

## How to Test

### 1. Navigate to Booking Page
- Log in to your dashboard
- Go to Bookings → Create Booking (or Edit an existing booking)

### 2. Test Event Date Field
**Location**: Top of the form

**Steps**:
1. Click on the "Event Date" input field
2. ✅ A calendar popup should appear
3. ✅ You should see today and past dates are disabled
4. ✅ Click on a future date to select it
5. ✅ The date should display in a readable format (e.g., "January 15, 2024")
6. ✅ The actual saved value will be in Y-m-d format (2024-01-15)

### 3. Test Date of Birth Field
**Location**: Customer Details section

**Steps**:
1. Scroll down to "Date of Birth" field
2. Click on the input field
3. ✅ A calendar popup should appear
4. ✅ Select a date of birth
5. ✅ Date should be displayed correctly

### 4. Test Wedding Date Field (Conditional)
**Location**: Wedding Details section (only visible for wedding events)

**Steps**:
1. In the "Event Type" dropdown, select an event that contains "Wedding" in the name
2. ✅ Wedding fields section should become visible
3. ✅ Click on the "Wedding Date" input field
4. ✅ A calendar popup should appear
5. ✅ Past dates should be disabled
6. ✅ Select a future date
7. ✅ Date should be displayed correctly

## Expected Behavior

### Calendar Popup Should Show:
- Month and year at the top
- Grid of dates
- Navigation arrows to change months
- Disabled dates (grayed out, not clickable)
- Today's date highlighted
- Selected date highlighted in blue/primary color

### Date Display Format:
- **User sees**: "January 15, 2024" (readable format)
- **Form submits**: "2024-01-15" (Y-m-d format for database)

### Date Restrictions:
- **Event Date**: Cannot select today or past dates (minimum is tomorrow)
- **Wedding Date**: Cannot select today or past dates (minimum is tomorrow)
- **Date of Birth**: Cannot select dates less than 5 days ago

## If It's Not Working

### Check 1: Browser Console
1. Press `F12` to open Developer Tools
2. Go to "Console" tab
3. Look for any error messages in red
4. Common errors to look for:
   - "flatpickr is not defined"
   - "Cannot read property '_flatpickr'"
   - Any other JavaScript errors

### Check 2: Hard Refresh
1. Press `Ctrl + Shift + R` (Windows) or `Cmd + Shift + R` (Mac)
2. This clears cached JavaScript files
3. Try clicking on date fields again

### Check 3: Verify Script Loading
1. Press `F12` to open Developer Tools
2. Go to "Console" tab
3. Type: `typeof flatpickr` and press Enter
4. Should return: `"function"`
5. If it returns `"undefined"`, the library is not loaded

### Check 4: Network Tab
1. Press `F12` to open Developer Tools
2. Go to "Network" tab
3. Refresh the page
4. Look for these files (should show status 200):
   - vendors.min.css
   - vendors.min.js
   - app.js

## Technical Details (For Developers)

### Files Modified
- **resources/views/dashboard/pages/bookings/manage.blade.php**
  - Added `initializeFlatpickr()` function
  - Added initialization with 100ms delay
  - Added reinitialization when wedding fields appear

### How It Works
1. Page loads, waits 100ms for DOM to be fully ready
2. `initializeFlatpickr()` function runs
3. It finds all elements with `data-provider="flatpickr"`
4. For each element, it:
   - Destroys any existing flatpickr instance
   - Reads configuration from data attributes
   - Creates new flatpickr instance with proper config

### Configuration Applied
```javascript
{
    disableMobile: true,        // Use flatpickr on mobile
    dateFormat: 'Y-m-d',        // Database format
    altInput: true,             // Show user-friendly format
    altFormat: 'F j, Y',        // Display format
    minDate: 'YYYY-MM-DD',      // From data attribute
    maxDate: 'YYYY-MM-DD'       // From data attribute
}
```

## Additional Notes

- Flatpickr library version: **4.6.13**
- Library source: `public/js/vendors.min.js`
- CSS source: `public/css/vendors.min.css`
- Initialization code: `public/js/app.js` (Plugins class)
- Custom initialization: In the blade file itself

## Success Indicators

✅ Calendar popup appears when clicking date fields  
✅ Past dates are properly disabled  
✅ Selected dates display in readable format  
✅ Dates are saved in correct format (Y-m-d)  
✅ No JavaScript errors in console  
✅ Wedding date field works when wedding event type is selected  

## Need Help?

If the datepicker is still not working after following this guide:
1. Check the browser console for errors (F12 → Console tab)
2. Take a screenshot of any error messages
3. Note which specific date field is not working
4. Try in a different browser to see if it's browser-specific

## Files Reference

### Modified Files
- `resources/views/dashboard/pages/bookings/manage.blade.php`

### Related Files (Not Modified)
- `public/js/vendors.min.js` (contains flatpickr library)
- `public/css/vendors.min.css` (contains flatpickr styles)
- `public/js/app.js` (contains base initialization code)

## Summary

The flatpickr datepicker should now work on all three date fields in the booking form:
1. Event Date (always visible)
2. Date of Birth (always visible)
3. Wedding Date (visible only for wedding events)

The fix handles initialization timing and properly reinitializes the datepicker when hidden fields become visible.
