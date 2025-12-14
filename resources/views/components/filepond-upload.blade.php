<div wire:ignore x-data="{
        pond: null,
        initFilePond() {
            // Register plugin hanya jika belum teregistrasi (mencegah error double register)
            if (!window.FilePondPluginImagePreview) {
                // Pastikan script plugin sudah di-load di layout utama (app.blade.php)
                console.warn('FilePond Plugins belum dimuat. Pastikan CDN/Import sudah benar.');
                return;
            }

            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.registerPlugin(FilePondPluginFileValidateType);

            // Create FilePond instance
            this.pond = FilePond.create($refs.input, {
                allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
                labelFileTypeNotAllowed: 'Jenis file tidak valid',
                fileValidateTypeLabelExpectedTypes: 'Hanya .png, .jpg, .gif, atau .webp',
                labelIdle: `Seret & lepas gambar atau <span class='filepond--label-action'>Cari</span>`,

                // Konfigurasi Server (Jembatan ke Livewire)
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        // Menggunakan $attributes->wire('model') untuk menangkap nama property secara dinamis
                        @this.upload(
                            '{{ $attributes->wire('model')->value() }}',
                            file,
                            load,
                            error,
                            progress
                        )
                    },
                    revert: (filename, load) => {
                        @this.removeUpload(
                            '{{ $attributes->wire('model')->value() }}',
                            filename,
                            load
                        )
                    },
                },
            });
        }
    }" x-init="initFilePond()">
    <input type="file" x-ref="input" {{ $attributes }}>
</div>
