<?php

declare(strict_types=1);

namespace Igamov\FilamentMediaGallery\Form;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use League\Flysystem\UnableToCheckFileExistence;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaGallery extends BaseFileUpload
{
    protected string $view = 'filament-media-gallery::forms.media-file-upload';

    protected string | Closure | null $collection = null;

    protected string | Closure | null $thumb = null;

    protected string | Closure | null $conversion = null;

    protected Closure | bool $hasAltToName = false;

    protected ?Closure $filterMediaUsing = null;

    protected string | Closure | null $conversionsDisk = null;

    protected bool | Closure $isMultiple = false;

    protected bool | Closure $hasResponsiveImages = false;

    protected string | Closure | null $mediaName = null;

    protected ?string $acceptedFileText = null;

    /**
     * @var array<string, mixed> | Closure | null
     */
    protected array | Closure | null $customHeaders = null;

    /**
     * @var array<string, mixed> | Closure | null
     */
    protected array | Closure | null $customProperties = null;

    /**
     * @var array<string, array<string, string>> | Closure | null
     */
    protected array | Closure | null $manipulations = null;

    /**
     * @var array<string, mixed> | Closure | null
     */
    protected array | Closure | null $properties = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->columnSpanFull();
        if (! $this->getAcceptedFileTypes()) {
            $this->image();
        }
        $this->multiple();
        $this->loadStateFromRelationshipsUsing(static function (MediaGallery $component, HasMedia $record): void {
            /** @var Model&HasMedia $record */
            $media = $record->load('media')->getMedia($component->getCollection() ?? 'default')
                ->when(
                    $component->hasMediaFilter(),
                    fn (Collection $media) => $component->filterMedia($media)
                )
                ->when(
                    ! $component->isMultiple(),
                    fn (Collection $media): Collection => $media->take(1),
                )
                ->mapWithKeys(function (Media $media): array {
                    $uuid = $media->getAttributeValue('uuid');

                    return [$uuid => $uuid];
                })
                ->toArray();

            $component->state($media);
        });
        $this->afterStateHydrated(static function (BaseFileUpload $component, string | array | null $state): void {
            if (is_array($state)) {
                return;
            }

            $component->state([]);
        });

        $this->beforeStateDehydrated(null);

        $this->dehydrated(false);

        $this->saveRelationshipsUsing(static function (MediaGallery $component) {
            $component->deleteAbandonedFiles();
            $component->saveUploadedFiles();
        });

        $this->saveUploadedFileUsing(static function (MediaGallery $component, TemporaryUploadedFile $file, ?Model $record): null | string | TemporaryUploadedFile {
            if (! method_exists($record, 'addMediaFromString')) {
                return $file;
            }

            try {
                if (! $file->exists()) {
                    return null;
                }
            } catch (UnableToCheckFileExistence $exception) {
                return null;
            }

            /** @var FileAdder $mediaAdder */
            $mediaAdder = $record->addMediaFromString($file->get());

            $filename = $component->getUploadedFileNameForStorage($file);

            $media = $mediaAdder
                ->addCustomHeaders($component->getCustomHeaders())
                ->usingFileName($filename)
                ->usingName($component->getMediaName($file) ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                ->storingConversionsOnDisk($component->getConversionsDisk() ?? '')
                ->withCustomProperties($component->getCustomProperties())
                ->withManipulations($component->getManipulations())
                ->withResponsiveImagesIf($component->hasResponsiveImages())
                ->withProperties($component->getProperties())
                ->toMediaCollection($component->getCollection() ?? 'default', $component->getDiskName());

            return $media->getAttributeValue('uuid');
        });

        $this->reorderUploadedFilesUsing(static function (MediaGallery $component, ?Model $record, array $state): array {
            $uuids = array_filter(array_values($state));

            $mediaClass = ($record && method_exists($record, 'getMediaModel')) ? $record->getMediaModel() : null;
            $mediaClass ??= config('media-library.media_model', Media::class);

            $mappedIds = $mediaClass::query()->whereIn('uuid', $uuids)->pluck('id', 'uuid')->toArray();

            $mediaClass::setNewOrder([
                ...array_flip($uuids),
                ...$mappedIds,
            ]);

            return $state;
        });
    }

