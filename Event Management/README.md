# Event Management System

A complete PHP + MySQL event management platform with admin dashboard, event registration, image uploads, and participant tracking.

## Features

✓ Event listing with thumbnails  
✓ Event details page  
✓ User registration for events  
✓ Participant list tracking  
✓ Admin dashboard  
✓ Event creation with image uploads  
✓ Capacity management  
✓ Input validation & SQL injection prevention (prepared statements)  

## Installation

### Step 1: Database Setup

Import `setup.sql` using one of these methods:

**Using phpMyAdmin:**
1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Click "Import" tab
3. Choose `setup.sql` from this folder
4. Click "Go"

**Using MySQL CLI (PowerShell):**
```powershell
mysql -u root -p < 'c:\xampp\htdocs\Tridib\Event Management\setup.sql'
```

### Step 2: Database Credentials

Edit `config/database.php` and update these if needed:
```php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'tridib_events';
$DB_USER = 'root';
$DB_PASS = '';
```

### Step 3: File Permissions

Ensure the `images/uploads/` folder is writable:
- On Windows/XAMPP this is usually automatic
- If issues arise, right-click folder → Properties → Security → Edit → grant "Modify" permission to your web server user

### Step 4: Verify Setup

Visit http://localhost/Tridib/Event%20Management/setup_test.php to verify everything is configured.

## Usage

### Public Pages

- **Events List**: http://localhost/Tridib/Event%20Management/events.php
- **Event Details**: Click "View details" on any event
- **Register**: Click "Register for this event" on details page
- **Participants**: View who registered for each event

### Admin Pages

**Login**: http://localhost/Tridib/Event%20Management/admin/login.php

Default credentials (change in production!):
- Username: `admin`
- Password: `admin123`

**Admin Dashboard**: Manage events and create new ones with images.

## File Structure

```
Event Management/
├── config/
│   └── database.php           # DB config (edit credentials here)
├── admin/
│   ├── login.php             # Admin login page
│   ├── dashboard.php         # Event management dashboard
│   ├── create_event.php      # Create new event with image
│   └── logout.php            # Logout
├── includes/
│   ├── header.php            # Page header & HTML start
│   └── footer.php            # Page footer & HTML end
├── images/
│   └── uploads/              # Uploaded event images
├── css/
│   └── style.css             # Responsive styles
├── js/
│   └── script.js             # Client-side validation
├── events.php                # List all events
├── event_detail.php          # Show event details
├── register.php              # Event registration form
├── participants.php          # List registered participants
├── index.php                 # Redirect to events.php
├── setup.sql                 # Database schema & sample data
├── setup_test.php            # Setup verification page
└── README.md                 # This file
```

## Database Schema

### `events` table
```sql
CREATE TABLE events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  event_date DATETIME NOT NULL,
  location VARCHAR(255),
  capacity INT DEFAULT 0,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### `registrations` table
```sql
CREATE TABLE registrations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  event_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(50),
  registered_at DATETIME NOT NULL,
  FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);
```

## Security Notes

⚠️ **This is a demo system. Before production use:**

1. **Admin Credentials**: Store in DB with hashed passwords (use `password_hash()`)
2. **CSRF Protection**: Add tokens to all forms
3. **Input Validation**: Already using prepared statements; add server-side regex for specific fields
4. **HTTPS**: Use SSL certificates in production
5. **File Uploads**: Already validating MIME types and size; consider virus scanning
6. **Rate Limiting**: Add login attempt limits and upload rate limiting
7. **Error Messages**: Don't expose database errors to users in production
8. **File Permissions**: Ensure `images/uploads/` is NOT executable as PHP

## Troubleshooting

### "Database connection failed"
- Check MySQL is running (XAMPP Control Panel)
- Verify credentials in `config/database.php`
- Ensure `tridib_events` database exists (run `setup.sql`)

### "Table not found" errors
- Run `setup.sql` again using phpMyAdmin or CLI
- Delete existing database if needed and reimport

### Images not uploading
- Check `images/uploads/` folder exists and is writable
- Verify file size is under 2MB
- Check file type is JPG, PNG, or GIF
- Look for PHP upload errors (check browser console)

### Admin login issues
- Use credentials: username `admin`, password `admin123`
- Clear browser cookies/cache if stuck
- Check browser console for errors

### 404 errors on pages
- Ensure folder is at: `c:\xampp\htdocs\Tridib\Event Management\`
- Access via: http://localhost/Tridib/Event%20Management/events.php (NOT a space in URL)

## API Notes

All pages use:
- **PDO** for database access with prepared statements
- **htmlspecialchars()** for output escaping to prevent XSS
- **Filter validation** for email addresses
- **Server-side image MIME validation** with `getimagesize()`

## Contact & Support

For issues, check:
1. `setup_test.php` for system status
2. Browser console (F12) for client-side errors
3. PHP error logs in XAMPP

---

**Last updated**: November 2025
