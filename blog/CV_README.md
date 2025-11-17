# 📄 Laravel CV Builder - Complete Professional CV System

**Version**: 1.0.0  
**Framework**: Laravel 11  
**Database**: MySQL 5.7+  
**PHP**: 8.0+

---

## 🎯 Overview

A stunning, fully-featured CV (Resume) management system built with Laravel. Create, manage, and showcase your professional profile with a beautiful, responsive design. Perfect for developers, designers, and professionals who want to showcase their work dynamically.

### ✨ Key Features

- ✅ **Professional CV Display** - Beautiful, modern CV showcase with multiple sections
- ✅ **Complete Admin Dashboard** - Manage all CV sections easily
- ✅ **Dynamic Content Management** - Add/edit/delete experiences, education, skills, projects
- ✅ **Skill Proficiency Bars** - Visual representation of skill levels
- ✅ **Timeline View** - Chronological display of experiences and education
- ✅ **Project Portfolio** - Showcase your best projects with links and tech stack
- ✅ **Community Portfolio** - Accept and moderate submitted portfolio entries from users
- ✅ **Portfolio Moderation** - Admin interface to approve/reject community submissions
- ✅ **Responsive Design** - Perfect on desktop, tablet, and mobile
- ✅ **Social Media Links** - LinkedIn, GitHub, Twitter, Portfolio links
- ✅ **Public/Private Toggle** - Control CV visibility
- ✅ **Modern UI/UX** - Gradient backgrounds, smooth animations, professional layout
- ✅ **Easy Setup** - Simple configuration and database setup

---

## 📋 Table of Contents