    /**
     * @return array<array{name: string, size: int, mime_type: string, url: string} | null>
     */
    public function getUploadedFiles(): array
    {
        $files = [];
        foreach ($this->getState() ?? [] as $fileKey => $file) {

            /** @var ?Media $media */
            $media = $this->getRecord()->getRelationValue('media')->firstWhere('uuid', $file);
            $srcUrl = $media?->getUrl();
            $thumbUrl = $media?->getUrl($this->getThumb() ?? '');
            $files[$fileKey] = [
                'name' => $media?->getAttributeValue('file_name') ?? $media?->getAttributeValue('name'),
                'alt' => $media?->getAttributeValue('file_name') ?? $media?->getAttributeValue('name'),
                'size' => $media?->getAttributeValue('size'),
                'mime_type' => $media?->getAttributeValue('mime_type'),
                'thumb_url' => $thumbUrl,
                'url' => $srcUrl,
            ];
        }

        return $files;
    }

    public function deleteAbandonedFiles(): void
    {
        /** @var Model&HasMedia $record */
        $record = $this->getRecord();

        $record
            ->getMedia($this->getCollection() ?? 'default')
            ->whereNotIn('uuid', array_keys($this->getState() ?? []))
            ->when($this->hasMediaFilter(), fn (Collection $media): Collection => $this->filterMedia($media))
            ->each(fn (Media $media) => $media->delete());
    }

    public function getCollection(): ?string
    {
        return $this->evaluate($this->collection);
    }

    public function getThumb(): ?string
    {
        return $this->evaluate($this->thumb);
    }

    public function hasMediaFilter(): bool
    {
        return $this->filterMediaUsing instanceof Closure;
    }

    public function filterMedia(Collection $media): Collection
    {
        return $this->evaluate($this->filterMediaUsing, [
            'media' => $media,
        ]) ?? $media;
    }

    public function getAcceptFileText(): string
    {
        return $this->acceptedFileText ?? config('gallery-json-media.form.default.image_accepted_text');
    }

    public function getCustomHeaders(): array
    {
        return $this->evaluate($this->customHeaders) ?? [];
    }

    public function image(): static
    {
        $this->acceptedFileTypes = config('media-gallery.form.default.image_accepted_file_type');
        $this->acceptedFileText = config('media-gallery.form.default.image_accepted_text');

        return $this;
    }

    public function collection(string | Closure | null $collection): static
    {
        $this->collection = $collection;

        return $this;
    }

    public function thumb(string | Closure | null $thumb): static
    {
        $this->thumb = $thumb;

        return $this;
    }

    public function conversion(string | Closure | null $conversion): static
    {
        $this->conversion = $conversion;

        return $this;
    }

    public function getConversionsDisk(): ?string
    {
        return $this->evaluate($this->conversionsDisk);
    }

    public function hasNameReplaceByTitle(): bool
    {
        return $this->evaluate($this->hasAltToName);
    }

    public function getConversion(): ?string
    {
        return $this->evaluate($this->conversion);
    }

    /**
     * @return array<string, mixed>
     */
    public function getCustomProperties(): array
    {
        return $this->evaluate($this->customProperties) ?? [];
    }

    /**
     * @param  array<string, mixed> | Closure | null  $properties
     */
    public function customProperties(array | Closure | null $properties): static
    {
        $this->customProperties = $properties;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getProperties(): array
    {
        return $this->evaluate($this->properties) ?? [];
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getManipulations(): array
    {
        return $this->evaluate($this->manipulations) ?? [];
    }

    public function hasResponsiveImages(): bool
    {
        return (bool) $this->evaluate($this->hasResponsiveImages);
    }

    public function getMediaName(TemporaryUploadedFile $file): ?string
    {
        return $this->evaluate($this->mediaName, [
            'file' => $file,
        ]);
    }
}
