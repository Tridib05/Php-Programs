# Event Management System - Getting Started Checklist

## ✅ Step 1: Import Database (Required First)

Choose one method:

### Option A: Using phpMyAdmin (Recommended for XAMPP)
1. Open http://localhost/phpmyadmin
2. Click "Import" tab
3. Click "Choose File" 
4. Select: `c:\xampp\htdocs\Tridib\Event Management\setup.sql`
5. Click "Go"
6. You should see success message

### Option B: Using MySQL Command Line
Open PowerShell and run:
```powershell
cd 'c:\xampp\htdocs\Tridib\Event Management'
mysql -u root < setup.sql
```

---

## ✅ Step 2: Verify System Setup

Visit: http://localhost/Tridib/Event%20Management/setup_test.php

All items should show **✓ OK**:
- [ ] Database connected successfully
- [ ] events table exists
- [ ] registrations table exists  
- [ ] images/uploads folder is writable
- [ ] All required files present
- [ ] Events in database (should show at least 2 sample events)

If any items show **✗ FAIL**, refer to README.md troubleshooting section.

---

## ✅ Step 3: Test Public Features

### View Events List
Visit: http://localhost/Tridib/Event%20Management/events.php

You should see:
- [ ] At least 2 sample events displayed
- [ ] Event titles visible
- [ ] Event images showing (if imported sample data)
- [ ] "View details" links for each event

### View Event Details
Click "View details" on any event

You should see:
- [ ] Full event title, date, location
- [ ] Event image (if available)
- [ ] Event description
- [ ] "Register for this event" button
- [ ] "View participants" link

### Register for Event
Click "Register for this event"

You should see:
- [ ] Registration form with Name, Email, Phone fields
- [ ] "Submit registration" button
- [ ] Form validation when submitting

Fill in the form:
- [ ] Name: (enter your name)
- [ ] Email: (enter your email)
- [ ] Phone: (optional)
- [ ] Click "Submit registration"

You should see:
- [ ] Success message
- [ ] Link back to event details

### View Participants
Go back to event details and click "View participants"

You should see:
- [ ] Your registration listed in the table
- [ ] Columns: #, Name, Email, Phone, Registered At
- [ ] Your data shows correctly

---

## ✅ Step 4: Test Admin Features

### Admin Login
Visit: http://localhost/Tridib/Event%20Management/admin/login.php

Enter credentials:
- Username: **admin**
- Password: **admin123**
- Click "Login"

You should see:
- [ ] Admin Dashboard page
- [ ] "Create new event" link
- [ ] Table of existing events
- [ ] "Logout" link

### Create Event
Click "Create new event"

You should see:
- [ ] Form with fields: Title, Description, Date & Time, Location, Capacity
- [ ] Image upload field (optional)

Fill in and submit:
- [ ] Title: "Test Event"
- [ ] Description: "Testing the admin create event feature"
- [ ] Date & Time: (any future date/time)
- [ ] Location: "Test Location"
- [ ] Capacity: 100
- [ ] Image: (optional - choose a JPG/PNG under 2MB)
- [ ] Click "Create"

You should see:
- [ ] Success message "Event created successfully"
- [ ] New event appears in dashboard list

### Logout
Click "Logout"

You should be:
- [ ] Redirected to login page
- [ ] Session cleared

---

## ✅ Step 5: Test Images (If Uploaded)

### Check Upload Location
Navigate to: `c:\xampp\htdocs\Tridib\Event Management\images\uploads\`

You should see:
- [ ] Any image files you uploaded (with names like `1234567890_abc123def.jpg`)
- [ ] Files are readable but NOT executable

### View Images in Browser
From events.php or event details:
- [ ] Images display as thumbnails or full-size
- [ ] Images load properly

---

## ✅ Step 6: (Optional) Create Sample Data

To get more practice, in phpMyAdmin run this SQL:

```sql
INSERT INTO events (title, description, event_date, location, capacity) VALUES
('Web Design Workshop', 'Learn modern web design principles', DATE_ADD(NOW(), INTERVAL 7 DAY), 'Room 101', 30),
('Networking Dinner', 'Meet industry professionals', DATE_ADD(NOW(), INTERVAL 14 DAY), 'Hotel Restaurant', 50),
('Coding Bootcamp', 'Intensive 3-day coding program', DATE_ADD(NOW(), INTERVAL 21 DAY), 'Tech Center', 25);
```

Then test registration on these events.

---

## ✅ All Done! 🎉

Your Event Management System is now fully functional!

### What You Can Do:
- ✅ View all events with images
- ✅ Register for events
- ✅ See participant lists
- ✅ Admin panel to create events with images

### Next Steps (Optional):
1. **Customize**: Edit `css/style.css` to match your brand colors
2. **Add More Events**: Use admin panel or database directly
3. **Secure It**: Change admin password in production
4. **Deploy**: Upload to a live server with HTTPS

### Quick Links:
- **Events**: http://localhost/Tridib/Event%20Management/events.php
- **Admin**: http://localhost/Tridib/Event%20Management/admin/login.php
- **Docs**: See README.md for full documentation
- **Help**: Run setup_test.php if you encounter issues

---

**Enjoy your Event Management System!** 🚀

Any issues? Check COMPLETION_REPORT.md or README.md troubleshooting section.
