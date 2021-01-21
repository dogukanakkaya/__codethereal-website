@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ce/datatable.css') }}">
@endpush

<table class="table table-hover" id="ce-datatable"></table>

@push('scripts')
    <script>
        let dt
        $(function () {
            dt = $('#ce-datatable').DataTable({
                dom: '<"dt-top"lf><"dt-body"t><"dt-bottom"ip>',
                processing: true,
                serverSide: true,
                ajax: '{{ $url }}',
                columns: @json($columns)
            });
        })
        function __refresh(){
            dt.ajax.reload()
        }

        @if($deleteChecked)
        /* Delete Checked */
        // On change event for header checkbox (for checking all)
        const __checkAll = () => {
            const el = document.getElementById('check-all')
            if (el.checked){
                document.getElementsByName('checked[]').forEach(check => {
                    check.checked = true
                })
            }else{
                document.getElementsByName('checked[]').forEach(check => {
                    check.checked = false
                })
            }
            __onCheck(el.checked)
        }

        // Show or hide remove button, fill the check-all box. On change event for all checkboxes
        const __onCheck = () => {
            const isAnyChecked = document.querySelector('input[name="checked[]"]:checked')
            const buttonEl = document.querySelector('.delete-checked')
            // Show button if any of them is checked, if none is checked hide the button
            if (isAnyChecked && buttonEl.classList.contains('d-none')){
                buttonEl.classList.remove('d-none')
            }else if(!buttonEl.classList.contains('d-none') && !isAnyChecked){
                buttonEl.classList.add('d-none')
            }

            // Check, check all box if every single checkbox is checked
            const uncheckedExist = document.querySelector('input[name="checked[]"]:not(:checked)')
            document.getElementById('check-all').checked = !uncheckedExist;
        }

        // Delete checked
        const __deleteChecked = () => {
            const checkedIds = []
            document.querySelectorAll('input[name="checked[]"]:checked').forEach(check => {
                if (check.checked){
                    checkedIds.push(check.value)
                }
            })
            if (typeof __delete !== 'function') {
                return
            }
            __delete(checkedIds.join(','))
        }
        /* /Delete Checked */
        @endif
    </script>
@endpush
