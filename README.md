# Media Gallery for Filament Spatie Media Library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/igamov/filament-media-gallery.svg?style=flat-square)](https://packagist.org/packages/igamov/filament-media-gallery)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/igamov/filament-media-gallery/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/igamov/filament-media-gallery/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/igamov/filament-media-gallery/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/igamov/filament-media-gallery/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/igamov/filament-media-gallery.svg?style=flat-square)](https://packagist.org/packages/igamov/filament-media-gallery)


This package add support spatie media library to filament V3.x

I've taken inspiration from the following plugins: [Json Media](https://github.com/webplusmultimedia/filament-json-media) & [Filament Spatie Media Library](https://github.com/filamentphp/spatie-laravel-media-library-plugin).

[![filament-media-gallery.png](https://i.postimg.cc/Tw52W9xd/filament-media-gallery.png)](https://postimg.cc/KkFh6t4w)
## Installation

You can install the package via composer:

```bash
composer require igamov/filament-media-gallery
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-media-gallery-views"
```


## Usage
### In Filament Forms
```php
use Igamov\FilamentMediaGallery\Form\MediaGallery;

MediaGallery::make('gallery')
      ->collection('gallery')
      ->thumb('tiny_conversion')
      ->disk('disk')
      ->columnSpanFull()
      ->reorderable(true)
      ->downloadable()
      ->maxSize(1536)
      ->multiple()
```

### In Filament Tables

To use the media gallery image column:

```php
use Igamov\FilamentMediaGallery\Tables\Columns\MediaGalleryImageColumn;

MediaGalleryImageColumn::make('avatar')
```

The media gallery image column supports all the customization options of the [original image column](https://filamentphp.com/docs/tables/columns/image).

### Passing a collection

Optionally, you may pass a `collection()`:

```php
use Igamov\FilamentMediaGallery\Tables\Columns\MediaGalleryImageColumn;

MediaGalleryImageColumn::make('avatar')
    ->collection('avatars')
```

The [collection](https://spatie.be/docs/laravel-medialibrary/working-with-media-collections/simple-media-collections) allows you to group files into categories.

By default, only media without a collection (using the `default` collection) will be shown. If you want to show media from all collections, you can use the `allCollections()` method:

```php
use Igamov\FilamentMediaGallery\Tables\Columns\MediaGalleryImageColumn;

MediaGalleryImageColumn::make('avatar')
    ->allCollections()
```

### Using conversions

You may also specify a `conversion()` to load the file from showing it in the table, if present:

```php
use Igamov\FilamentMediaGallery\Tables\Columns\MediaGalleryImageColumn;

MediaGalleryImageColumn::make('avatar')
    ->conversion('thumb')
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [igamov](https://github.com/igamov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
