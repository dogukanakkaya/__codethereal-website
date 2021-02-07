{{ Form::textarea($name, '', ['class' => 'form-control rich-editor']) }}

@once
    @push('scripts')
        <script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
        <script>
            @if($basic)
                const options = {
                    plugins: 'paste print preview searchreplace autolink directionality visualblocks visualchars fullscreen link media template table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern',
                    paste_as_text: true,
                    contextmenu: 'link table spellchecker',
                    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | link | removeformat',
                }
            @else
                const options = {
                    plugins: 'image paste print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern code',
                    paste_as_text: true,
                    contextmenu: 'link image imagetools table spellchecker',
                    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | link | image | removeformat',
                    images_upload_handler: function (blobInfo, success, failure) {
                        const tinymceImageFormData = new FormData();
                        tinymceImageFormData.append('file', blobInfo.blob(), blobInfo.filename());
                        tinymceImageFormData.append('folder', 'tinymce')
                        tinymceImageFormData.append('_token', '{{ csrf_token() }}')
                        request.post('{{ route('files.upload') }}', tinymceImageFormData)
                            .then(function (response) {
                                success(response.data.path);
                            })
                            .catch(function (err) {
                                failure('HTTP Error: ' + err.message);
                            });
                    },
                }
            @endif

            tinymce.init({
                selector: 'textarea.rich-editor',
                width: '100%',
                height: 500,
                draggable_modal: true,
                //statusbar: false,
                //language: "tr_TR",
                ...options,
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save()
                    })
                }
            })
        </script>
    @endpush
@endonce
