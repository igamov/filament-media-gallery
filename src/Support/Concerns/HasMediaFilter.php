<?php

declare(strict_types=1);

namespace Igamov\FilamentMediaGallery\Support\Concerns;

use Closure;
use Illuminate\Support\Collection;

/**
 * Same API as Filament's Spatie Media Library plugin; that trait ships only with `filament/spatie-laravel-media-library-plugin`, not `filament/support`.
 *
 * @see https://github.com/filamentphp/spatie-laravel-media-library-plugin/blob/5.x/src/Support/Concerns/HasMediaFilter.php
 */
trait HasMediaFilter
{
    protected ?Closure $filterMediaUsing = null;

    public function filterMediaUsing(?Closure $callback): static
    {
        $this->filterMediaUsing = $callback;

        return $this;
    }

    public function filterMedia(Collection $media): Collection
    {
        return $this->evaluate($this->filterMediaUsing, [
            'media' => $media,
        ]) ?? $media;
    }

    public function hasMediaFilter(): bool
    {
        return $this->filterMediaUsing instanceof Closure;
    }
}
