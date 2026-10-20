# Deployment, Environment Reference & Backup/Recovery

## 1. Requirements

- PHP 8.3+ with the extensions Laravel 13 requires (`bcmath`, `ctype`, `curl`,
  `dom`, `fileinfo`, `gd` or `imagick`, `json`, `mbstring`, `openssl`,
  `pcre`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`)
- MySQL 8.0+
- Composer 2
- Node 18+ / npm (build step only — Node is not required at runtime)
- A process manager (systemd/Supervisor) for the queue worker
- A web server (Nginx or Apache) in front of PHP-FPM

## 2. First deployment

```bash
git clone <repository> ecozeen-group
cd ecozeen-group

composer install --no-dev --optimize-autoloader
npm ci
npm run build

cp .env.example .env
php artisan key:generate
# Edit .env — see the environment reference below.

php artisan migrate --force
php artisan db:seed --force   # roles/permissions + accurate, non-fabricated seed content

php artisan ecozeen:create-admin   # bootstrap the first Super Administrator — see §4

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link   # only needed if MEDIA_DISK=public
```

Point your web server's document root at `public/`. A minimal Nginx
`location` block:

```nginx
root /var/www/ecozeen-group/public;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/run/php/php8.3-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
}

location ~ /\.(?!well-known).* {
    deny all;
}
```

### Queue worker (required)

Contact, newsletter, and job-application notification emails are all queued
(`ShouldQueue`). Run a worker under a process supervisor, e.g.
`/etc/supervisor/conf.d/ecozeen-queue.conf`:

```ini
[program:ecozeen-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ecozeen-group/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/ecozeen-group
autostart=true
autorestart=true
numprocs=2
user=www-data
stdout_logfile=/var/www/ecozeen-group/storage/logs/queue-worker.log
```

Without a running worker, the contact/newsletter/career emails are enqueued
but never sent — the website still functions correctly either way, since all
of those controllers persist the submission to the database first and only
then dispatch a best-effort notification email.

### Redeploys

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build

php artisan down --render="errors::503" --retry=30
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart   # picks up new code in the already-running worker
php artisan up
```

## 3. Environment reference

All variables live in `.env`; `.env.example` lists every key with safe local
defaults. The ones specific to this application (beyond standard Laravel
config) are:

| Variable | Purpose | Notes |
| --- | --- | --- |
| `MEDIA_DISK` | Filesystem disk used for publicly-served media (logos, product/company images, blog images, OG images) | `public` locally; use `s3` in production for multi-server/CDN delivery |
| `PRIVATE_DISK` | Filesystem disk used for sensitive uploads | Currently only job-application resumes. Must **never** be a publicly-readable disk — files are only ever served through the authenticated, policy-checked `admin.job-applications.resume` route, never via a public URL |
| `DB_*` | MySQL connection | |
| `MAIL_*` | Outbound mail transport | Used for contact/newsletter/job-application notification emails (all queued) |
| `QUEUE_CONNECTION` | Queue driver | `database` works out of the box; a dedicated worker process is required either way (see above) |
| `SESSION_DRIVER`, `CACHE_STORE` | Session/cache backend | `database` by default; for multi-server deployments use a shared store (e.g. Redis) so admin sessions survive load-balancer routing |
| `APP_URL` | Canonical site URL | Used to build the `public` disk's URL, the sitemap's `<loc>` entries, and (in production) forces HTTPS via `AppServiceProvider` when `APP_ENV=production` |

Secrets (`APP_KEY`, `DB_PASSWORD`, `MAIL_PASSWORD`, any cloud storage keys)
must never be committed. Generate `APP_KEY` with `php artisan key:generate`
on first deploy and keep it stable afterward — rotating it invalidates all
existing encrypted data (sessions, encrypted cookies).

## 4. Administrator accounts

Public self-registration is intentionally disabled (`routes/auth.php`) —
this is an administrative CMS, not a consumer product, and allowing anyone
to sign up would defeat the RBAC model entirely.

The first Super Administrator is created interactively, after migrations and
seeding, so the password is never written to a config file, environment
variable, or seeder:

```bash
php artisan ecozeen:create-admin
```

You will be prompted for a name, email, and password (minimum 12
characters; use `--password=` only in non-interactive/CI contexts, and
never in a file that gets committed). Subsequent administrators should be
created by an existing Super Administrator through **Admin → Users**, not
by re-running this command, except as a recovery path if all Super
Administrator accounts are locked out.

Re-running the command with an existing email updates that user's name,
password, and role — useful both for provisioning and for resetting a lost
Super Administrator password without database access.

### Roles

Seeded by `RolesAndPermissionsSeeder` (`php artisan db:seed --class=RolesAndPermissionsSeeder`,
already included in the default `db:seed` run):

| Role | Scope |
| --- | --- |
| Super Administrator | Every permission, always (see `AppServiceProvider::boot()`); cannot delete their own account or grant themselves additional roles |
| Administrator | Everything except user management is scoped the same as Super Administrator, minus `roles.manage` |
| Content Manager | Pages, companies, products, services, projects, blog, media, navigation |
| Editor | Update-only on most content types, limited create rights |
| Author | Blog posts and media only |
| SEO Manager | Update meta fields on existing content, manage redirects |
| Inquiry Manager | Contact inquiries, newsletter subscribers, job listings/applications |
| Read-only Analyst | View-only across every module, plus the activity log |

## 5. Backup & recovery

### What to back up

1. **Database** — all content, settings, and submissions live in MySQL.
2. **The `public` media disk** (or your S3 bucket, if configured) — uploaded
   images/logos.
3. **The `private`/`local` disk** — job application resumes. Treat this with
   the same care as the database; it contains applicant PII.
4. **`.env`** — store securely (e.g. in your secrets manager), separately
   from code backups, since it contains `APP_KEY` and credentials.

The application code itself is reproducible from version control and does
not need to be backed up separately.

### Database backup

```bash
mysqldump --single-transaction --routines --triggers \
  -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" \
  | gzip > ecozeen-$(date +%Y%m%d%H%M%S).sql.gz
```

Automate this on a schedule appropriate to your change volume (daily is a
reasonable starting point for a corporate marketing site) and retain backups
off-server (object storage, a managed backup service, etc.).

### Database restore

```bash
php artisan down
gunzip -c ecozeen-20260101120000.sql.gz | mysql -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE"
php artisan up
```

### Media/file backup

If using local disks, back up `storage/app/public` and `storage/app/private`
(or their equivalents under your configured disk root) with your regular
file-system backup tooling (e.g. `rsync`, snapshotting). If using S3 (or any
S3-compatible disk), enable bucket versioning and/or cross-region
replication instead.

### Disaster recovery checklist

1. Provision a fresh host and deploy the application per §2.
2. Restore the database from the most recent backup.
3. Restore (or re-point `MEDIA_DISK`/`PRIVATE_DISK` at) the media and resume
   storage backups.
4. Restore `.env` from your secrets manager — reusing the **same** `APP_KEY`
   is required for existing encrypted sessions/cookies to remain valid (not
   required for the site to function, since all CMS content is plain
   database rows, but required if you want existing logged-in sessions to
   survive).
5. Run `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
6. Smoke-test: homepage, a company/product/service/project detail page, the
   admin login, and the admin dashboard.
