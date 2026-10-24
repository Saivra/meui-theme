# Contributing

Start with a small issue describing the problem or proposed behavior. Include a minimal reproduction
for bugs. Changes should preserve Filament's native behavior and keep application policies, models and
business logic outside the package.

## Local checks

```shell
composer install
composer test
composer analyse
php vendor/bin/pint --test
composer validate --strict
```

Tests default to in-memory SQLite. To use a dedicated disposable PostgreSQL database, set
`MEUI_DB_CONNECTION=pgsql`, `MEUI_DB_DATABASE`, `MEUI_DB_HOST`, `MEUI_DB_PORT`, `MEUI_DB_USERNAME` and
`MEUI_DB_PASSWORD` in your shell before running `composer test`. Use a test database only: the suite
creates and removes its fixture tables. Never commit environment files or credentials.

## UI changes

Use the studio and component fixtures with light/dark mode, expanded/collapsed navigation, desktop and
mobile widths, keyboard focus and reduced motion. Check top navigation and `shell(false)` when changing
shell styles. Keep CSS scoped to the MeUI panel marker. Do not change vendor files or publish global
vendor-view overrides. Add a regression test for a behavior change.

## Pull requests

Explain the problem, resulting behavior and validation. Keep changes focused and avoid generated
vendor files, lockfiles, local screenshots containing private information, and application-specific code.
By contributing, you agree that your contribution is distributed under the project's MIT license.
