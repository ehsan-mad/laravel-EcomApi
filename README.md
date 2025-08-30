<h1 align="center">Laravel E-Commerce Backend API</h1>

> Production URL: https://laravel-ecomapi.onrender.com

Pinned reference (deployment concepts): https://medium.com/@architkumar-SDE/how-to-deploy-laravel-application-on-render-for-free-using-docker-nginx-configuration-9683b3378756

This repository contains a lean Laravel 12 API backend for an e‑commerce system: products, categories, brands, carts, wishlists, invoices, and SSLCommerz payment initiation. It is containerized and deployed on Render with CockroachDB (PostgreSQL wire protocol) instead of traditional MySQL. The app exposes only JSON endpoints—no Blade UI layer is required for normal use.

## Core Stack

- Laravel 12 (PHP 8.2)
- CockroachDB (via `pgsql` driver)
- Custom idempotent migration command: `cockroach:migrate`
- JWT-based stateless auth (custom helper) using email + OTP
- SSLCommerz sandbox payment initiation
- Docker multi-stage build (composer + runtime) running `artisan serve`

## Quick Start (Local Development)

Prerequisites: PHP 8.2+, Composer, Docker (optional), CockroachDB or Postgres compatible instance.

1. Clone & install:
   ```bash
   git clone https://github.com/ehsan-mad/laravel-EcomApi.git
   cd laravel-EcomApi
   composer install
   cp .env.example .env
   php artisan key:generate
   ```
