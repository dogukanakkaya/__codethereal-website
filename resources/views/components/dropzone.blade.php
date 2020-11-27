<div id="dz-{{ $index }}" class="dropzone ce-dropzone mb-3">
    <div class="dz-default dz-message">
        <i class="fas fa-cloud-upload-alt"></i>
        <h3>{{ __('dropzone.title') }}</h3>
        <p>({{ __('dropzone.description') }})</p>
    </div>
</div>
<div class="row ce-previews" id="preview-{{ $index }}"></div>

<input type="hidden" name="{{ $inputName }}" value="{{ isset($file->id) ? $file->id : 0 }}">

@once
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/static/dropzone.min.css') }}"/>
    @endpush

    @push('scripts')
        <script src="{{ asset('js/static/dropzone.min.js') }}"></script>
        <script>
            Dropzone.autoDiscover = false
            Dropzone.prototype.defaultOptions.dictRemoveFile = '{{ __('dropzone.remove_file') }}'
            Dropzone.prototype.defaultOptions.dictFileTooBig = '{{ __('dropzone.file_too_big') }}'
            Dropzone.prototype.defaultOptions.dictInvalidFileType = '{{ __('dropzone.file_extension_unallowed') }}'
            Dropzone.prototype.defaultOptions.dictCancelUpload = '{{ __('dropzone.cancel_upload') }}'
            Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = '{{ __('dropzone.cancel_upload_confirm') }}'
            Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = '{{ __('dropzone.too_many_files', ['max' => '1']) }}'

            // If there is one file make col-12, if there is 2 make col-6 else make col-4
            const gridCol = "{{ $maxFiles === 1 ? 12 : ($maxFiles === 2 ? 6 : 4) }}"

            const removeFile = (id) => {
                if (confirm('{{ __('global.confirm_delete') }}')) {

                    const url = '{{ route('files.destroy', ['id' => ':id']) }}'.replace(':id', id)

                    request.delete(url)
                        .then(response => {
                            makeToast(response.data)
                            if(response.data.status){
                                clearPreview{{ $index }}()
                            }
                        })
                }
            }

            const objectFitToggle = (el) => {
                const img = el.closest('div').previousElementSibling
                img.style.objectFit = img.style.objectFit === 'contain' ? 'cover' : 'contain';
            }
        </script>
    @endpush
@endonce

@push('scripts')
    <script>
        const dz{{ $index }} = new Dropzone("#dz-{{ $index }}", {
            url: '{{ route('files.upload') }}',
            params: {
                _token: '{{ csrf_token() }}',
                folder: '{{ $folder }}'
            },
            maxFilesize: 5,
            acceptedFiles: 'image/*',
            maxFiles: {{ $maxFiles }},
            addRemoveLinks: true,
            init: function () {
                this.on("success", function (file, response) {
                    createPreview{{ $index }}(response.id, response.path)
                    this.removeFile(file)
                });
            }
        });

        const clearPreview{{ $index }} = () => {
            document.querySelector('input[name="{{ $inputName }}"]').value = 0

            const preview{{ $index }} = document.getElementById('preview-{{ $index }}')
            preview{{ $index }}.querySelectorAll('*').forEach(n => n.remove());
        }

        const createPreview{{ $index }} = (id, url) => {
            // If max file is 1, every time new file dropped, clearPreview, then append.
            if ({{ $maxFiles }} === 1){
                clearPreview{{ $index }}()
            }
            document.querySelector('input[name="{{ $inputName }}"]').value = id

            const downloadUrl = '{{ route('files.download', ['id' => ':id']) }}'.replace(':id', id)

            const preview{{ $index }} = document.getElementById('preview-{{ $index }}')
            preview{{ $index }}.insertAdjacentHTML('afterbegin',
                `<div class="col-${gridCol}">
                                                <div class="thumb">
                                                    <img class="w-100" src="${url}" alt="dz-thumb" />
                                                    <div class="preview-actions">
                                                        <a href="${downloadUrl}" title="{{ __('global.download') }}"><i class="fas fa-cloud-download-alt"></i> {{ __('global.download') }}</a>
                                                        <a href="javascript:void(0);" onclick="removeFile(${id})" title="{{ __('dropzone.remove_file') }}"><i class="fas fa-trash"></i> {{ __('dropzone.remove_file') }}</a>
                                                        <a href="javascript:void(0);" onclick="objectFitToggle(this)" title="{{ __('dropzone.show_full') }}"><i class="fas fa-expand-alt"></i> {{ __('dropzone.show_full') }}</a>
                                                    </div>
                                                </div>
                                            </div>`)
        }

        @if (isset($file) && $file !== NULL)
            createPreview{{$index}}({{ $file->id }}, '{{ asset('storage/' . $file->path) }}');
        @endif
    </script>
@endpush
