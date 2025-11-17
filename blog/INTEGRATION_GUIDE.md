# Portfolio & CV Integration Guide

## Overview

The Laravel CV Builder now features a **complete portfolio integration system** that merges:
- **Direct projects** (created by admins in the dashboard)
- **Community portfolio entries** (submitted via the public portfolio form)
- **Historical portfolio entries** (stored in `storage/app/portfolio.json`)

All data flows through a single unified system with moderation, approval workflows, and beautiful public display.

---

## Architecture

### Database Tables

**cv_profiles** - Main CV data
- `id`, `full_name`, `title`, `bio`, `email`, `phone`, `location`
- Social links, profile photo, about_me

**projects** - All projects (unified table)
- Core fields: `project_name`, `description`, `project_url`, `github_url`, `start_date`, `end_date`
- Portfolio tracking:
  - `submitted_by` (name of community contributor)
  - `is_approved` (boolean - shows on public page)
  - `submission_type` (enum: 'direct' or 'community')
  - `submission_email` (contact from submission)
  - `submission_website` (website from submission)

### Flow

```
1. User submits form at / (root)
   ↓
2. PortfolioController@store saves:
   - To storage/app/portfolio.json (backward compat)
   - To projects table with submission_type='community', is_approved=true
   ↓
3. Public displays at / or /cv:
   - Admin projects (direct entries)
   - Database community projects (where is_approved=true)
   - JSON entries (for backward compat)
   ↓
4. Admin can moderate at /admin/cv/portfolio:
   - View submissions
   - Approve/Reject entries
   - Delete entries
   - View statistics
```

---

## Setup & Migration

### Step 1: Run New Migration

```bash
php artisan migrate
```

This creates the new fields on the `projects` table:
- `submitted_by`
- `is_approved`
- `submission_type`
- `submission_email`
- `submission_website`

### Step 2: Import Existing Portfolio Entries (Optional)

If you have existing entries in `storage/app/portfolio.json`, import them:

```bash
php artisan portfolio:import
# or with specific CV profile:
php artisan portfolio:import --cv-id=1
```

This command:
- Reads `portfolio.json`
- Converts each entry to a project record
- Sets `submission_type='community'` and `is_approved=true`
- Prevents duplicate imports

---

## Usage

### For End Users (Public Submission)

1. Navigate to `/` (site root)
2. Fill in portfolio entry form:
   - **Name** (required): Your name
   - **Title** (optional): Your role/position
   - **Bio** (optional): Description
   - **Email** (optional): Contact
   - **Website** (optional): Your site
3. Click "Add"
4. Entry is saved and displays on `/cv` immediately

### For Admin (Moderation & Management)

#### Dashboard
- Navigate to `/admin/cv`
- Click "Portfolio" or go to `/admin/cv/portfolio`
- See all community submissions

#### Approve/Reject
- **Approve**: Makes entry visible on public CV
- **Reject**: Hides entry from public (marks `is_approved=false`)
- **Delete**: Permanently removes entry

#### Statistics
- Click "📊 View Stats" or go to `/admin/cv/portfolio/stats`
- See totals, approval rates, quality metrics

#### Create Direct Entries
- Use `/admin/cv/projects/create` to add admin-only projects
- These have `submission_type='direct'` and always show

---

## Key Features

### 1. Dual Storage (Backward Compatibility)
- Submissions saved to **both** `portfolio.json` AND database
- Allows gradual migration
- Historical entries preserved

### 2. Moderation Workflow
- Community submissions default to `is_approved=true`
- Can reject entries to hide from public
- Approve/Reject toggle in admin UI

### 3. Public Display
- Only approved entries show (`is_approved=true`)
- Direct (admin) projects always show
- Beautiful responsive grid layout

### 4. Tracking
- Know who submitted each entry (`submitted_by`, `submission_email`)
- When it was submitted (`created_at`)
- Track submission source (`submission_type`)

---

## File Locations