1. [Installation](#installation)
2. [Database Setup](#database-setup)
3. [Configuration](#configuration)
4. [Features Guide](#features-guide)
5. [Usage](#usage)
6. [File Structure](#file-structure)
7. [Troubleshooting](#troubleshooting)
8. [Customization](#customization)

---

## 🚀 Installation

### Prerequisites

- PHP 8.0 or higher
- MySQL 5.7+
- Composer
- Laravel 11

### Step 1: Clone/Navigate to Project

```bash
cd blog
```

### Step 2: Install Dependencies

```bash
composer install
npm install
```

### Step 3: Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### Step 4: Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cv_builder
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Create Database

```bash
mysql -u root -p
CREATE DATABASE cv_builder;
exit
```

### Step 6: Run Migrations

```bash
php artisan migrate
```

### Step 7: Start Development Server

```bash
php artisan serve
npm run dev
```

Visit: `http://localhost:8000`

---

## 💾 Database Setup

### Database Tables

The system automatically creates the following tables:

#### 1. **cv_profiles**
Main profile information
```sql
- id (Primary Key)
- full_name (string) - Your name
- title (string) - Professional title (e.g., "Full Stack Developer")
- bio (text) - Short professional summary
- email (string, unique) - Professional email
- phone (string) - Contact number
- location (string) - City/Country
- website_url (url) - Personal website
- linkedin_url (url) - LinkedIn profile
- github_url (url) - GitHub profile
- twitter_url (url) - Twitter profile
- portfolio_url (url) - Portfolio website
- about_me (longtext) - Detailed about section
- profile_photo (string) - Profile picture path
- cv_file (string) - PDF file path
- is_public (boolean) - CV visibility toggle
- created_at, updated_at
```

#### 2. **experiences**
Professional work experience
```sql
- id (Primary Key)
- cv_profile_id (Foreign Key)
- company_name (string) - Company name
- job_title (string) - Job position
- employment_type (string) - Full-time, Part-time, etc
- description (text) - Job description
- location (string) - Work location
- start_date (date) - Start date
- end_date (date, nullable) - End date
- is_current (boolean) - Currently working?
- company_website (string) - Company website
- key_achievements (json) - Array of achievements
- sort_order (integer)
- created_at, updated_at
```

#### 3. **educations**
Educational background
```sql
- id (Primary Key)
- cv_profile_id (Foreign Key)
- school_name (string) - University/College
- degree (string) - Bachelor, Master, PhD, etc
- field_of_study (string) - Major/Specialization
- start_date (date)
- end_date (date, nullable)
- is_current (boolean)
- description (text) - Coursework, activities, etc
- gpa (decimal) - Grade point average
- activities (json) - Array of activities/clubs
- school_website (string)
- sort_order (integer)
- created_at, updated_at
```

#### 4. **skills**
Professional skills
```sql
- id (Primary Key)
- cv_profile_id (Foreign Key)
- skill_name (string) - Skill name
- category (string) - Programming, Design, etc
- proficiency (integer) - 1-100 skill level
- description (text) - Skill description
- sort_order (integer)
- icon_class (string) - Font Awesome icon class
- endorsements (json) - Array of endorsers
- created_at, updated_at
```

#### 5. **projects**
Showcase projects
```sql
- id (Primary Key)
- cv_profile_id (Foreign Key)
- project_name (string)
- description (text) - Short description
- detailed_description (text) - Long description
- project_url (url) - Live project link
- github_url (url) - GitHub repository
- start_date (date)
- end_date (date, nullable)
- is_current (boolean)
- technologies (json) - Array of tech stack
- images (json) - Array of project images
- featured_image (string) - Main project image
- impact (text) - Project impact/results
- sort_order (integer)
- created_at, updated_at
```

---

## ⚙️ Configuration

### 1. Authentication

Edit `config/auth.php` to ensure proper authentication setup:

```php
'defaults' => [
    'guard' => 'web',
    'passwords' => 'users',
],
```

### 2. Database Connection

Ensure your `.env` has correct database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cv_builder
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. App Configuration

Set your app name and timezone in `.env`:

```env
APP_NAME="CV Builder"
APP_TIMEZONE=UTC
```

---

## 🎯 Features Guide

### 1. Public CV Display (`/cv`)

Beautiful public CV display with:
- Hero header with profile information
- About me section
- Professional experience timeline
- Education timeline
- Skills with proficiency bars
- Project portfolio
- Social media links
- Contact information

**Route**: `GET /cv`

### 2. Admin Dashboard (`/admin/cv`)

Comprehensive management interface with:
- Statistics cards (experiences, education, skills, projects)
- Quick access to all sections
- Profile preview
- Recent updates

**Route**: `GET /admin/cv`

**Protected by**: `auth` middleware

### 3. Profile Management (`/admin/cv/profile/edit`)

Edit personal and professional information:
- Full name, title, bio
- Contact information
- Social media links
- Professional summary
- CV visibility toggle

**Routes**:
- `GET /admin/cv/profile/edit` - Show form
- `PUT /admin/cv/profile/update` - Save changes

### 4. Experience Management (`/admin/cv/experiences`)

Manage work experience:
- Add new experiences
- Edit existing roles
- Delete outdated positions
- Add key achievements
- Mark current position

**Routes**:
- `GET /admin/cv/experiences` - List all
- `GET /admin/cv/experiences/create` - Create form
- `POST /admin/cv/experiences` - Store
- `GET /admin/cv/experiences/{id}/edit` - Edit form
- `PUT /admin/cv/experiences/{id}` - Update
- `DELETE /admin/cv/experiences/{id}` - Delete

### 5. Education Management (`/admin/cv/educations`)

Manage educational background:
- Add degrees and certifications
- Track GPA
- Add activities and societies
- Mark current studies

**Routes**:
- `GET /admin/cv/educations` - List all
- `GET /admin/cv/educations/create` - Create form
- `POST /admin/cv/educations` - Store
- `GET /admin/cv/educations/{id}/edit` - Edit form
- `PUT /admin/cv/educations/{id}` - Update
- `DELETE /admin/cv/educations/{id}` - Delete

### 6. Skills Management (`/admin/cv/skills`)

Manage professional skills:
- Add skills with proficiency levels (1-100)
- Organize by category
- Add descriptions
- Track endorsements

**Proficiency Levels**:
- 1-49: Beginner
- 50-69: Intermediate
- 70-89: Advanced
- 90-100: Expert

**Routes**:
- `GET /admin/cv/skills` - List all
- `GET /admin/cv/skills/create` - Create form
- `POST /admin/cv/skills` - Store
- `GET /admin/cv/skills/{id}/edit` - Edit form
- `PUT /admin/cv/skills/{id}` - Update
- `DELETE /admin/cv/skills/{id}` - Delete

### 7. Projects Management (`/admin/cv/projects`)

Showcase your best work:
- Add project details
- Upload featured images
- Add tech stack
- Link to live projects and GitHub
- Add project impact description

**Routes**:
- `GET /admin/cv/projects` - List all
- `GET /admin/cv/projects/create` - Create form
- `POST /admin/cv/projects` - Store
- `GET /admin/cv/projects/{id}/edit` - Edit form
- `PUT /admin/cv/projects/{id}` - Update
- `DELETE /admin/cv/projects/{id}` - Delete

---

## 📱 Usage

### For Users

#### Step 1: Add Profile Information
1. Go to `/admin/cv`
2. Click "Edit Profile"
3. Fill in your personal and professional info
4. Save

#### Step 2: Add Experience
1. Click "Manage" under Professional Experience
2. Click "+ Add"
3. Fill in job details
4. Save

#### Step 3: Add Education
1. Click "Manage" under Education
2. Click "+ Add"
3. Fill in education details
4. Save

#### Step 4: Add Skills
1. Click "Manage" under Skills & Expertise
2. Click "+ Add"
3. Enter skill name, category, and proficiency
4. Save

#### Step 5: Add Projects
1. Click "Manage" under Featured Projects
2. Click "+ Add"
3. Fill in project details
4. Save

#### Step 6: Share Your CV
Share the URL: `http://yoursite.com/cv`

### For Developers

#### Access Models

```php
use App\Models\CVProfile;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Skill;
use App\Models\Project;

// Get main CV
$cv = CVProfile::first();

// Get relationships
$experiences = $cv->experiences;
$educations = $cv->educations;
$skills = $cv->skills;
$projects = $cv->projects;

// Get specific skills by category
$programmingSkills = $cv->getSkillsByCategory('Programming');

// Get years of experience
$yearsExp = $cv->getYearsOfExperience();
```

#### Access Controllers

All CRUD controllers are available in `app/Http/Controllers/Admin/`

---

## 📁 File Structure

```
blog/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── CVController.php              (Public CV display)
│   │       └── Admin/
│   │           ├── CVProfileController.php   (Profile management)
│   │           ├── ExperienceController.php  (Experience CRUD)
│   │           ├── EducationController.php   (Education CRUD)
│   │           ├── SkillController.php       (Skill CRUD)
│   │           └── ProjectController.php     (Project CRUD)
│   └── Models/
│       ├── CVProfile.php
│       ├── Experience.php
│       ├── Education.php
│       ├── Skill.php
│       └── Project.php
│
├── database/
│   └── migrations/
│       ├── *_create_cv_profiles_table.php
│       ├── *_create_experiences_table.php
│       ├── *_create_educations_table.php
│       ├── *_create_skills_table.php
│       └── *_create_projects_table.php
│
├── resources/
│   └── views/
│       ├── cv/
│       │   ├── show.blade.php         (Public CV display)
│       │   └── preview.blade.php      (CV preview)
│       └── admin/
│           └── cv/
│               ├── dashboard.blade.php
│               ├── edit-profile.blade.php
│               ├── experience-form.blade.php
│               ├── education-form.blade.php
│               ├── skill-form.blade.php
│               └── project-form.blade.php
│
└── routes/
    └── cv.php                         (CV-specific routes)
```

---

## 🔧 Troubleshooting

### Issue: Database connection error

**Solution**: Check `.env` file:
```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: Migrations not running

**Solution**:
```bash
php artisan migrate:refresh
php artisan migrate
```

### Issue: 404 errors on CV pages

**Solution**:
```bash
php artisan route:cache
php artisan route:clear
```

### Issue: Assets not loading

**Solution**:
```bash
npm run build
php artisan optimize
```

### Issue: Storage path not found

**Solution**:
```bash
php artisan storage:link
```

---

## 🎨 Customization

### Change Colors

Edit the CSS in `resources/views/cv/show.blade.php`:

```css
:root {
    --primary: #2563eb;      /* Change to your color */
    --secondary: #1e40af;
    --accent: #0891b2;
}
```

### Change Fonts

Edit in Blade templates or `resources/css/app.css`:

```css
body {
    font-family: 'Your Font', sans-serif;
}
```

### Add New Sections

1. Create a new migration for the new table
2. Create a Model class
3. Add relationship in CVProfile model
4. Create Controller for CRUD
5. Add routes
6. Create views

### Custom Themes

The CV display uses inline styles. You can:
- Create alternate views
- Use CSS variables for theming
- Add dark mode support

---

## 📊 API Reference

### CVProfile Model

```php
// Methods
$cv->getYearsOfExperience()        // Returns: int
$cv->getSkillsByCategory($cat)    // Returns: Collection
$cv->getSkillCategories()         // Returns: array
$cv->getTotalProjects()           // Returns: int
$cv->getCurrentExperience()       // Returns: Experience|null

// Relationships
$cv->experiences()    // HasMany
$cv->educations()     // HasMany
$cv->skills()         // HasMany
$cv->projects()       // HasMany
```

### Experience Model

```php
// Attributes
$exp->display_date    // "Jan 2020 - Present"
$exp->duration        // "3y 2m"

// Methods
$exp->getDisplayDateAttribute()
```

### Skill Model

```php
// Attributes
$skill->proficiency_level    // "Expert", "Advanced", etc

// Methods
$skill->addEndorsement($person)
```

---

## 🔐 Security Considerations

1. **Authentication**: Admin routes are protected by `auth` middleware
2. **Validation**: All inputs are validated
3. **Authorization**: Consider adding authorization policies for multi-user
4. **CSRF**: Laravel CSRF protection enabled by default
5. **SQL Injection**: Using Eloquent ORM prevents SQL injection
6. **XSS**: Use Blade's `{{ }}` for auto-escaping

---

## 📝 Sample Data

To seed sample data (modify as needed):

```php
$cv = CVProfile::create([
    'full_name' => 'John Doe',
    'title' => 'Full Stack Developer',
    'email' => 'john@example.com',
    'phone' => '+1234567890',
    'location' => 'New York, USA',
    'bio' => 'Passionate developer',
]);

$cv->experiences()->create([
    'company_name' => 'Tech Corp',
    'job_title' => 'Senior Developer',
    'start_date' => '2020-01-01',
    'is_current' => true,
]);

$cv->skills()->create([
    'skill_name' => 'Laravel',
    'category' => 'Programming',
    'proficiency' => 95,
]);
```

---

## 🚀 Deployment

### Before Deployment

1. Set `APP_DEBUG=false` in `.env`
2. Optimize Laravel:
   ```bash
   php artisan optimize
   php artisan config:cache
   ```
3. Build frontend:
   ```bash
   npm run build
   ```
4. Run migrations on server:
   ```bash
   php artisan migrate --force
   ```

### Production `.env`

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
DB_CONNECTION=mysql
DB_HOST=your_host
DB_DATABASE=cv_db
DB_USERNAME=cv_user
DB_PASSWORD=strong_password
```

---

## 📞 Support

For issues or questions:
1. Check the Troubleshooting section
2. Review Laravel documentation
3. Check migrations and database schema

---

## 📄 License

This project is open source and available under the MIT License.

---

## 🎉 Features Showcase

### Beautiful Design
- Modern gradient backgrounds
- Smooth animations and transitions
- Professional color scheme
- Responsive layouts

### Easy Management
- Intuitive admin interface
- Quick-add buttons
- Inline editing
- Bulk operations

### Rich Content
- Timeline views
- Skill progress bars
- Project showcase
- Social media integration

### Performance
- Optimized queries
- Pagination support
- Image optimization
- Fast load times

---

**Enjoy your new CV system!** 🚀

For updates and more features, check back regularly.

---

**Created with ❤️ using Laravel 11**
