
<template x-for="(file, fileIndex) in uploadFiles" :key="fileIndex">
  <li class="image-file rounded-lg cursor-pointer" role="listitem"
{{--      style="transition: transform 200ms ease 0s"--}}
      x-data="{startGrabbing: false}"
      :data-id="file.filekey"
      :x-ref="fileIndex"
      @dragstart="dragstart($event)"
      @dragend="$event.target.setAttribute('draggable', false);stopDragging = true"
      @dragover="updateListOrder($event)"
      draggable="false"
      :class="{ 'opacity-25': indexBeingDragged === fileIndex }"
  >
    <div class="flex w-full max-h-fit"
         :class="{'pointer-events-none': indexBeingDragged}"
    >

      <div class="gallery-header"
           x-data="{ itemDrag : false }"
           :class="{ 'webplusm-finish-uploading' : file.is_success  && file.is_new, 'webplusm-error-uploading' : file.error  && file.is_new }"
      >
        @if($isReorderable())
          <button type="button" class="gallery-icon reorder justify-self-start hidden sm:block"
                  :class="{
                                                    'grabbing-cursor' : startGrabbing,
                                                    'grab-cursor' : !startGrabbing,
                                                    'pointer-events-auto' : !startUpload && file.is_success && !indexBeingDragged,
                                                    'pointer-events-none cursor-not-allowed' : startUpload || !file.is_success || indexBeingDragged
                                                }"
                  x-on:mousedown.stop="startGrabbing = true;stopDragging = false;setParentDraggable($event)"
                  x-on:mouseup.stop="stopDragging = true;startGrabbing = false"
                  @dragover.stop
                  x-show="!itemDrag && !indexBeingDragged && isReorderable"
                  wire:loading.attr="disabled"
          >
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 24 24"
                 style="enable-background:new 0 0 512 512" xml:space="preserve" class="text-white w-4 h-5"
            >
              <g>
                <circle cx="8" cy="4" r="2" fill="currentColor" opacity="1" data-original="#000000"></circle>
                <circle cx="8" cy="12" r="2" fill="currentColor" opacity="1" data-original="#000000"></circle>
                <circle cx="8" cy="20" r="2" fill="currentColor" opacity="1" data-original="#000000"></circle>
                <circle cx="16" cy="4" r="2" fill="currentColor" opacity="1" data-original="#000000"></circle>
                <circle cx="16" cy="12" r="2" fill="currentColor" opacity="1" data-original="#000000"></circle>
                <circle cx="16" cy="20" r="2" fill="currentColor" opacity="1" data-original="#000000"></circle>
              </g>
            </svg>
{{--            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"--}}
{{--                 xmlns="http://www.w3.org/2000/svg"--}}
{{--                 class="text-white w-4 h-5 ">--}}
{{--              <path fill-rule="evenodd" clip-rule="evenodd"--}}
{{--                    d="M2 0C2.53043 0 3.03914 0.210714 3.41421 0.585786C3.78929 0.960859 4 1.46957 4 2C4 2.53043 3.78929 3.03914 3.41421 3.41421C3.03914 3.78929 2.53043 4 2 4C1.46957 4 0.960859 3.78929 0.585786 3.41421C0.210714 3.03914 0 2.53043 0 2C0 1.46957 0.210714 0.960859 0.585786 0.585786C0.960859 0.210714 1.46957 0 2 0ZM10 0C10.5304 0 11.0391 0.210714 11.4142 0.585786C11.7893 0.960859 12 1.46957 12 2C12 2.53043 11.7893 3.03914 11.4142 3.41421C11.0391 3.78929 10.5304 4 10 4C9.46957 4 8.96086 3.78929 8.58579 3.41421C8.21071 3.03914 8 2.53043 8 2C8 1.46957 8.21071 0.960859 8.58579 0.585786C8.96086 0.210714 9.46957 0 10 0ZM20 2C20 1.46957 19.7893 0.960859 19.4142 0.585786C19.0391 0.210714 18.5304 0 18 0C17.4696 0 16.9609 0.210714 16.5858 0.585786C16.2107 0.960859 16 1.46957 16 2C16 2.53043 16.2107 3.03914 16.5858 3.41421C16.9609 3.78929 17.4696 4 18 4C18.5304 4 19.0391 3.78929 19.4142 3.41421C19.7893 3.03914 20 2.53043 20 2ZM2 8C2.53043 8 3.03914 8.21071 3.41421 8.58579C3.78929 8.96086 4 9.46957 4 10C4 10.5304 3.78929 11.0391 3.41421 11.4142C3.03914 11.7893 2.53043 12 2 12C1.46957 12 0.960859 11.7893 0.585786 11.4142C0.210714 11.0391 0 10.5304 0 10C0 9.46957 0.210714 8.96086 0.585786 8.58579C0.960859 8.21071 1.46957 8 2 8ZM12 10C12 9.46957 11.7893 8.96086 11.4142 8.58579C11.0391 8.21071 10.5304 8 10 8C9.46957 8 8.96086 8.21071 8.58579 8.58579C8.21071 8.96086 8 9.46957 8 10C8 10.5304 8.21071 11.0391 8.58579 11.4142C8.96086 11.7893 9.46957 12 10 12C10.5304 12 11.0391 11.7893 11.4142 11.4142C11.7893 11.0391 12 10.5304 12 10ZM18 8C18.5304 8 19.0391 8.21071 19.4142 8.58579C19.7893 8.96086 20 9.46957 20 10C20 10.5304 19.7893 11.0391 19.4142 11.4142C19.0391 11.7893 18.5304 12 18 12C17.4696 12 16.9609 11.7893 16.5858 11.4142C16.2107 11.0391 16 10.5304 16 10C16 9.46957 16.2107 8.96086 16.5858 8.58579C16.9609 8.21071 17.4696 8 18 8ZM4 18C4 17.4696 3.78929 16.9609 3.41421 16.5858C3.03914 16.2107 2.53043 16 2 16C1.46957 16 0.960859 16.2107 0.585786 16.5858C0.210714 16.9609 0 17.4696 0 18C0 18.5304 0.210714 19.0391 0.585786 19.4142C0.960859 19.7893 1.46957 20 2 20C2.53043 20 3.03914 19.7893 3.41421 19.4142C3.78929 19.0391 4 18.5304 4 18ZM10 16C10.5304 16 11.0391 16.2107 11.4142 16.5858C11.7893 16.9609 12 17.4696 12 18C12 18.5304 11.7893 19.0391 11.4142 19.4142C11.0391 19.7893 10.5304 20 10 20C9.46957 20 8.96086 19.7893 8.58579 19.4142C8.21071 19.0391 8 18.5304 8 18C8 17.4696 8.21071 16.9609 8.58579 16.5858C8.96086 16.2107 9.46957 16 10 16ZM20 18C20 17.4696 19.7893 16.9609 19.4142 16.5858C19.0391 16.2107 18.5304 16 18 16C17.4696 16 16.9609 16.2107 16.5858 16.5858C16.2107 16.9609 16 17.4696 16 18C16 18.5304 16.2107 19.0391 16.5858 19.4142C16.9609 19.7893 17.4696 20 18 20C18.5304 20 19.0391 19.7893 19.4142 19.4142C19.7893 19.0391 20 18.5304 20 18Z"--}}
{{--                    fill="currentColor" />--}}

{{--            </svg>--}}
          </button>
        @endif
          <div class="flex gallery-file-info align-items-center gap-1" style="overflow: hidden;-webkit-box-orient: vertical;-webkit-line-clamp: 1;"
              >
            <span x-text="getFileName(file)"></span>
          </div>
        <div class="gallery-file-info" x-text="getHumanSize(file.size)"></div>
      </div>
      <div data-fancybox="gallery"
           :data-src="file.url">
      <div class="flex w-full pointer-events-none object-cover" x-html="getContentImage(file)"

           :class="{ 'blur-[2px]' : startUpload && file.is_new && !file.is_success }"
      >
      </div>
      </div>
      <div class="absolute inset-0 m-auto  w-28 h-28 flex items-center justify-center rounded-full z-[3] pointer-events-none"
           :style="{'--wm-progress' : `calc(${file.progress??0} * 1%)`,backgroundImage: `conic-gradient(white var(--wm-progress), transparent var(--wm-progress))`}"
           x-show="startUpload && file.is_new && !file.is_success"
      >
        <div class="flex items-center justify-center text-xl font-medium bg-gray-700 rounded-full text-white dark:text-white w-24 h-24" x-text="(file.progress??0)+'%'"></div>
      </div>
      <div class="gallery-footer">
        <div class="flex">
          @if($isDownloadable())
            <button type="button" class="gallery-icon download"
                    @dragover.stop.prevent
                    x-on:click="
                       const result = await fetch(file.url)
                        console.log(file.name)
                        const fileURL = window.URL.createObjectURL(new Blob([await result.arrayBuffer()]));
                        const fURL = document.createElement('a');

                        fURL.href = fileURL;
                        fURL.setAttribute('download', getFileName(file));
                        fURL.setAttribute('target', '_blank');
                        fURL.click();
                      "
                    :class="{
                      'pointer-events-auto' : !startUpload && file.is_success && !indexBeingDragged,
                      'pointer-events-none cursor-not-allowed' : startUpload || !file.is_success
                     }"
            >
              @svg(name: 'heroicon-o-arrow-down-tray',class:"w-4 h-4 text-white" )
            </button>
          @endif
        </div>

        <div class="flex">


          @if(@$isDeletable())
            <button type="button" class="gallery-icon delete"
                    @dragover.stop.prevent
                    x-on:click.stop="isFire = true;file.is_new?await removeUploadFile(file.filekey,fileIndex):await deleteUploadFile(file.filekey,fileIndex);isFire = false;"
                    x-data="{ isFire : false }"
                    :class="{
                                                    'pointer-events-auto' : !startUpload && file.is_success && !indexBeingDragged,
                                                    'pointer-events-none cursor-not-allowed' : startUpload || !file.is_success
                                                }"
                    :disabled="isFire"
            >
{{--              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"--}}
{{--                   stroke-width="2.5" stroke="currentColor" class="w-5 h-5" x-show="!isFire" aria-hidden="true" data-slot="icon"--}}
{{--              >--}}
{{--                <path stroke-linecap="round" stroke-linejoin="round"--}}
{{--                      d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />--}}
{{--              </svg>--}}
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                   stroke-width="1.5" stroke="currentColor" class="w-4 h-4" x-show="!isFire" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
              </svg>


              <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                   class="animate-spin fi-icon-btn-icon h-5 w-5"
                   x-show="isFire"
              >
                <path clip-rule="evenodd"
                      d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                      fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
              </svg>
            </button>
          @endif
        </div>
      </div>
    </div>
  </li>

</template>
