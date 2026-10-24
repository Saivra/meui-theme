# Releasing

1. Run the complete package tests on SQLite and PostgreSQL, static analysis, formatting and strict
   Composer validation. Verify the supported-version matrix in GitHub Actions.
2. Check the studio and representative native components in light/dark mode at desktop and mobile widths.
3. Update the changelog and compatibility notes. Do not set a version in `composer.json`; Git tags provide it.
4. Inspect the distribution archive. Include runtime source, views, CSS, translations and license notices;
   exclude tests, workbench, dependencies, environment files and local build output.
5. Tag the tested commit and publish the GitHub release. Keep release notes focused on features, changes
   and upgrade instructions. Verify the release and download after publishing.
6. Register or synchronize `saivra/meui-theme` on Packagist and confirm the tagged version resolves.
7. Submit or update the free plugin listing through the Filament author dashboard. The author profile
   must have access; directory approval is separate from GitHub and Packagist publication.

An application consuming a pinned archive or version is updated separately. Theme development and a
new package release should not silently replace a consuming application's installed version.
