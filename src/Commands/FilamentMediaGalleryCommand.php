<?php

namespace Igamov\FilamentMediaGallery\Commands;

use Illuminate\Console\Command;

class FilamentMediaGalleryCommand extends Command
{
    public $signature = 'filament-media-gallery';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
