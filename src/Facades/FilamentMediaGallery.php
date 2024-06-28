<?php

namespace Igamov\FilamentMediaGallery\Facades;

use Igamov\FilamentMediaGallery\Form\MediaGallery;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Igamov\FilamentMediaGallery\Form\MediaGallery
 */
class FilamentMediaGallery extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MediaGallery::class;
    }
}
