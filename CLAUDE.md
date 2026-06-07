# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What This App Is

A wedding invitation management system. Guests receive personalized magic-link invitations and RSVP through a public-facing form. Admins manage invitations through a separate protected dashboard.

## Commands

```bash
# Full dev environment (server + queue + logs + vite)
composer run dev

# First-time setup
composer run setup

# Run tests
php artisan test --compact

# Lint (auto-fix)
vendor/bin/pint --dirty --format agent

# Run a single test
php artisan test --compact --filter=testName
```

## Architecture

### Dual Authentication

The app has **two separate auth guards**:

- `web` guard — Fortify-powered auth for `User` model; used for account settings (`/settings/*`)
- `admin` guard — custom session auth for `Admin` model; used for the admin panel (`/admin/*`)

Admin login/logout is handled by `LoginController`, not Fortify. The `Admin` model is a separate Eloquent `Authenticatable` — not related to `User`.

Seed a default admin: `php artisan db:seed --class=AdminSeeder` (email: `admin@wedding.com`, password: `password`).

### Route Files

- `routes/web.php` — public invitation & RSVP routes (no auth)
- `routes/admin.php` — admin dashboard routes (`auth:admin` middleware)
- `routes/settings.php` — user account settings (Fortify `auth` guard)

### Core Domain Flow

1. Admin creates an `Invitation` via `app/Livewire/Admin/CreateInvitation.php`
2. Each invitation gets a unique `magic_link_token` (64-char random string) and `access_code`
3. `SendInvitationEmail` job dispatches `InvitationMail` to the guest
4. Guest clicks the magic link `/i/{token}` → `InvitationController@show` → RSVP form
5. Guest submits RSVP → `RsvpController@store` → updates `guests` records + fires `SendConfirmationToAdmins` job
6. Automated reminders: `reminders:send-first` and `reminders:send-second` Artisan commands dispatch `SendReminderEmail` jobs

### Key Models

- `Invitation` — one per family/group; has status (`pending`/`confirmed`/`declined`), magic link token, reminder tracking fields, and `max_guests`
- `Guest` — belongs to Invitation; `is_primary` marks the main contact; `attending` boolean; `position` for ordering
- `NotificationLog` — audit trail for emails sent to an invitation
- `Admin` — separate from `User`; only used for the admin panel

### Admin Livewire Components

All admin UI is in `app/Livewire/Admin/`:
- `InvitationsList` — paginated list with search and status filter
- `InvitationDetail` — view/edit a single invitation and its guests
- `CreateInvitation` — create new invitation form

### Email / Queue

Three mailable jobs in `app/Jobs/`: `SendInvitationEmail`, `SendReminderEmail`, `SendConfirmationToAdmins`. Email templates are in `resources/views/emails/`. The app uses Resend (`resend/resend-laravel`) for transactional email.

### Layouts

- `layouts/app.blade.php` — authenticated user layout (Flux UI sidebar)
- `layouts/admin.blade.php` — admin panel layout
- `layouts/auth.blade.php` — login/register pages
- `resources/views/public/` — unauthenticated guest-facing pages (landing, invitation form, success)
