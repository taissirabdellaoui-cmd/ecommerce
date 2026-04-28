# Setup Instructions

## 1. Initialize Authentication System

Run this in your browser to set up the database:
```
http://localhost/ecommerce/setup.php
```

This will:
- Add `password` column to client table
- Add `is_admin` column to client table  
- Set password "12345678" for all existing users
- Make User ID 1 (Jean Dupont) an admin

**After running, delete the setup.php file**

## 2. Login Credentials

### Demo User (Customer)
- Email: `jean.dupont@email.com`
- Password: `12345678`

### Demo User (Admin)  
- Email: `jean.dupont@email.com`
- Password: `12345678`
- Goes to: Admin Dashboard

### Other Demo Users
- Email: `marie.l@email.com` (Customer)
- Email: `p.bernard@email.com` (Customer)
- Password: `12345678`

## 3. New Features

✅ **Real Password System**
- All users have password "12345678"
- Passwords are hashed with bcrypt
- New registrations require secure passwords

✅ **Unified Login**
- Single login page for both users and admins
- Customers go to homepage
- Admins go to admin dashboard
- Automatically detects user role

✅ **Fixed Issues**
- Logo now works in admin pages (links to admin/dashboard.php)
- Footer simplified to show only "All rights reserved"
- Admin login removed from admin/dashboard.php
- Session-based admin authentication

## 4. Registration

Users can register new accounts with:
- Name
- Email
- Address  
- Password (any length 6+ characters)
- New users are regular customers by default

## 5. Admin Access

To make any user an admin, run this SQL:
```sql
UPDATE client SET is_admin = 1 WHERE id = 3;
```

## 6. Files to Delete (After Setup)

- `/setup.php` - Setup script
- `/update-images.php` - Old image update script
- `/migrate-auth.php` - Old migration script
