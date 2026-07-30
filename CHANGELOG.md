# Changelog

All notable changes made to the codebase to support the local Docker environment and prepare for deployment.

## Added
- **`Dockerfile`**: Created a custom Dockerfile based on `php:7.4-apache` to install the required `mysqli` and `pdo_mysql` extensions.
- **`docker-compose.yml`**: Added Docker Compose configuration to orchestrate the web server and the local `mariadb:10.5` database, including auto-import of the `hmsdb.sql` dump.

## Changed
Updated the database connection host from `"localhost"` to `"db"` (the name of the Docker database service container) in the following files:
- `digit-spot-hms/includes/connection_string.php`
- `digit-spot-hms-hotel/includes/connection_string.php`
- `digit-spot-hms-apps/includes/connection_string.php`
- `digit-spot-hms-pos/includes/connection_string.php`
- `digit-spot-hms-pos/public/admin/materialcontrol/includes/connection_string.php`

> **Note**: For the AWS deployment, `sync_code.sh` handles all connection string replacements automatically before syncing to EC2, and reverts them locally afterwards.

## AWS Deployment Fixes (2026-05-15)

### Fixed
- **`sync_code.sh`**: Extended to also replace `DB_USER` (`root` → `hmsadmin`) and `DB_PASS` (empty → RDS password) in all `connection_string.php` files before syncing, and revert them locally after. Previously only `DB_SERVER` was swapped, causing `Access denied for user 'root'` errors on RDS.
- **`sync_code.sh`**: Added `-v /home/ubuntu/hms-solution:/var/www/html` volume flag to the `docker run` command. The Dockerfile has no `COPY` instruction and relies on a volume mount — without it the container served an empty web root.

### Changed
- **`index.html`** (root): Added `<meta http-equiv="refresh">` redirect to `/digit-spot-hms/` so that `hmssolution.org` goes directly to the landing page instead of the static readme page.

### Applied directly on EC2 (2026-05-15)
The following were applied in-place on the running EC2 instance since `sync_code.sh` had already been run:
- All 5 `connection_string.php` files updated with correct RDS credentials (`hmsadmin` / `<YOUR_RDS_PASSWORD>`).
- Root `index.html` updated with the redirect meta tag.
- Docker container restarted with the volume mount flag.

## Login & Domain URL Fixes (2026-05-15)

### Root Cause
All 5 `php_paths.php` files had `DOMAIN_URL`, `MAIN_DOMAIN_URL`, `$service_portal`, and logo URLs hardcoded to `http://127.0.0.1/`. After a successful login, the PHP returns that URL to the browser as the redirect target — causing login to appear broken in production even when authentication succeeded.

### Fixed
- **`sync_code.sh`**: Extended to replace `http://127.0.0.1/` → `https://hmssolution.org/` in all `php_paths.php` files before syncing, and revert locally after.
- **RDS `user_admin_tbl`**: Reset `uchena` account password to `admin` (SHA-1 hash). The original import used the pre-reset SQL dump, so the local password change was not reflected in RDS.

### Applied directly on EC2 (2026-05-15)
- All 5 `php_paths.php` files updated: every `http://127.0.0.1/` replaced with `https://hmssolution.org/`.
- `uchena` password reset directly in RDS via `UPDATE user_admin_tbl SET password = SHA1('admin') WHERE username = 'uchena'`.

## Navigation / AJAX Fix (2026-05-15)

### Root Cause
All sub-apps have a `jspath.js` file that defines `filePath` — the base URL used for every AJAX call in the application. It was hardcoded to `http://127.0.0.1/...`. Every navigation click (Administration, Billing, etc.) fired an AJAX request to that local address, got no response in production, and hung on "processing please wait".

### Fixed
- **`sync_code.sh`**: Extended to also replace `http://127.0.0.1/` → `https://hmssolution.org/` in all `jspath.js` files before syncing, and revert locally after.

### Applied directly on EC2 (2026-05-15)
- All 5 `jspath.js` files (including `digit-spot-hms-pos/public/admin/materialcontrol/js/jspath.js`) updated with `https://hmssolution.org/`.

### Login Credentials (Production)
| Account | Username | Password | Notes |
|---|---|---|---|
| Hardcoded fallback | `hotelmaster` | `master001` | Lives in `log_account.php`, bypasses DB |
| DB super admin | `uchena` | `admin` | Lives in `user_admin_tbl` |

## Deployment & Routing Fixes (2026-06-04)

### Added
- **`.htaccess`**: Created a root `.htaccess` file to enforce a permanent 301 redirect from the bare domain (`hmssolution.org/`) to the landing page (`/digit-spot-hms/`). This replaces legacy load balancer/server routing rules that were lost during the AWS migration.

### Fixed
- **PHP 8 Compatibility**: Pulled upstream fix from `origin/master` for `digit-spot-hms-pos/public/admin/materialcontrol/includes/custom_functions.php` to resolve `count()` errors when an array is empty.
- **Root `index.html`**: Added a fallback `<meta http-equiv="refresh">` redirect to ensure users never see the repository documentation placeholder page.
- **`sync_code.sh`**: Re-added the Docker volume mount `-v /home/ubuntu/hms-solution:/var/www/html` to `docker run`. The `Dockerfile` does not copy files into the image natively, which had resulted in Apache returning a `403 Forbidden` error when trying to serve an empty directory.
