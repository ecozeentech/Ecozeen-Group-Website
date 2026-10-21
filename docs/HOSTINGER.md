# Hostinger Business Shared Hosting — step-by-step setup

This is the complete procedure for putting the Ecozeen Group website + CMS
on **Hostinger Web Business** (shared hosting). It is different from a VPS
deploy: you cannot change the document root away from `public_html`, you
cannot run a Supervisor queue worker, and Node.js is usually not available
over SSH.

Follow the steps in order. Where you see `yourdomain.com` or `u123456789`,
replace them with your real domain and Hostinger username.

---

## What you need before starting

1. An active **Hostinger Business** (or Cloud) plan with the domain attached.
2. **SSH access** enabled (Business includes it).
3. A GitHub account that can clone
   `https://github.com/ecozeentech/Ecozeen-Group-Website`
   (if the repo is private, create a [personal access token](https://github.com/settings/tokens)
   with `repo` scope, or add Hostinger’s deploy key).
4. 15–30 minutes. Do not skip the PHP 8.3 or `.env` steps — the site will
   500 without them.

---

## Step 1 — Set PHP to 8.3 (web *and* SSH)

Laravel 13 **requires PHP 8.3**. Hostinger’s website PHP version and the
SSH `php` command are independent — you must set both.

### 1a. Website PHP (hPanel)

1. Log in to [hPanel](https://hpanel.hostinger.com).
2. Open **Websites → Manage** for `yourdomain.com`.
3. Go to **Advanced → PHP Configuration** (sometimes labelled **PHP
   Settings**).
4. Set the PHP version to **8.3**.
5. Under **PHP Extensions**, make sure these are enabled:
   `curl`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo`,
   `pdo_mysql`, `tokenizer`, `xml`, `zip`.
6. Under **PHP Options**, set:
   - `memory_limit` → `256M` (512M if offered)
   - `max_execution_time` → `120`
   - `upload_max_filesize` and `post_max_size` → `16M` (resumes are capped
     at 10 MB in the app)
7. Click **Save**.

### 1b. SSH PHP (the one `composer` and `artisan` use)

1. In hPanel go to **Advanced → SSH Access**. Enable it if it is off.
   Note the host, port (usually `65002`), username, and password.
2. Connect from your computer:

   ```bash
   ssh -p 65002 u123456789@yourdomain.com
   ```

3. Check the CLI version:

   ```bash
   php -v
   ls /opt/alt/
   ```

   If `php -v` is **not** 8.3, Hostinger still has 8.3 installed in parallel.
   Use it explicitly every time (do not rely on `php` alone):

   ```bash
   /opt/alt/php83/usr/bin/php -v
   ```

   To make that the default for this SSH session:

   ```bash
   echo 'alias php="/opt/alt/php83/usr/bin/php"' >> ~/.bashrc
   echo 'alias composer="composer2"' >> ~/.bashrc
   source ~/.bashrc
   php -v          # must now say 8.3.x
   composer2 -V    # Composer 2
   ```

---

## Step 2 — Create the MySQL database

1. In hPanel go to **Databases → Management**.
2. Click **Create database**.
3. Fill in:
   - Database name — Hostinger prefixes this, e.g. `u123456789_ecozeen`
   - Username — e.g. `u123456789_ecozeen`
   - A strong password. Save it in a password manager; you will paste it
     into `.env`.
4. Click **Create**.
5. Confirm the user is assigned to the database with **All privileges**.
6. Write down four values:
   - **DB host** — almost always `localhost` (shown on the same page; do
     **not** use `127.0.0.1` if hPanel says `localhost`)
   - **DB name** (the full prefixed name)
   - **DB username** (the full prefixed name)
   - **DB password**

---

## Step 3 — Empty `public_html`

Hostinger serves whatever is in `public_html`. The default welcome page
must go.

**File Manager method**

1. **Websites → Manage → File Manager**.
2. Open `domains/yourdomain.com/public_html` (or `public_html` if that is
   the only site).
3. Select everything inside it (the default `index.html`, `default.php`,
   etc.) and delete it. Leave the folder itself.

**SSH method**

```bash
cd ~/domains/yourdomain.com/public_html
ls
# Review the listing, then:
rm -rf ./* ./.[!.]*
```

---

## Step 4 — Put the Laravel project *inside* `public_html`

On Hostinger you cannot point the web root at Laravel’s `public/` folder.
The supported layout is: the **entire** Laravel app lives in `public_html`,
and a root `.htaccess` (already in this repo) sends visitors to the nested
`public/` directory while blocking `.env`, `vendor/`, `storage/`, etc.

### Option A — Git over SSH (recommended)

```bash
cd ~/domains/yourdomain.com/public_html

# Public repo:
git clone https://github.com/ecozeentech/Ecozeen-Group-Website.git .

# Private repo (use a GitHub personal access token as the password):
git clone https://github.com/ecozeentech/Ecozeen-Group-Website.git .
```

You want the **`main`** branch:

```bash
git checkout main
git status    # should show you are on main
```

### Option B — hPanel Git

1. **Websites → Manage → Git**.
2. Connect the GitHub repository.
3. Set the deploy path to `public_html` and the branch to `main`.
4. Deploy.

### Option C — ZIP upload (no Git)

On your computer:

```bash
git clone https://github.com/ecozeentech/Ecozeen-Group-Website.git
cd Ecozeen-Group-Website
git checkout main
zip -r ecozeen.zip . -x ".git/*" "node_modules/*" "vendor/*"
```

In hPanel File Manager, upload `ecozeen.zip` into `public_html` and
**Extract** it so that `artisan`, `composer.json`, `app/`, `public/` and
`.htaccess` are **directly** inside `public_html` — not nested in an extra
folder.

After any method, this listing must be true:

```text
~/domains/yourdomain.com/public_html/
  .htaccess          ← repo-root file (rewrites to /public)
  artisan
  composer.json
  app/
  bootstrap/
  config/
  database/
  public/            ← Laravel's real web root (index.php, .htaccess, build/)
  resources/
  routes/
  storage/
  ...
```

If you see `public_html/Ecozeen-Group-Website/artisan`, the project was
extracted one folder too deep. Move everything up one level.

---

## Step 5 — Install PHP dependencies

From SSH:

```bash
cd ~/domains/yourdomain.com/public_html

/opt/alt/php83/usr/bin/php /usr/bin/composer2 install --no-dev --optimize-autoloader --no-interaction
```

If Composer complains about PHP 8.2, you are not using the 8.3 binary —
go back to Step 1b. Do **not** use `--ignore-platform-reqs`.

`vendor/` should now exist. You do **not** need `npm` on the server: the
compiled CSS/JS is already in `public/build/` on `main`.

---

## Step 6 — Create and edit `.env`

```bash
cd ~/domains/yourdomain.com/public_html
cp .env.example .env
/opt/alt/php83/usr/bin/php artisan key:generate
```

Edit `.env` (File Manager → right-click `.env` → Edit, **or** `nano .env`
over SSH). Set **exactly** these (leave everything else unless you know
you need it):

```dotenv
APP_NAME="Ecozeen Group"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_ecozeen
DB_USERNAME=u123456789_ecozeen
DB_PASSWORD=the-password-from-step-2

# Shared hosting cannot run a queue worker. "sync" sends mail in the
# same request so contact / newsletter / career emails actually go out.
QUEUE_CONNECTION=sync
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true

MEDIA_DISK=public
PRIVATE_DISK=local

LOG_CHANNEL=stack
LOG_LEVEL=error

MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_SCHEME=smtps
MAIL_USERNAME=hello@yourdomain.com
MAIL_PASSWORD=the-mailbox-password
MAIL_FROM_ADDRESS="hello@yourdomain.com"
MAIL_FROM_NAME="Ecozeen Group"
```

Notes:

- `APP_DEBUG` **must** be `false` on a live site.
- `APP_URL` **must** be `https://…` (SSL is Step 10).
- Create the mailbox `hello@yourdomain.com` first under **Emails → Email
  Accounts** in hPanel; use that address and its password for
  `MAIL_USERNAME` / `MAIL_PASSWORD`. Until the mailbox exists, you can
  temporarily set `MAIL_MAILER=log` — form submissions still save in the
  database, they just will not email anyone.

Save the file.

---

## Step 7 — Permissions

```bash
cd ~/domains/yourdomain.com/public_html

chmod -R ug+rwx storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod 775 {} \;
find storage bootstrap/cache -type f -exec chmod 664 {} \;
```

If artisan later errors with “permission denied” on a log or cache file,
re-run the block above.

---

## Step 8 — Database migrate, seed, first admin

```bash
cd ~/domains/yourdomain.com/public_html
PHP=/opt/alt/php83/usr/bin/php

$PHP artisan migrate --force
$PHP artisan db:seed --force
```

`db:seed` creates the 8 roles/permissions and the Ecozeen Group seed
content (companies, pages, navigation, etc.). It does **not** create a
login — that is the next command.

```bash
$PHP artisan ecozeen:create-admin
```

You will be asked for:

- **Name** — e.g. `Site Owner`
- **Email** — the address you will log in with
- **Password** — at least **12 characters**

Save those credentials. This is the only Super Administrator until you
invite more people from **Admin → Users**.

Then cache config for production:

```bash
$PHP artisan storage:link
$PHP artisan config:cache
$PHP artisan route:cache
$PHP artisan view:cache
```

If you edit `.env` later, re-run `config:cache` or the old values stay
cached:

```bash
$PHP artisan config:cache
```

---

## Step 9 — Confirm the rewrite is in place

`public_html/.htaccess` must be the **repo-root** `.htaccess` (the one that
rewrites to `public/` and forbids `.env`). `public_html/public/.htaccess`
must also exist (Laravel’s front-controller rules).

Quick check over SSH:

```bash
head -5 ~/domains/yourdomain.com/public_html/.htaccess
# should mention RewriteRule to public/

ls ~/domains/yourdomain.com/public_html/public/index.php
```

In a browser, visit:

| URL | Expected |
| --- | --- |
| `https://yourdomain.com/` | Homepage (200) |
| `https://yourdomain.com/contact` | Contact page |
| `https://yourdomain.com/admin` | Redirects to `/login` |
| `https://yourdomain.com/.env` | 403 Forbidden |

If `/` is a 403, the root `.htaccess` is missing. If `/` is a directory
listing of `app/`, `bootstrap/`, … the rewrite is not running — go back
to Step 1a and confirm PHP/Apache rewrite is on (it is on by default on
Hostinger).

Log in at `https://yourdomain.com/login` with the admin you created in
Step 8. You should land on `/admin`.

---

## Step 10 — SSL (HTTPS)

1. In hPanel: **Websites → Manage → SSL**.
2. Install the **free SSL** (Let’s Encrypt) if it is not already active.
3. Enable **Force HTTPS**.
4. Confirm `APP_URL` in `.env` starts with `https://`, then:

   ```bash
   cd ~/domains/yourdomain.com/public_html
   /opt/alt/php83/usr/bin/php artisan config:cache
   ```

---

## Step 11 — Cron (optional but useful)

Shared hosting cannot run `queue:work`, which is why `.env` uses
`QUEUE_CONNECTION=sync`. A cron job is still useful if you later add
scheduled tasks.

1. hPanel → **Advanced → Cron Jobs**.
2. Type: **Custom**.
3. Schedule: every minute (`* * * * *`).
4. Command:

   ```text
   /opt/alt/php83/usr/bin/php /home/u123456789/domains/yourdomain.com/public_html/artisan schedule:run >> /dev/null 2>&1
   ```

   Replace `u123456789` and `yourdomain.com` with your real values (the
   full path is shown in **FTP Accounts** as the root folder, minus
   `public_html`).

---

## Updating the site later

Whenever `main` changes (including the uploaded HTML UI rebuild: public
templates for home, about, companies, products, insights, partner, and
contact; a light admin dashboard/directory/visual editor; rebuilt CSS in
`public/build/`):

```bash
cd ~/domains/yourdomain.com/public_html
git pull origin main

/opt/alt/php83/usr/bin/php /usr/bin/composer2 install --no-dev --optimize-autoloader --no-interaction
/opt/alt/php83/usr/bin/php artisan migrate --force
/opt/alt/php83/usr/bin/php artisan config:cache
/opt/alt/php83/usr/bin/php artisan route:cache
/opt/alt/php83/usr/bin/php artisan view:cache
```

Then hard-refresh the browser (Ctrl+Shift+R / Cmd+Shift+R). The hashed
files in `public/build/` change on each frontend build, and Hostinger
does not need Node — those files ship on `main`.

Do **not** re-run `db:seed` on a live site that already has content.
`PageSeeder` and `NavigationSeeder` replace matching pages and menus, so
a re-seed would overwrite homepage and navigation edits made in admin.

If you want the dedicated **About** (`/about`) and **Partner With Us**
(`/partner`) templates on an existing site, those slugs must already
exist as published CMS pages — add them in **Admin → Visual Editor**
instead of re-seeding. The uploaded wordmark and shield remain the
fallback logo and favicon at `public/brand/` and `public/favicon.png`.

After this update, saving companies, products, settings, and pages in
admin no longer wipes images or fails on empty `https://` social/website
URL placeholders. Public pages read live CMS records; they do not copy
mock HTML addresses, phone numbers, or financial metrics.

---

## Backups on Hostinger

1. **hPanel → Files → Backups** (or **Websites → Dashboard → Backups**)
   — enable weekly automatic backups if not already on.
2. Also dump the database yourself after any important CMS change:

   ```bash
   mysqldump -u u123456789_ecozeen -p u123456789_ecozeen \
     | gzip > ~/ecozeen-$(date +%F).sql.gz
   ```

   Download that file off the server. See `docs/DEPLOYMENT.md` for the
   full restore procedure.

Job-application PDFs live in `storage/app/private/` — include that folder
in any file backup (hPanel backups usually cover it).

---

## Common failures

| Symptom | Cause | Fix |
| --- | --- | --- |
| `composer install` says PHP 8.2 | SSH CLI is not 8.3 | Use `/opt/alt/php83/usr/bin/php` (Step 1b) |
| HTTP 500, blank page | `.env` missing, `APP_KEY` empty, or `storage/` not writable | Steps 6–7; then `tail storage/logs/laravel.log` |
| HTTP 403 on `/` | Root `.htaccess` missing or `public_html` still has Hostinger’s default page | Steps 3–4 and 9 |
| Site lists `app/`, `vendor/` | Rewrite not applied | Confirm `public_html/.htaccess` is the repo-root file |
| `/admin` 404 | Project extracted one folder too deep | Move contents up so `artisan` is in `public_html/` |
| Contact form saves but no email | `QUEUE_CONNECTION=database` with no worker, or SMTP not set | Set `QUEUE_CONNECTION=sync` and Step 6 mail settings, then `config:cache` |
| CSS unstyled | `public/build/` missing | `git pull` from `main` (assets are committed) or run `npm run build` locally and upload `public/build/` |
| `SQLSTATE[HY000] [2002]` | Wrong `DB_HOST` | Use the host shown in hPanel (usually `localhost`) |
| Login works then 419 | Session/HTTPS mismatch | `SESSION_SECURE_COOKIE=true`, Force HTTPS, `APP_URL=https://…`, `config:cache` |
| `The stream or file could not be opened in append mode` | Bad permissions on `storage/logs` | Re-run Step 7 |

---

## What this hosting plan cannot do

- **No queue worker.** Always `QUEUE_CONNECTION=sync` on this plan.
- **No document-root change.** Do not try to point the domain at `public/`;
  use the repo-root `.htaccess` instead.
- **No Node.js build on the server** (or an unreliable one). Frontend
  assets ship in `public/build/` on `main`.
- **Symlinks may be disabled** until you enable them in PHP functions.
  `php artisan storage:link` needs `symlink()`; if it fails, in hPanel
  **PHP Configuration → Disable Functions** remove `symlink` from the
  list, or copy `storage/app/public` files into `public/storage` as a
  fallback.

If you later move to a VPS, follow `docs/DEPLOYMENT.md` instead of this
file (Nginx root → `public/`, Supervisor queue worker, Redis, etc.).
