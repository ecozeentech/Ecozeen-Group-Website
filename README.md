# Ecozeen Group — Website, CMS & Admin Dashboard

A Laravel 13 / PHP 8.3 application that serves the public Ecozeen Group corporate
website and a custom, section-based content management system and admin
dashboard. Marketing copy, pages, navigation, theme styling, SEO metadata,
legal content, and the company/product/service/project directories are all
editable from `/admin` — no code changes or deployments required for routine
content updates.

## Stack

- **Backend:** Laravel 13, PHP 8.3
- **Database:** MySQL 8
- **Frontend:** Blade, Tailwind CSS, Alpine.js, Livewire
- **Authorization:** [Spatie laravel-permission](https://spatie.be/docs/laravel-permission) (role/permission based RBAC)
- **Testing:** PHPUnit (Feature + Unit), SQLite in-memory for the test run
- **Code style:** [Laravel Pint](https://laravel.com/docs/pint)

## Features

- **Section-based page builder** — 21+ reusable section types (hero, rich
  text, image/text, galleries, collections, FAQs, stats, testimonials, CTAs,
  forms, etc.), each with a server-side schema in
  `app/Support/PageBuilder/SectionTypeRegistry.php`. Unknown fields are
  stripped and rich text is purified (`stevebauman/purify`) before anything is
  persisted or rendered — the browser is never trusted.
- **Revisions** — every settings/content update snapshots the previous page
  state (page attributes + ordered sections) to `content_revisions`, with a
  one-click restore.
- **Duplicate / publish / unpublish** workflows, homepage exclusivity (only
  one page can be the homepage at a time), and scheduled publishing for
  pages and blog posts.
- **Centralized theme settings** — brand colors, fonts, spacing, and
  container width are stored in the database and rendered as CSS custom
  properties (`resources/views/components/theme-variables.blade.php`); the
  Tailwind config consumes the same variables, and only an explicit allow-list
  of values is ever accepted (`ThemeSetting::allowedOptions()`).
- **Full admin dashboard** for companies, products, services, projects (with
  categories/tags), blog, global/reusable sections, navigation menus, job
  listings & applications, contact inquiries, newsletter subscribers,
  redirects, media, website/theme settings, users, roles, and an activity
  log.
- **RBAC** — 8 seeded roles (Super Administrator, Administrator, Content
  Manager, Editor, Author, SEO Manager, Inquiry Manager, Read-only Analyst),
  enforced via Laravel Policies and Spatie permissions. Super Administrators
  cannot delete their own account or grant themselves additional roles.
- **Public inbound forms** — contact, newsletter signup, and job
  applications, each protected by an invisible honeypot field and an
  explicit consent checkbox. Job application resumes (PDF only) are stored on
  a **private** disk and are only ever downloadable through an
  authenticated, policy-checked admin route — never via a public URL.
- **Technical SEO** — per-page/entity meta title/description, canonical URL,
  `noindex` flag, OG image, an auto-generated `sitemap.xml`, and a
  `robots.txt` driven by a site-wide search-indexing toggle.
- **Accurate, non-fabricated seed data** for the real Ecozeen Group entities
  (Ecozeen Swap, Transvoo, AccountSwift, Ecozeen Tech UK/Nigeria,
  PlaySnooker, Ecozeen Mining, Ecozeen Agro Allied). No phone numbers,
  physical addresses, legal registrations, financial figures, or customer
  quotes have been invented — those fields are intentionally left blank
  until supplied by the business and entered through the admin.

## Local setup

**Requirements:** PHP 8.3+, Composer 2, Node 18+/npm, MySQL 8 (or another
Laravel-supported database).

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Edit `.env` and point `DB_*` at your database (create an empty database named
in `DB_DATABASE` first), then:

```bash
php artisan migrate --seed
npm run build   # or `npm run dev` while developing
```

Create your first administrator account (see
[`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) for why this is a dedicated
command rather than a seeder):

```bash
php artisan ecozeen:create-admin
```

Serve the app:

```bash
php artisan serve
```

Visit `http://localhost:8000` for the public site and
`http://localhost:8000/admin` for the dashboard.

## Testing

```bash
php artisan test
```

The test suite uses an in-memory SQLite database and the `array` cache/mail
drivers (see `phpunit.xml`), so it never touches your configured MySQL
database.

Run the code style checker:

```bash
vendor/bin/pint --test   # check only
vendor/bin/pint          # auto-fix
```

## Project structure (CMS-relevant highlights)

| Path | Purpose |
| --- | --- |
| `app/Support/PageBuilder/SectionTypeRegistry.php` | Authoritative schema + sanitizer for every page-builder section type |
| `app/Http/Controllers/Admin/*` | Admin dashboard CRUD controllers |
| `app/Http/Controllers/Site/*` | Public site controllers |
| `app/Policies/*` | RBAC authorization rules |
| `database/seeders/RolesAndPermissionsSeeder.php` | The 8 roles and their permission sets |
| `database/seeders/*Seeder.php` | Idempotent seed data for companies, products, services, projects, pages, navigation, blog, jobs, and settings |
| `resources/views/admin/*` | Admin dashboard Blade views, including the page-builder UI |
| `resources/views/site/*` | Public site Blade views |
| `config/ecozeen.php` | App-specific configuration (media disks/limits, SEO defaults) |

## Documentation

- [`docs/HOSTINGER.md`](docs/HOSTINGER.md) — **step-by-step Hostinger
  Business shared hosting setup** (PHP 8.3, MySQL, Git, `.env`, migrate,
  first admin, SSL, cron).
- [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md) — VPS / generic production
  deployment, environment reference, backup & recovery, and the
  first-admin bootstrap procedure.
