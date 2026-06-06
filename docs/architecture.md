# Architecture Guide

This project is a single Laravel application. For now, keep the frontend and backend in one repository, but keep their responsibilities separated by folder and by commit.

## Current Shape

The app uses Laravel Blade pages, Laravel routes, server-side sessions, role middleware, OTP verification, cart/session data, checkout, PayMongo payments, and service catalog data together. Because of that, splitting into separate frontend and backend repositories would add complexity right now.

Use this guide to keep the code organized while still working inside one Laravel repo.

## Frontend Areas

Frontend code is responsible for page structure, visual layout, browser behavior, and user-facing interaction.

Keep frontend work in:

- `resources/views/`
  - Blade pages and partials.
  - Customer storefront, dashboard, checkout, profile, auth, and admin screens.
- `resources/css/`
  - Tailwind and app styles.
- `resources/js/`
  - Browser-side JavaScript used by Vite.
- `public/`
  - Images, compiled assets, and public files.

Frontend files may display data that controllers pass to them, but they should not own role rules, OTP rules, payment decisions, order ownership checks, or database write logic.

## Backend Areas

Backend code is responsible for request handling, validation, authentication state, authorization, database reads/writes, OTP rules, service rules, order rules, and payment integration.

Keep backend work in:

- `app/Http/Controllers/`
  - Customer-facing request handling.
  - Cart, checkout, profile, service, order, and support flows.
- `app/Http/Controllers/Auth/`
  - Customer authentication, registration, password reset, email verification, Google login, and OTP flow controllers.
- `app/Http/Controllers/Admin/`
  - Admin, admin-client, and developer portal flows.
- `app/Models/`
  - Eloquent models and relationships.
- `app/Http/Middleware/`
  - Role, admin, OTP, and access middleware.
- `database/migrations/`
  - Database schema changes.
- `config/`
  - Service and integration configuration.

Backend code may choose which view to render and which data to pass, but it should not contain page styling or duplicated frontend markup.

## Route Areas

Routes are the bridge between frontend URLs and backend controllers.

Current route files:

- `routes/web.php`
  - Public storefront routes.
  - Customer routes.
  - Admin, admin-client, and developer route groups.
  - Cart, checkout, payment, and service routes.
- `routes/auth.php`
  - Customer auth routes and OTP-related auth flow routes.
- `routes/console.php`
  - Artisan console routes.

For future cleanup, consider splitting admin/developer route groups into a dedicated route file only after tests cover the route names and middleware behavior.

## Role Boundaries

The app has four important roles:

- Customer
  - Uses storefront, cart, checkout, profile, orders, dashboard, and customer OTP.
- Admin-client
  - Uses the protected staff portal after approval and OTP verification.
- Admin
  - Uses protected admin routes and can manage operational areas.
- Developer
  - Trusted system-owner role. Developer access can bypass OTP where explicitly required by policy.

Role and OTP behavior belongs in controllers, middleware, policies, tests, or dedicated backend services. Blade files should only render the state they receive.

## Service Catalog Boundaries

Service catalog behavior should stay consistent across storefront, cart, checkout, and admin management.

Keep these responsibilities separate:

- `ServiceController`
  - Public service browsing and admin service management.
- `CartController`
  - Cart session behavior and selected service package data.
- `CheckoutController`
  - Order placement and checkout validation.
- `PaymongoCheckoutController`
  - Payment request, success, cancel, and webhook behavior.
- Blade views
  - Service cards, forms, cart tables, checkout screens, and status display only.

## Cleanup Status

The current frontend/backend separation cleanup has been completed at the Laravel boundary level:

- The storefront homepage shell is now small and delegates major page sections to `resources/views/components/storefront/`.
- Storefront Blade components now cover the header, hero, services, about, contact, product detail, cart drawer, and product modal markup.
- Homepage service-card data is prepared by `FrontPageController` instead of being built inside `welcome.blade.php`.
- Customer dashboard data is prepared by `CustomerPortalController`.
- Auth dashboard redirect decisions live in `AuthenticatedSessionController`.
- Route files are mostly declarative and should stay focused on URL, middleware, prefix, and controller mapping.

Stop this cleanup track here unless there is a specific bug or duplication problem. The next meaningful phase is service-layer extraction, which touches business behavior and should be handled in separate, higher-review PRs.

## Commit And Branch Rules

Use small branches and small commits.

Recommended branch types:

- `feature/...` for new user-facing behavior.
- `fix/...` for bugs.
- `cleanup/...` for file organization and docs.
- `maintenance/...` for dependency and security updates.

Recommended commit split:

- Frontend-only view/style changes.
- Backend-only controller/model/route changes.
- Database migration changes.
- Test changes.
- Documentation changes.

Avoid mixing a visual redesign, auth logic, dependency updates, and database migrations in one commit or PR.

## When To Consider A Real Split

Consider separate frontend and backend repositories only after the app has:

- A stable JSON API for customer, admin-client, admin, and developer workflows.
- Frontend screens moved away from direct Blade/session coupling.
- API authentication and CSRF/session strategy documented.
- Tests for auth, OTP, cart, checkout, payments, service catalog, and admin access.
- A deployment plan for two separate applications.

Until then, one repo with clear folder boundaries is safer.
