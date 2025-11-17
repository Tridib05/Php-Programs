# OnlineVoting - Simple PHP/MySQL Voting App

Setup

1. Create the database and tables: run the SQL in `database/setup.sql` using phpMyAdmin or `mysql` CLI.
2. Update DB credentials in `config/database.php` if needed.
3. Place the `OnlineVoting` folder under your web root (e.g. `c:\xampp\htdocs\Tridib\OnlineVoting`).
4. Open `http://localhost/Tridib/OnlineVoting/` in your browser.

Usage

- Register a user on `register.php`.
- Login via `login.php`.
- Visit `vote.php` to cast a single vote (each user can only vote once).
- View results on `results.php`.

Notes

- The `votes` table has a unique constraint on `user_id` to ensure one vote per user.
- This is a minimal demo intended for local use; do not use in production without adding CSRF tokens, stronger session protections, and rate limiting.

Admin
 - Admin

 - After creating users, promote an account to admin using `make_admin.php` (enter the user's email). An admin can add/edit/delete candidates via `admin_candidates.php`.
 - For a fresh install, run `database/setup.sql` first, register a user, then visit `make_admin.php` to make that user an admin.
 - Candidate photos: admin can upload an optional image (jpg/png/gif) up to 2MB. Uploaded images are saved into `uploads/`.

Security Notes

 - CSRF protection is included for forms via a session token. Keep PHP sessions secure and enable HTTPS for production.
Database migration notes
 The app stores uploaded candidate photos in `OnlineVoting/uploads/`. If you move the folder, update paths accordingly.

 Database migration notes

 If you already created the `users` table before, run:
 ```sql
 ALTER TABLE users ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0;
 ```
 If you created `candidates` without a `photo` column, run:
 ```sql
 ALTER TABLE candidates ADD COLUMN photo VARCHAR(255) NULL;
 ```

 What I added for you

 - Assets: `assets/style.css` provides simple layout and thumbnails. Linked in `includes/header.php`.
 - Uploads: `uploads/` folder created; admin photo upload implemented (`admin_candidates.php`, `admin_edit_candidate.php`), and deletes removed files on candidate delete.
 - CSRF: `includes/csrf.php` and `csrf_input()` used across forms.
 - Session & security: `includes/session.php` sets cookie params, regenerates session id on login, and simple login attempt limits.
 - Charts: `results.php` shows a Chart.js bar chart with vote counts.

 If you'd like, I can now:

 - Add nicer UI (thumbnails, spacing) and optional candidate avatars display on mobile.
 - Add email-based password reset flow.
 - Export results to CSV for offline reporting.

 Choose one and I'll implement it next.
