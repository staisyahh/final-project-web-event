@props([
    'wireModel' => 'form.gallery',
])

<div
    wire:ignore
    x-data="{
        pond: null,
        initFilePond() {
            // Register FilePond plugins
            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.registerPlugin(FilePondPluginFileValidateType);

            // Create a new FilePond instance
            this.pond = FilePond.create($refs.input);
            
            // Configure FilePond
            this.pond.setOptions({
                allowMultiple: true,
                server: {
                    // Use Livewire's temporary upload endpoint
                    process: (fieldName, file, metadata, load, error, progress, abort) => {
                        @this.upload('{{ $wireModel }}', file, load, error, progress)
                    },
                    // Use Livewire's temporary upload removal endpoint
                    revert: (uniqueFileId, load, error) => {
                        @this.removeUpload('{{ $wireModel }}', uniqueFileId, load)
                    },
                },
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif'],
                labelFileTypeNotAllowed: 'Jenis file tidak valid',
                fileValidateTypeLabelExpectedTypes: 'Hanya .png, .jpg, atau .gif',
                labelIdle: `Seret & lepas gambar Anda atau <span class='filepond--label-action'>Cari</span>`,
            });
        }
    }"
    x-init="initFilePond()"
>
    {{-- The actual file input that FilePond will enhance --}}
    <input type="file" x-ref="input" multiple>
</div>
