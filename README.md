# Loyola Travel Request System

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

## 🚀 Getting Started (For Developers)

### 1. Clone the Repository

```bash
git clone https://github.com/your-team/loyola-travel-system.git
cd loyola-travel-system
```

> Replace the above URL with your actual repo link.

---

### 2. Set Up Each Project Individually

```bash
cd member-portal     # or cd admin-portal
```

Then run:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

---

### 3. Set Up Database

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
- Members (user_id = 2 and 3)
- Sample travel requests and forms

---

### 4. Serve Locally

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