2. Configure DB (Cockroach/Postgres) in `.env`:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=localhost
   DB_PORT=26257
   DB_DATABASE=defaultdb
   DB_USERNAME=your_cockroach_user
   DB_PASSWORD=your_password
   ```
3. (Optional) Generate JWT secret (auto in container; locally you can):
   ```bash
   php -r "echo bin2hex(random_bytes(32));"  # copy value
   # put into .env as JWT_SECRET=...
   ```
4. Run custom Cockroach migration (creates tables with `IF NOT EXISTS`):
   ```bash
   php artisan cockroach:migrate
   ```
5. Seed minimal data (test user + default SSLCommerz row if empty):
   ```bash
   php artisan db:seed --force
   ```
6. Start API:
   ```bash
   php artisan serve
   ```

## Docker / Render Deployment Summary

The container uses `php:8.2-cli` and runs `php artisan serve` (sufficient for Render free tier). Startup script (`scripts/entrypoint.sh`) does:
1. Ensure `vendor/` exists (composer install if missing)
2. Ensure `.env`, generate `APP_KEY` and `JWT_SECRET` if blank
3. Start HTTP server early (Render port detection)
4. Run `cockroach:migrate` (idempotent)
5. Run `db:seed` (safe repeat)
6. Cache config (skip route cache due to closures)

Environment variable on Render: `PORT` (Render injects) is used by `artisan serve`.

If you adapt to nginx/php-fpm (like the pinned article) you would expose port 8080 and serve `public/` via nginx, but current setup keeps it simpler.

## Environment Variables (Key)

| Variable | Purpose | Notes |
|----------|---------|-------|
| APP_KEY | Laravel encryption key | Auto-generated in container if empty |
| JWT_SECRET | HMAC key for JWT tokens | Auto-generated if absent |
| DB_CONNECTION | Should be `pgsql` | CockroachDB driver |
| DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD | Database access | Cockroach uses 26257 by default |
| SSLC_STORE_ID | SSLCommerz store id | Optional (can be inserted via update route) |
| SSLC_STORE_PASSWORD | SSLCommerz store password | Keep secret |
| SSLC_INIT_URL | Sandbox: https://sandbox.sslcommerz.com/gwprocess/v4/api.php | Production differs |
| SSLC_SUCCESS_URL / SSLC_FAIL_URL / SSLC_CANCEL_URL / SSLC_IPN_URL | Callback endpoints | Point to deployed domain |

Any missing SSLCommerz fields are seeded with defaults on first boot; override via environment or update endpoint (see below) then remove the endpoint.

## Authentication Flow (Email + OTP + JWT)

1. Request login to generate OTP:
   `GET /userLogin/{email}` → sends/returns OTP (development stub) and sets a temporary state.
2. Verify OTP:
   `GET /verifyOTP/{email}/{otp}` → returns `token` (JWT) + user id in headers/cookies.
3. Use token in subsequent protected endpoints via `Authorization: Bearer <token>` or cookie (implementation reads custom headers—ensure you replicate how frontend uses it).
4. Logout: `GET /userLogout` (clears token cookie where applicable).

Example verify response (success):
```json
{
  "status": "success",
  "token": "<jwt>",
  "user_id": 1
}
```

## Payment Integration (SSLCommerz Sandbox)

Table: `sslcommerz_accounts` (single active row expected).

Initiation occurs during invoice creation (`/InvoiceCreate`). The helper posts to SSLCommerz with stored credentials. Common failure cause: placeholder `testbox` or inactive sandbox store.

Temporary credential update route (REMOVE AFTER CONFIGURED):
`ANY /payment-config/update`
Body (JSON or query params):
```json
{
  "store_id": "YOUR_SANDBOX_STORE_ID",
  "store_password": "YOUR_SANDBOX_STORE_PASSWORD",
  "currency": "BDT",
  "init_url": "https://sandbox.sslcommerz.com/gwprocess/v4/api.php",
  "success_url": "https://laravel-ecomapi.onrender.com/PaymentSuccess",
  "fail_url": "https://laravel-ecomapi.onrender.com/PaymentFail",
  "cancel_url": "https://laravel-ecomapi.onrender.com/PaymentCancel",
  "ipn_url": "https://laravel-ecomapi.onrender.com/PaymentIPN"
}
```
Debug endpoints:
- `GET /payment-config` (masked password)
- `GET /payment-health` (issues array)

On successful initiation `/InvoiceCreate` response should include gateway status and a redirect URL (GatewayPageURL) for frontend redirection.

## Public & Protected Endpoints (Current)

Public:
- `GET /` Root info
- `GET /health` Basic health
- `GET /db-ping` DB connectivity
- `GET /db-test` Product count check
- `GET /products-sample` / `categories-sample` / `brands-sample` Quick sample rows
- `GET /userLogin/{email}` Start auth
- `GET /verifyOTP/{email}/{otp}` Verify & get JWT
- `GET /userLogout` Logout
- `GET /productIds` Sample aggregated listing
- `GET|POST /PaymentSuccess|/PaymentFail|/PaymentCancel|/PaymentIPN` SSLCommerz callbacks
- `GET /payment-config` (debug)
- `GET /payment-health` (debug)
- `ANY /payment-config/update` (TEMP – remove after setup)
- `GET /routes-debug` (DO NOT enable in production long-term)

Protected (JWT required):
- `GET /ProductWishList`
- `GET /CreateWishList/{product_id}`
- `GET /RemoveWishList/{product_id}`
- `POST /CreateCartList`
- `GET /CartList`
- `GET /DeleteCartList/{product_id}`
- `GET /InvoiceCreate`
- `GET /InvoiceList`
- `GET /InvoiceProductList/{invoice_id}`
- `GET /debug-auth` (auth verification)

## CockroachDB Notes

Standard Laravel migrations are bypassed in favor of the custom command `cockroach:migrate` which uses `CREATE TABLE IF NOT EXISTS` for idempotent deploys (important on Render restarts). Avoid running default `php artisan migrate` unless you later replace the custom path with proper PostgreSQL-compatible migrations.

## Hardening Checklist (Post-Configuration)

1. Remove or guard `/payment-config/update` (e.g., wrap in env-based condition or require a header token).
2. Disable `/routes-debug` and other diagnostic endpoints not needed publicly.
3. Set real production SSLCommerz URLs & credentials (non-sandbox) before go-live.
4. Add rate limiting (e.g., Laravel throttle middleware) to auth & payment endpoints.
5. Enforce HTTPS (Render provides HTTPS by default) and set `APP_ENV=production`, `APP_DEBUG=false`.

## Minimal Invoice / Payment Test Flow

1. Configure SSLCommerz credentials (sandbox) via update endpoint.
2. Auth: `/userLogin/test@example.com` then `/verifyOTP/test@example.com/<otp>`.
3. Call `/InvoiceCreate` (ensure there are products; seed or insert manually if empty).
4. Inspect response JSON for payment initiation status.

## Troubleshooting

| Symptom | Likely Cause | Fix |
|---------|--------------|-----|
| `Store Credential Error Or Store is De-active` | Using `testbox` placeholder or inactive sandbox store | Provide real sandbox credentials |
| Duplicate table errors at boot (previous) | Non-idempotent DDL | Resolved via `IF NOT EXISTS` (already implemented) |
| `key must be a string when using hmac` | Missing `JWT_SECRET` | Ensure env or allow entrypoint to generate |
| 404 routes after deploy | Route cache with closures | We skip route cache; clear with `php artisan route:clear` |

## License

MIT

---
For deployment reference (nginx approach) see the pinned Medium article above; this project intentionally opts for a simpler `artisan serve` container suitable for quick free-tier hosting.
