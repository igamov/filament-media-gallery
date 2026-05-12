# Changelog

All notable changes to `filament-media-gallery` will be documented in this file.

## 2.0.0 - 2026-05-12

- Add support for **Filament v5**, **PHP 8.2+**, and **Laravel 11** (via dependency chain).
- Require `spatie/laravel-medialibrary` ^11.0 explicitly.
- Register `SchemasServiceProvider` in the package test harness for Filament 5 compatibility.
- Fix default accept-label config key to use `media-gallery` (was incorrectly referencing `gallery-json-media`).
- Form field Blade uses **`callSchemaComponentMethod`** + component key (Filament 5); `getUploadedFiles()` is exposed with **`#[ExposedLivewireMethod]`** / **`#[Renderless]`** (other upload actions inherit from `BaseFileUpload`).
- **`secondary()`** styling via `CanBeSecondary`, matching gallery-json-media’s secondary wrapper.
- Frontend build migrated to **Tailwind CSS v4** (`@tailwindcss/cli` / `@tailwindcss/postcss`), aligned with current gallery-json-media tooling.

## 1.0.0 - 202X-XX-XX

- Initial release (Filament v3).
