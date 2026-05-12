@php
    use Filament\Support\Facades\FilamentAsset;
    use Filament\Support\Facades\FilamentView;
    use Illuminate\Support\Arr;

    $key = $getKey();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :label-sr-only="$isLabelHidden()"
    @class([
        'bg-gray-100/80 dark:bg-gray-800 p-2 rounded-lg' => $isSecondary(),
    ])
>
    <div
        @if (FilamentView::hasSpaMode())
            ax-load="visible"
        @else
            ax-load
        @endif
        ax-load-src="{{ FilamentAsset::getAlpineComponentSrc('filament-media-gallery', 'igamov/filament-media-gallery') }}"
        x-load-css="['{{ FilamentAsset::getStyleHref('filament-media-gallery-styles', 'igamov/filament-media-gallery') }}']"
        x-data="galleryFileUpload({
                     state : $wire.$entangle('{{ $getStatePath() }}'),
                     statePath : @js($getStatePath()),
                     minSize : @js($getMinSize()) ,
                     maxSize : @js($getMaxSize()),
                     maxFiles : @js($getMaxFiles()),
                     isReorderable: @js($isReorderable()),
                     isDeletable: @js($isDeletable()),
                     isDisabled: @js($isDisabled),
                     isDownloadable: @js($isDownloadable()),
                     isMultiple : @js($isMultiple()),
                     acceptedFileTypes : @js($getAcceptedFileTypes()),
                     uploadingMessage: @js($getUploadingMessage()),
                     changeNameToAlt : @js($hasNameReplaceByTitle()),
                     removeUploadedFileUsing: async (fileKey) => {
                        return await $wire.callSchemaComponentMethod(
                            @js($key),
                            'removeUploadedFile',
                            { fileKey },
                        )
                    },
                     deleteUploadedFileUsing: async (fileKey) => {
                         return await $wire.callSchemaComponentMethod(
                            @js($key),
                            'deleteUploadedFile',
                            { fileKey },
                        )
                    },
                    getUploadedFilesUsing: async () => {
                        return await $wire.callSchemaComponentMethod(
                            @js($key),
                            'getUploadedFiles',
                        )
                    },
                    reorderUploadedFilesUsing: async (fileKeys) => {
                        return await $wire.callSchemaComponentMethod(
                            @js($key),
                            'reorderUploadedFiles',
                            { fileKeys },
                        )
                    },

         })"
        wire:ignore
        x-ignore
        class="grid gap-y-2 "
        x-id="['file-input']"
    >
        <input type="file" :id="$id('file-input')"
               x-bind="laFileInput"
               x-ref="laFileInput"
               class="hidden"
               {{ $isMultiple()?'multiple':'' }}
               {{ $isDisabled()?'disabled':'' }}
               accept="{{  implode(',',Arr::wrap($getAcceptedFileTypes())) }}"
        >



        <div class="gallery-file-upload-wrapper"
             x-ref="galleryImages"
        >
            <ul role="list"
                id="gallery"
                class="flex flex-wrap gap-2 transition-all duration-200"
                @keydown.window.tab="usedKeyboard = true"
                @dragenter.stop.prevent="dropcheck++"
                @dragleave="dropcheck--;dropcheck || rePositionPlaceholder()"
                @dragover.stop.prevent
                @dragend="revertState()"
                @drop.stop.prevent="getSort();resetState()"
                x-ref="ulGalleryWrapper"
            >
                @include('filament-media-gallery::forms.content.media-content')
                <div @class([
                      'media-dropzone flex items-center justify-center  py-3 border border-dashed rounded-lg border-gray-300  text-gray-400 transition
              hover:border-primary-400 dark:border-gray-400/50 dark:bg-gray-800 dark:hover:border-primary-600 dark:text-white/80',
                     ])
                     :class="{'pointer-events-none opacity-40' : startUpload}"
                     role="button"
                     x-ref="dropzone"
                     x-cloak
                     x-bind="dropZone"
                     x-show="canUpload"
                     style="width: 188px;"
                >
                    <div class="flex gap-3 pointer-events-none items-center" x-ref="ladroptitle">
                        @svg(name: 'heroicon-o-plus',class:"w-10 h-auto text-slate-500" )
                    </div>
                </div>
            </ul>
        </div>
    </div>
</x-dynamic-component>
