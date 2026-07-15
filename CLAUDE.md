# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Lavalite CMS — a Laravel 5.x-based content management system built on the `lavalite/framework` package ecosystem. The app layer in `app/` is thin; most CMS logic lives in Composer packages under `vendor/lavalite/` and `vendor/litepie/`.

## Commands

```bash
# Install dependencies
composer install
npm install
bower install  # Installs into vendor/bower_components/

# Set up environment
cp .env.example .env
php artisan key:generate

# Database setup (SQLite by default in .env.example)
php artisan migrate --seed

# Development server
php artisan serve

# Build frontend assets (concatenates CSS/JS from bower_components)
gulp

# Run tests
vendor/bin/phpunit

# Run a single test file
vendor/bin/phpunit tests/TestHomePage.php

# Clear caches after config changes
php artisan clear-compiled && php artisan optimize
```

## Architecture

### Request flow

Routes in `app/Http/routes.php` dispatch to one of several controller types, each corresponding to a user context:

| Controller | Context |
|---|---|
| `PublicWebController` / `PublicApiController` | Unauthenticated public visitors |
| `UserWebController` / `UserApiController` | Authenticated internal users |
| `ClientWebController` / `ClientApiController` | Authenticated client-portal users |
| `AdminWebController` / `AdminApiController` | Admin dashboard |

### Theming system

Views are **not** in `resources/views/`. They are loaded from `public/themes/{theme}/views/` at runtime. The `AppServiceProvider` registers two view namespaces:

- `admin::` → `public/themes/admin/views/`
- `public::` → `public/themes/public/views/`

Theme configuration lives in `config/theme.php`. The four contexts (admin, public, user, client) each map to a theme + layout defined there.

### User & role model

Two separate authenticatable models exist: `App\User` (internal users) and `App\Client` (client portal). Both use role-based permissions via `Litepie\User` from the framework package. Roles are configured in `config/user.php` — the `superuser` role bypasses all permission checks. Default credentials seeded: `superuser@superuser.com` / `superuser@superuser`.

### Frontend assets

Assets are managed with **Bower** (not npm) and compiled with **Gulp + Laravel Elixir**. `gulpfile.js` concatenates bower components into:

- `public/css/vendor_admin.css` and `public/js/vendor_admin.js`
- `public/css/vendor_public.css` and `public/js/vendor_public.js`

Custom app JS lives in `public/js/admin.js` and `public/js/public.js`. The `DOMPurify` npm package is included to sanitize HTML (added to address a code scanning alert for XSS via library input).

### Packages

Core CMS features (pages, settings, tasks, messages, calendar) are pulled in as Composer packages from `lavalite/*`. They register their own routes, migrations, and service providers automatically via the framework's package loader. The `packages/` directory in the repo root is for local package development (currently empty).

### Database

Default driver is SQLite (`storage/database.sqlite`). Switch to MySQL by updating `.env`. Migrations cover: users, clients, roles, permissions, pages, menus, settings, tasks, messages, calendars, and revisions.
