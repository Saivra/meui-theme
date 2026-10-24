# Changelog

## 0.2.0 — 2026-10-24

- Refined light and dark surfaces, typography, navigation selection, tables, forms, chart cards and authentication artwork.
- Respect native uncontained sections, removing redundant borders around statistics and schema layouts.
- Add validated panel defaults for neutral palette, accent, corners, card treatment, navigation surface, workspace layout and contrast.
- Extend density and other appearance defaults to support request-time closures for application database settings.
- Add an optional personal customizer with live preview, immediate application, database persistence and reset.
- Isolate saved preferences by panel, guard, user class and authenticated identifier; recheck authorization on writes.
- Let panel providers restrict access and available controls, or connect an existing preferences store through callbacks.
- Add optional portable JSON preferences migration, a second published stylesheet, mobile control sizing and forced-colors support.
- Keep mobile profile and notification controls at 44px and preview the application's default accent independently of saved choices.
- Add customization regression coverage and expanded documentation showcases.

Upgrade: run `php artisan filament:assets`. To enable personal customization, publish `meui-migrations`, run
`php artisan migrate`, then register `MeUiPlugin::make()->customizer()`. Existing theme-only installations need no migration.

## 0.1.0 — 2026-10-24

- Initial MIT-licensed release for Filament 5.10.2+, Laravel 12 and 13.
- Native sidebar and topbar, tenant navigation, profile menu and inset workspace.
- Light/dark/system appearance and comfortable/compact table density.
- Styling for native tables, forms, infolists, actions, dialogs, notifications and authentication.
- Five semantic banner tones and security, primary and secondary header rails.
- Reusable page-intro, frame and empty-state Blade components.
- Optional authorized component studio with fictitious records and interactive examples.
- Independent Testbench tests, component fixtures and compatibility CI.
- Preserve existing sidebar dimensions when the full shell is disabled.
