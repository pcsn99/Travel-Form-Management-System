# Travel Form Management System

This is a Laravel-based system designed for managing **community travel requests and forms**.

There are two separate Laravel projects inside this repository:
- `member` → For **Community Members**
- `admin` → For **Admin Management**

---

## System Features

### Member Portal
- Submit travel requests (local or overseas)
- Fill out dynamic travel forms
- Upload documents
- View past requests and forms
- Notifications and account info

### Admin Portal
- Approve or reject travel requests/forms
- Manage questions
- Upload attachments and sign forms
- View calendar of upcoming travels
- See who’s available or traveling
- View community member profiles
- Real-time + email notifications

---

# 🚀 Getting Started (For Developers)

### 1. Clone the Repository 

```bash
git clone https://github.com/pcsn99/Travel-Form-Management-System.git

```

### 2. Create Symlinks 
For LINUX
```bash
ln -s ../../shared admin/public
ln -s ../../shared member/public

```

For Windows
```bash
mklink /D admin\public\ ..\shared
mklink /D member\public\ ..\shared

```

- MAKE SURE YOU ARE IN YOUR OWN BRANCH. SCROLL DOWN AT THE BOTTOM FOR THE BRANCHING GUIDE
---
> Make sure you are in the project folder, either Member or Admin, before running any further commands

---

### 3. Set Up Each Project Individually

```bash
cd member     # or cd admin
```

Then run:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

---

### 4. Set Up Database

1. Create a MySQL database, e.g. `loyola_travel`
2. Update your `.env` file:

```
DB_DATABASE=loyola_travel
DB_USERNAME=root
DB_PASSWORD=your_password
```

3. Run migrations and seed data:

```bash
php artisan migrate --seed
```

This creates:
- Admin (user_id = 1)
- Members 


---

### 5. Serve Locally

```bash
php artisan serve
```

## Default Test Accounts

| Role        | Email             | Password  |
|-------------|-------------------|-----------|
| Admin       | admin@example.com | password  |
| Member 1    | member1@example.com | member123  |
| Member 2    | member2@example.com | member123  |

---

## Frontend Development Guide

- You can edit Blade files in:
  - `/resources/views/` (for pages and components)
- Styling is using:
  - **Bootstrap 5** (CDN)
  - Minimal custom CSS (inline or scoped)


---

## Notifications

- Notifications show in the 🔔 bell icon.
- Admin and member systems use Laravel’s built-in notification system.

---

## Notes
- Projects share the same MySQL database.
- You may run both portals on different ports, or switch while testing.
- Feel free to update layouts, modals, UI components—just don’t remove essential logic!
- If you have concerns or any backend logic you want added, just message me.

---
---
---

## Understanding Laravel Views (For Frontend Team)

Laravel uses **Blade** as its templating engine. Here's how the view system works in a simple way:

---

### Layouts (Think: Master Page)

- Layouts are like the **main template** that wraps around your pages.
- You define things like navbar, sidebar, footer here, so you don’t repeat them on every page.

**Example: `layouts/app.blade.php`**

```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Default Title')</title>
</head>
<body>
    <div class="navbar"> ... </div>

    <div class="content">
        @yield('content') {{-- This is where each page puts its content --}}
    </div>
</body>
</html>
```

---

### Blade Pages (Think: Specific Pages)

- These are individual page files (like dashboard, profile, forms).
- They **extend** a layout and **fill in sections** like content or title.

**Example: `dashboard.blade.php`**

```blade
@extends('layouts.app') {{-- Inherits from the layout --}}

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome to the Dashboard</h1>
@endsection
```

---

### Components (Think: Reusable Mini-Templates)

- If you have a repeated block (like a card or a button), you can make it a **component**.
- This keeps your code clean and reusable.

**Example: A simple alert component**

```blade
{{-- components/alert.blade.php --}}
<div class="alert alert-{{ $type }}">
    {{ $slot }}
</div>
```

You can use it like this in any page:

```blade
<x-alert type="success">
    Travel request submitted successfully!
</x-alert>
```

---

### Summary for Frontend Developers

| Concept     | Meaning                             | Example                            |
|-------------|--------------------------------------|------------------------------------|
| Layouts     | Wrapper template for all pages       | `layouts/app.blade.php`            |
| Sections    | Content blocks you fill in each page | `@section('content')`              |
| Components  | Reusable pieces of HTML             | `<x-alert>...</x-alert>`           |
| `@yield`    | Placeholder inside layout            | `@yield('content')`                |
| `@extends`  | Page inherits from layout            | `@extends('layouts.app')`          |


---
---

##  Git Branching Guide for Front-End Development

This guide explains how to create and manage your own branches when working on the front-end using Git.

---

### Basic Rules

1. **DO NOT work directly on the `main` branch.**
2. **Each member must work on their own branch.**
3. **Commit often with clear, descriptive messages.**
4. **Pull the latest changes before starting new work.**
5. **Push your changes to GitHub regularly.**

---

### How to Create and Work on Your Own Branch

```bash
# 1. Make sure you're in the project directory
cd path/to/your/project

# 2. Pull the latest code from main
git checkout main
git pull origin main

# 3. Create your own branch (replace 'your-name-feature' with something relevant)
git checkout -b your-name-feature

# 4. Start working and commit your changes
git add .
git commit -m "Added XYZ section to dashboard"

# 5. Push your branch to GitHub
git push origin your-name-feature
```

---

### Why Descriptive Commit Messages Matter

Good commit messages help everyone understand:

- What was changed
- Why it was changed
- Who changed it

Examples of good commit messages:

- `"Added modal to edit user account info"`
- `"Fixed form alignment issue in travel request page"`
- `"Updated notification bell layout"`

Bad examples:

- `"stuff"`
- `"fixed"`
- `"edit"`

---

### Merging Your Work (Advanced Step - ask Paul first)

Before merging into `main`, your work will be reviewed and tested. We’ll use Pull Requests on GitHub to do that.

---