### New Files Created

```
app/Console/Commands/
  └── ImportPortfolioEntries.php          Artisan command to import JSON

app/Http/Controllers/Admin/
  └── PortfolioModerationController.php   Admin moderation logic

resources/views/admin/cv/
  ├── portfolio-moderation.blade.php      Moderation UI
  └── portfolio-stats.blade.php           Stats dashboard

database/migrations/
  └── 2025_11_17_000006_add_portfolio_*   New fields migration
```

### Modified Files

```
app/Http/Controllers/PortfolioController.php     Now saves to both JSON + DB
app/Models/Project.php                           Added fillable fields
routes/cv.php                                    Added portfolio routes
resources/views/cv/show.blade.php                Display community entries
resources/views/welcome.blade.php                Added email field to form
```

---

## Routes

### Public Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/` | Show CV + portfolio form |
| GET | `/cv` | Show public CV |
| GET | `/cv-preview` | Preview mode |
| POST | `/portfolio` | Submit portfolio entry |

### Admin Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/admin/cv/portfolio` | View all submissions |
| GET | `/admin/cv/portfolio/stats` | Statistics |
| POST | `/admin/cv/portfolio/{id}/approve` | Approve entry |
| POST | `/admin/cv/portfolio/{id}/reject` | Reject entry |
| DELETE | `/admin/cv/portfolio/{id}` | Delete entry |

---

## Admin Moderation Interface

### Moderation Page (`/admin/cv/portfolio`)

**Features:**
- Table of all community submissions
- Sort by status (Approved/Rejected)
- Inline approve/reject/delete buttons
- Email and website clickable links
- Submission date tracking
- Pagination (10 per page)

**Quick Stats:**
- Total submissions
- Approved count
- Rejected count
- Link to statistics

### Statistics Page (`/admin/cv/portfolio/stats`)

**Shows:**
- Total projects (admin + community)
- Admin-created projects
- Community submissions
- Approval rate (%)
- Portfolio quality score (%)
- Visual progress bars

---

## Artisan Commands

### Import Historical Entries

```bash
php artisan portfolio:import
```

**Options:**
```
--cv-id=1    (default: 1) CV profile ID to attach projects to
```

**Output:**
```
📂 Found 5 portfolio entries. Importing...
✅ Imported: John's Project
⏭️  Skipped: Already imported entry
✅ Imported: Jane's Portfolio
Import Complete!
✅ Imported: 2
⏭️  Skipped: 3
Total: 5 entries processed
```

---

## Database Schema

### projects Table (New Fields)

```sql
ALTER TABLE projects ADD COLUMN submitted_by VARCHAR(255) NULL;
ALTER TABLE projects ADD COLUMN is_approved BOOLEAN DEFAULT true;
ALTER TABLE projects ADD COLUMN submission_type ENUM('direct', 'community') DEFAULT 'direct';
ALTER TABLE projects ADD COLUMN submission_email VARCHAR(255) NULL;
ALTER TABLE projects ADD COLUMN submission_website VARCHAR(255) NULL;
```

### Example Queries

**Get all approved community entries:**
```php
$entries = Project::where('submission_type', 'community')
    ->where('is_approved', true)
    ->get();
```

**Get pending community entries:**
```php
$pending = Project::where('submission_type', 'community')
    ->where('is_approved', false)
    ->get();
```

**Get all admin projects:**
```php
$admin = Project::where('submission_type', 'direct')->get();
```

---

## Workflow Examples

### Example 1: Submit Portfolio Entry

```
User visits /
Fills form:
  - Name: "Alice Cooper"
  - Title: "Web Developer"
  - Bio: "I build awesome websites"
  - Email: alice@example.com
  - Website: alice.dev
Clicks "Add"

System:
  1. Validates form
  2. Saves to storage/app/portfolio.json
  3. Creates Project record:
     - project_name: "Alice Cooper"
     - description: "Web Developer"
     - submission_type: "community"
     - is_approved: true
     - submitted_by: "Alice Cooper"
     - submission_email: "alice@example.com"
     - submission_website: "alice.dev"
  4. Redirects with success message
  5. Entry shows on /cv immediately
```

