# Dependency Audit Guide

Use this guide before opening dependency-security PRs.

## Current Baseline

The current dependency audit baseline is clean:

- `composer audit` reports no PHP package advisories.
- `npm audit` reports no Node package vulnerabilities.

## Recommended Commands

Run these checks from the project root:

```bash
composer audit
npm audit
```

On Windows PowerShell, if `npm` is blocked by script execution policy, use:

```powershell
npm.cmd audit
```

## PR Rules

Keep dependency-security PRs small and targeted:

- Update only packages with confirmed advisories.
- Prefer targeted package updates over broad `composer update` or broad npm upgrades.
- Run `php artisan test` after PHP package updates.
- Run `npm run build` after Node package updates that affect frontend tooling.
- Keep dependency updates separate from auth, OTP, checkout, payment, and UI changes.

If an audit is clean, do not update packages just to chase newer versions. Treat broad framework or build-tool upgrades as separate maintenance work with deeper testing.
