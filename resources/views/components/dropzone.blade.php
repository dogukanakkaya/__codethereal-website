<div id="dz-{{ $index }}" class="dropzone ce-dropzone mb-3">
    <div class="dz-default dz-message">
        <i class="material-icons-outlined md-36">cloud_upload</i>
        <h3>{{ __('dropzone.title') }}</h3>
        <p>({{ __('dropzone.description') }})</p>
    </div>
</div>
<div class="row ce-previews {{ $sortable ? 'sortable' : '' }}" id="preview-{{ $index }}"></div>
<input type="hidden" name="{{ $inputName }}" value="0">

@once


    @push('toEnd')
        @include('admin.partials.edit-image-modal')
    @endpush

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/ce/dropzone.css') }}"/>
    @endpush

    @push('scripts')
        @if($sortable)
            <script src="{{ asset('js/static/sortable.min.js') }}"></script>
            <script>
                const sortable = new Sortable(document.querySelector('.sortable'), {
                    animation: 150,
                    dataIdAttr: "data-file-id",
                    onChange: () => {
                        request.put('{{ route('files.save_sequence') }}', sortable.toArray())
                    },
                });
            </script>
        @endif
        <script src="{{ asset('js/static/dropzone.min.js') }}"></script>
        <script>
            Dropzone.autoDiscover = false
            Dropzone.prototype.defaultOptions.dictRemoveFile = '{{ __('dropzone.remove_file') }}'
            Dropzone.prototype.defaultOptions.dictFileTooBig = '{{ __('dropzone.file_too_big') }}'
            Dropzone.prototype.defaultOptions.dictInvalidFileType = '{{ __('dropzone.file_extension_unallowed') }}'
            Dropzone.prototype.defaultOptions.dictCancelUpload = '{{ __('dropzone.cancel_upload') }}'
            Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation = '{{ __('dropzone.cancel_upload_confirm') }}'
            Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = '{{ __('dropzone.too_many_files', ['max' => $maxFiles]) }}'

            const removeFile = id => {
                if (confirm('{{ __('global.confirm_delete') }}')) {
                    const url = '{{ route('files.destroy', ['id' => ':id']) }}'.replace(':id', id)

                    request.delete(url)
                        .then(response => {
                            makeToast(response.data)
                            if (response.data.status) {
                                clearPreview(id)
                            }
                        })
                }
            }

            const clearPreview = id => {
                const inputEl = document.querySelector(`[data-file-id='${id}'] + input`)
                if (inputEl){
                    inputEl.value = inputEl.value.replace(id, '').replace('|', '')
                }

                const preview = document.querySelector(`[data-file-id='${id}']`)
                if (preview){
                    preview.remove()
                }
            }

            let fileId
            const imageForm = document.getElementById('image-form')

            const editFile = id => {
                const url = '{{ route('files.find', ['id' => ':id']) }}'.replace(':id', id)
                fileId = id

                request.get(url)
                    .then(response => {
                        const {translations} = response.data

                        // TODO: think that, which one is better performance? Or maybe merge all languages foreach to one, and assign these to variables
                        let translation = {}
                        @foreach($languages as $language)
                            translation = translations?.{{ $language->code }}
                        document.querySelector(`input[name="{{ $language->code }}[file_title]"]`).value = translation?.title ?? ''
                        document.querySelector(`input[name="{{ $language->code }}[file_alt]"]`).value = translation?.alt ?? ''
                        document.querySelector(`input[type=checkbox][name="{{ $language->code }}[file_active]"]`).checked = parseInt(translation?.active ?? 1) === 1
                        @endforeach
                        openModal('#image-form-modal')
                    })
            }

            imageForm.addEventListener('submit', e => {
                e.preventDefault()

                toggleBtnLoading()
                const url = '{{ route('files.update', ['id' => ':id']) }}'.replace(':id', fileId)
                request.put(url, serialize(imageForm, { hash: true }))
                    .then(response => {
                        makeToast(response.data)
                        if (response.data.status) {
                            closeModal('#image-form-modal')
                            imageForm.reset()
                        }
                        toggleBtnLoading()
                    })
            })

            const objectFitToggle = el => {
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
            addRemoveLinks: true,
            init: function () {
                this.on("success", function (file, response) {
                    createPreview{{ $index }}(response.id, response.path)
                    this.removeFile(file)
                });
            }
        });

        const createPreview{{ $index }} = (id, url) => {
            if (parseInt(document.querySelector('input[name="{{ $inputName }}"]').value) === 0) {
                document.querySelector('input[name="{{ $inputName }}"]').value = id
            } else {
                document.querySelector('input[name="{{ $inputName }}"]').value += `|${id}`
            }

            const downloadUrl = '{{ route('files.download', ['id' => ':id']) }}'.replace(':id', id)

            const preview{{ $index }} = document.getElementById('preview-{{ $index }}')
            preview{{ $index }}.insertAdjacentHTML('afterbegin',
                `<div class="col-{{ $maxFiles === 1 ? 12 : 4 }}" data-file-id="${id}">
                    <div class="thumb">
                        <img class="w-100" src="${url}" alt="dz-thumb" />
                        <div class="preview-actions">
                            <a href="${downloadUrl}" title="{{ __('global.download') }}"><i class="material-icons-outlined md-18">cloud_download</i> {{ __('global.download') }}</a>
                            <a href="javascript:void(0);" onclick="editFile(${id})" title="{{ __('global.update') }}"><i class="material-icons-outlined md-18">edit</i> {{ __('global.update') }}</a>
                            <a href="javascript:void(0);" onclick="removeFile(${id})" title="{{ __('dropzone.remove_file') }}"><i class="material-icons-outlined md-18">delete</i> {{ __('dropzone.remove_file') }}</a>
                            <a href="javascript:void(0);" onclick="objectFitToggle(this)" title="{{ __('dropzone.show_full') }}"><i class="material-icons-outlined md-18">zoom_out_map</i> {{ __('dropzone.show_full') }}</a>
                        </div>
                    </div>
                </div>`)
        }

            @forelse($files as $file)
                createPreview{{$index}}({{ $file->id }}, '{{ asset('storage/' . $file->path) }}')
            @empty

            @endforelse
    </script>
@endpush