### Example 2: Moderate Submission

```
Admin goes to /admin/cv/portfolio
Sees table of submissions
Finds "Bob's Portfolio" - decides it's spam
Clicks "Reject"

System:
  1. Sets is_approved: false
  2. Entry hidden from public /cv
  3. Shows success: "'Bob's Portfolio' has been rejected"
  4. Admin still sees it (for record-keeping)
```

### Example 3: Import Historical Entries

```
Admin has old portfolio.json with 10 entries
Runs: php artisan portfolio:import

System:
  1. Reads portfolio.json
  2. For each entry:
     - Checks if already imported (by name + submitter)
     - Creates Project record with submission_type='community'
     - Sets is_approved=true
  3. Shows summary:
     - ✅ Imported: 8 entries
     - ⏭️  Skipped: 2 (already in DB)
  4. All entries now on public /cv
```

---

## Customization

### Change Default Approval Status

In `PortfolioController@store`, modify:
```php
'is_approved' => false,  // Require admin approval
```

### Disable JSON Storage

Remove this section from `PortfolioController@store`:
```php
// Also save to JSON for backward compatibility
$json = Storage::exists('portfolio.json') ? ...
```

### Custom Moderation Email

Add after approval/rejection in `PortfolioModerationController`:
```php
Mail::to($project->submission_email)
    ->send(new PortfolioApprovedMail($project));
```

### Display Pending in Public

Modify `cv/show.blade.php` to show rejected/pending entries separately:
```blade
@php
    $pending = $cv->projects->where('submission_type', 'community')
                           ->where('is_approved', false);
@endphp
```

---

## Troubleshooting

### "Table 'projects' doesn't have column 'submitted_by'"

**Solution:** Run migrations:
```bash
php artisan migrate
```

### Portfolio entries not showing on /cv

**Check:**
1. Is CV profile created? (`CVProfile::first()`)
2. Are entries in database? Query: `Project::all()`
3. Are they approved? Check `is_approved=true`
4. Check browser console for JavaScript errors

**Debug:**
```bash
php artisan tinker
>>> Project::where('submission_type', 'community')->get()
```

### Import command shows "CVProfile not found"

**Solution:**
1. Create a CV profile first:
   ```bash
   php artisan tinker
   >>> App\Models\CVProfile::create(['full_name' => 'Main CV', 'title' => 'Portfolio'])
   ```
2. Then run import with correct CV ID:
   ```bash
   php artisan portfolio:import --cv-id=1
   ```

### JSON file keeps growing

**Solution:** After migrating all entries to database, delete `storage/app/portfolio.json` and remove the JSON save code from `PortfolioController`.

---

## Performance Tips

### Optimize Public Display

In `CVController@show`, eager load community projects:
```php
$cv = CVProfile::with([
    'experiences',
    'educations',
    'skills',
    'projects' => function ($query) {
        $query->where('is_approved', true);
    }
])->first();
```

### Index Database Columns

Add indexes for common filters:
```php
Schema::table('projects', function (Blueprint $table) {
    $table->index('submission_type');
    $table->index('is_approved');
    $table->index('submitted_by');
});
```

---

## Next Steps

1. ✅ Run migration: `php artisan migrate`
2. ✅ Import historical entries: `php artisan portfolio:import`
3. ✅ Visit `/admin/cv/portfolio` to moderate
4. ✅ Test public submission at `/`
5. ✅ Customize approval workflow as needed

---

## Support

For issues or questions, check:
- Detailed routes in `routes/cv.php`
- Model relationships in `app/Models/Project.php`
- View logic in `resources/views/cv/show.blade.php`
- Admin UI in `resources/views/admin/cv/portfolio-moderation.blade.php`

